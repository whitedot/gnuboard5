(function () {
  'use strict';

  var DROPDOWN_SELECTOR = '.hs-dropdown';
  var TOGGLE_SELECTOR = '.hs-dropdown-toggle';
  var MENU_SELECTOR = '.hs-dropdown-menu';
  var OPEN_CLASS = 'hs-dropdown-open';
  var LEGACY_OPEN_CLASS = 'open';

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

  function ensureContainer(dropdown) {
    var style = window.getComputedStyle(dropdown);
    if (style && style.position === 'static') {
      dropdown.style.position = 'relative';
    }
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

  function place(dropdown) {
    var refs = getRefs(dropdown);
    if (!refs) {
      return;
    }

    ensureContainer(dropdown);

    var config = getConfig(dropdown);
    var placement = normalizePlacement(config.placement);

    var size = measure(refs.menu);

    var left = refs.toggle.offsetLeft;
    var top = refs.toggle.offsetTop + refs.toggle.offsetHeight;

    if (placement.side === 'top') {
      top = refs.toggle.offsetTop - size.height;
    }

    if (placement.side === 'left') {
      left = refs.toggle.offsetLeft - size.width;
      top = refs.toggle.offsetTop;
    }

    if (placement.side === 'right') {
      left = refs.toggle.offsetLeft + refs.toggle.offsetWidth;
      top = refs.toggle.offsetTop;
    }

    if (placement.side === 'top' || placement.side === 'bottom') {
      if (placement.align === 'end') {
        left = refs.toggle.offsetLeft + refs.toggle.offsetWidth - size.width;
      } else if (placement.align === 'center') {
        left = refs.toggle.offsetLeft + (refs.toggle.offsetWidth - size.width) / 2;
      }
    }

    if (placement.side === 'left' || placement.side === 'right') {
      if (placement.align === 'end') {
        top = refs.toggle.offsetTop + refs.toggle.offsetHeight - size.height;
      } else if (placement.align === 'center') {
        top = refs.toggle.offsetTop + (refs.toggle.offsetHeight - size.height) / 2;
      }
    }

    refs.menu.style.position = 'absolute';
    refs.menu.style.left = left + 'px';
    refs.menu.style.top = top + 'px';
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
