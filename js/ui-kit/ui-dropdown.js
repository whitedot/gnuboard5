(function () {
  'use strict';

  var DROPDOWN_SELECTOR = '.hs-dropdown';
  var TOGGLE_SELECTOR = '.hs-dropdown-toggle';
  var MENU_SELECTOR = '.hs-dropdown-menu';
  var OPEN_CLASS = 'hs-dropdown-open';
  var LEGACY_OPEN_CLASS = 'open';
  var VIEWPORT_GAP = 8;
  var MENU_OFFSET = 6;

  var opened = [];

  function getElementTarget(target) {
    if (!target) {
      return null;
    }

    if (target.nodeType === 1) {
      return target;
    }

    return target.parentElement || null;
  }

  function findClosest(target, selector) {
    var element = getElementTarget(target);
    return element && typeof element.closest === 'function' ? element.closest(selector) : null;
  }

  function parseOption(dropdown, name, fallback) {
    var className = dropdown && dropdown.className ? String(dropdown.className) : '';
    var match = className.match(new RegExp('\\[--' + name + ':([^\\]]+)\\]'));
    var raw = match ? match[1] : '';

    if (!raw && window.getComputedStyle) {
      raw = window.getComputedStyle(dropdown).getPropertyValue('--' + name);
    }

    var value = String(raw || '').trim().toLowerCase();
    if (!value) {
      return fallback;
    }

    if ((value.charAt(0) === '"' && value.charAt(value.length - 1) === '"') || (value.charAt(0) === "'" && value.charAt(value.length - 1) === "'")) {
      value = value.slice(1, -1);
    }

    return value || fallback;
  }

  function getConfig(dropdown) {
    if (!dropdown._dropdownConfig) {
      dropdown._dropdownConfig = {
        trigger: parseOption(dropdown, 'trigger', 'click'),
        placement: parseOption(dropdown, 'placement', 'bottom-start'),
        autoClose: parseOption(dropdown, 'auto-close', 'all')
      };
    }

    return dropdown._dropdownConfig;
  }

  function getRefs(dropdown) {
    if (!dropdown) {
      return null;
    }

    var toggle = dropdown.querySelector(TOGGLE_SELECTOR);
    var menu = dropdown.querySelector(MENU_SELECTOR);

    if (!toggle || !menu) {
      return null;
    }

    return { toggle: toggle, menu: menu };
  }

  function measure(menu) {
    var oldDisplay = menu.style.display;
    var oldVisibility = menu.style.visibility;
    var oldPointer = menu.style.pointerEvents;

    menu.style.display = 'block';
    menu.style.visibility = 'hidden';
    menu.style.pointerEvents = 'none';

    var result = {
      width: menu.offsetWidth,
      height: menu.offsetHeight
    };

    menu.style.display = oldDisplay;
    menu.style.visibility = oldVisibility;
    menu.style.pointerEvents = oldPointer;

    return result;
  }

  function normalizePlacement(placement) {
    var value = String(placement || 'bottom-start').toLowerCase();

    if (value === 'bottom') {
      return { side: 'bottom', align: 'center' };
    }

    if (value === 'top') {
      return { side: 'top', align: 'center' };
    }

    if (value === 'bottom-right') {
      return { side: 'bottom', align: 'end' };
    }

    if (value === 'bottom-left') {
      return { side: 'bottom', align: 'start' };
    }

    if (value === 'top-left') {
      return { side: 'top', align: 'start' };
    }

    if (value === 'top-right') {
      return { side: 'top', align: 'end' };
    }

    var parts = value.split('-');
    var side = parts[0] || 'bottom';
    var align = parts[1] || (side === 'top' || side === 'bottom' ? 'start' : 'center');

    if (align === 'left') {
      align = 'start';
    }

    if (align === 'right') {
      align = 'end';
    }

    return { side: side, align: align };
  }

  function clamp(value, min, max) {
    if (max < min) {
      return min;
    }

    return Math.min(Math.max(value, min), max);
  }

  function getViewportBounds() {
    return {
      left: VIEWPORT_GAP,
      top: VIEWPORT_GAP,
      right: Math.max(VIEWPORT_GAP, window.innerWidth - VIEWPORT_GAP),
      bottom: Math.max(VIEWPORT_GAP, window.innerHeight - VIEWPORT_GAP)
    };
  }

  function getSideCandidates(side) {
    if (side === 'top') {
      return ['top', 'bottom'];
    }

    if (side === 'left') {
      return ['left', 'right'];
    }

    if (side === 'right') {
      return ['right', 'left'];
    }

    return ['bottom', 'top'];
  }

  function getCandidatePosition(toggleRect, menuSize, side, align) {
    var left = toggleRect.left;
    var top = toggleRect.bottom + MENU_OFFSET;

    if (side === 'top') {
      top = toggleRect.top - menuSize.height - MENU_OFFSET;
    } else if (side === 'left') {
      left = toggleRect.left - menuSize.width - MENU_OFFSET;
      top = toggleRect.top;
    } else if (side === 'right') {
      left = toggleRect.right + MENU_OFFSET;
      top = toggleRect.top;
    }

    if (side === 'top' || side === 'bottom') {
      if (align === 'end') {
        left = toggleRect.right - menuSize.width;
      } else if (align === 'center') {
        left = toggleRect.left + (toggleRect.width - menuSize.width) / 2;
      }
    } else {
      if (align === 'end') {
        top = toggleRect.bottom - menuSize.height;
      } else if (align === 'center') {
        top = toggleRect.top + (toggleRect.height - menuSize.height) / 2;
      }
    }

    return { left: left, top: top };
  }

  function getOverflowScore(position, menuSize, bounds) {
    var overflowLeft = Math.max(0, bounds.left - position.left);
    var overflowTop = Math.max(0, bounds.top - position.top);
    var overflowRight = Math.max(0, position.left + menuSize.width - bounds.right);
    var overflowBottom = Math.max(0, position.top + menuSize.height - bounds.bottom);

    return overflowLeft + overflowTop + overflowRight + overflowBottom;
  }

  function choosePlacement(toggleRect, menuSize, placement, bounds) {
    var sides = getSideCandidates(placement.side);
    var best = null;

    for (var i = 0; i < sides.length; i += 1) {
      var side = sides[i];
      var position = getCandidatePosition(toggleRect, menuSize, side, placement.align);
      var score = getOverflowScore(position, menuSize, bounds) + (i > 0 ? 1 : 0);

      if (!best || score < best.score) {
        best = {
          side: side,
          left: position.left,
          top: position.top,
          score: score
        };
      }
    }

    return best || {
      side: placement.side,
      left: toggleRect.left,
      top: toggleRect.bottom + MENU_OFFSET
    };
  }

  function place(dropdown) {
    var refs = getRefs(dropdown);
    if (!refs) {
      return;
    }

    var config = getConfig(dropdown);
    var placement = normalizePlacement(config.placement);

    var menuSize = measure(refs.menu);
    var toggleRect = refs.toggle.getBoundingClientRect();
    var bounds = getViewportBounds();
    var selected = choosePlacement(toggleRect, menuSize, placement, bounds);

    var maxLeft = bounds.right - menuSize.width;
    var maxTop = bounds.bottom - menuSize.height;
    var safeLeft = clamp(selected.left, bounds.left, maxLeft);
    var safeTop = clamp(selected.top, bounds.top, maxTop);

    var availableWidth = Math.max(80, Math.floor(bounds.right - safeLeft));
    var availableHeight = Math.max(80, Math.floor(bounds.bottom - safeTop));

    refs.menu.style.position = 'fixed';
    refs.menu.style.left = safeLeft + 'px';
    refs.menu.style.top = safeTop + 'px';
    refs.menu.style.margin = '0';
    refs.menu.style.maxWidth = availableWidth + 'px';
    refs.menu.style.maxHeight = availableHeight + 'px';
    refs.menu.style.overflowY = 'auto';
  }

  function remember(dropdown) {
    if (opened.indexOf(dropdown) === -1) {
      opened.push(dropdown);
    }
  }

  function forget(dropdown) {
    var index = opened.indexOf(dropdown);
    if (index > -1) {
      opened.splice(index, 1);
    }
  }

  function close(dropdown) {
    var refs = getRefs(dropdown);
    if (!refs) {
      return;
    }

    dropdown.classList.remove(OPEN_CLASS);
    dropdown.classList.remove(LEGACY_OPEN_CLASS);

    refs.toggle.setAttribute('aria-expanded', 'false');

    refs.menu.setAttribute('aria-hidden', 'true');
    refs.menu.style.display = 'none';
    refs.menu.style.opacity = '0';
    refs.menu.style.pointerEvents = 'none';
    refs.menu.style.zIndex = '';
    refs.menu.style.position = '';
    refs.menu.style.left = '';
    refs.menu.style.top = '';
    refs.menu.style.margin = '';
    refs.menu.style.maxWidth = '';
    refs.menu.style.maxHeight = '';
    refs.menu.style.overflowY = '';

    forget(dropdown);
  }

  function closeAll(except) {
    opened.slice().forEach(function (dropdown) {
      if (!except || dropdown !== except) {
        close(dropdown);
      }
    });
  }

  function open(dropdown) {
    var refs = getRefs(dropdown);
    if (!refs) {
      return;
    }

    closeAll(dropdown);
    place(dropdown);

    dropdown.classList.add(OPEN_CLASS);
    dropdown.classList.add(LEGACY_OPEN_CLASS);

    refs.toggle.setAttribute('aria-expanded', 'true');

    refs.menu.setAttribute('aria-hidden', 'false');
    refs.menu.style.display = 'block';
    refs.menu.style.opacity = '1';
    refs.menu.style.pointerEvents = 'auto';
    refs.menu.style.zIndex = '200';

    remember(dropdown);
  }

  function toggle(dropdown) {
    if (dropdown.classList.contains(OPEN_CLASS)) {
      close(dropdown);
    } else {
      open(dropdown);
    }
  }

  function autoCloseInside(dropdown) {
    var mode = getConfig(dropdown).autoClose;
    return mode === 'all' || mode === 'inside' || mode === 'true';
  }

  function autoCloseOutside(dropdown) {
    var mode = getConfig(dropdown).autoClose;
    return mode === 'all' || mode === 'outside' || mode === 'true';
  }

  function isFormElement(target) {
    return !!findClosest(target, 'form, input, textarea, select, label, button[type="submit"]');
  }

  function init(dropdown) {
    var refs = getRefs(dropdown);
    if (!refs) {
      return;
    }

    refs.toggle.setAttribute('aria-expanded', 'false');
    refs.menu.setAttribute('aria-hidden', 'true');
    refs.menu.style.display = 'none';
    refs.menu.style.opacity = '0';
    refs.menu.style.pointerEvents = 'none';
    refs.menu.style.position = '';
    refs.menu.style.left = '';
    refs.menu.style.top = '';
    refs.menu.style.margin = '';
    refs.menu.style.maxWidth = '';
    refs.menu.style.maxHeight = '';
    refs.menu.style.overflowY = '';

    if (getConfig(dropdown).trigger === 'hover' && !dropdown._hoverBound) {
      dropdown._hoverBound = true;

      var openTimer = null;
      var closeTimer = null;

      var requestOpen = function () {
        clearTimeout(closeTimer);
        openTimer = setTimeout(function () {
          open(dropdown);
        }, 70);
      };

      var requestClose = function () {
        clearTimeout(openTimer);
        closeTimer = setTimeout(function () {
          close(dropdown);
        }, 110);
      };

      dropdown.addEventListener('mouseenter', requestOpen);
      dropdown.addEventListener('mouseleave', requestClose);
      dropdown.addEventListener('focusin', requestOpen);
      dropdown.addEventListener('focusout', function (event) {
        if (!dropdown.contains(event.relatedTarget)) {
          requestClose();
        }
      });
    }
  }

  document.addEventListener('click', function (event) {
    var toggleButton = findClosest(event.target, TOGGLE_SELECTOR);
    if (toggleButton) {
      var targetDropdown = toggleButton.closest(DROPDOWN_SELECTOR);
      if (!targetDropdown) {
        return;
      }

      if (toggleButton.disabled || toggleButton.getAttribute('aria-disabled') === 'true') {
        event.preventDefault();
        return;
      }

      event.preventDefault();
      toggle(targetDropdown);
      return;
    }

    var clickedMenu = findClosest(event.target, MENU_SELECTOR);
    if (clickedMenu) {
      var menuDropdown = clickedMenu.closest(DROPDOWN_SELECTOR);
      if (menuDropdown && autoCloseInside(menuDropdown) && !isFormElement(event.target)) {
        close(menuDropdown);
      }
      return;
    }

    opened.slice().forEach(function (dropdown) {
      if (autoCloseOutside(dropdown)) {
        close(dropdown);
      }
    });
  });

  document.addEventListener('keydown', function (event) {
    if (event.key === 'Escape' && opened.length) {
      close(opened[opened.length - 1]);
    }
  });

  function repositionOpened() {
    opened.slice().forEach(function (dropdown) {
      place(dropdown);
    });
  }

  window.addEventListener('resize', repositionOpened);
  window.addEventListener('scroll', repositionOpened, true);

  document.querySelectorAll(DROPDOWN_SELECTOR).forEach(init);
})();
