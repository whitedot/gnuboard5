(function () {
  'use strict';

  var ACTIVE_CLASS = 'hs-overlay-open';
  var OPEN_CLASS = 'open';
  var HIDDEN_CLASS = 'hidden';
  var DISABLED_CLASS = 'pointer-events-none';
  var FADE_CLASS = 'opacity-0';
  var overlayStack = [];

  var resolveOverlay = function resolveOverlay(selector) {
    if (!selector) {
      return null;
    }

    var trimmed = selector.trim();
    if (!trimmed) {
      return null;
    }

    if (trimmed.startsWith('#')) {
      return document.querySelector(trimmed);
    }

    return document.getElementById(trimmed);
  };

  var lockBodyScroll = function lockBodyScroll() {
    if (!document.body) {
      return;
    }

    if (overlayStack.length) {
      document.body.classList.add('overflow-hidden');
    } else {
      document.body.classList.remove('overflow-hidden');
    }
  };

  var attachBackdropHandler = function attachBackdropHandler(overlay) {
    if (!overlay || overlay._overlayBackdropHandler) {
      return;
    }

    var handler = function handler(event) {
      if (event.target !== overlay) {
        return;
      }

      if (overlay.dataset.hsOverlayStatic === 'true') {
        return;
      }

      hideOverlay(overlay);
    };

    overlay._overlayBackdropHandler = handler;
    overlay.addEventListener('mousedown', handler);
    overlay.addEventListener('touchstart', handler);
  };

  var detachBackdropHandler = function detachBackdropHandler(overlay) {
    if (!overlay || !overlay._overlayBackdropHandler) {
      return;
    }

    overlay.removeEventListener('mousedown', overlay._overlayBackdropHandler);
    overlay.removeEventListener('touchstart', overlay._overlayBackdropHandler);
    overlay._overlayBackdropHandler = null;
  };

  var showOverlay = function showOverlay(overlay) {
    if (!overlay || overlay.classList.contains(ACTIVE_CLASS)) {
      return;
    }

    overlay.setAttribute('aria-hidden', 'false');
    overlay.classList.remove(HIDDEN_CLASS);
    overlay.classList.remove(DISABLED_CLASS);

    requestAnimationFrame(function () {
      overlay.classList.remove(FADE_CLASS);
      overlay.classList.add(ACTIVE_CLASS);
      overlay.classList.add(OPEN_CLASS);
    });

    attachBackdropHandler(overlay);
    overlayStack.push(overlay);
    lockBodyScroll();
  };

  var hideOverlay = function hideOverlay(overlay, options) {
    if (options === void 0) {
      options = {};
    }

    if (!overlay || !overlay.classList.contains(ACTIVE_CLASS)) {
      return;
    }

    if (options.skipStatic && overlay.dataset.hsOverlayStatic === 'true') {
      return;
    }

    overlay.setAttribute('aria-hidden', 'true');
    overlay.classList.add(FADE_CLASS);
    overlay.classList.add(DISABLED_CLASS);
    overlay.classList.remove(ACTIVE_CLASS);
    overlay.classList.remove(OPEN_CLASS);

    var finalize = function finalize(event) {
      if (event && event.target !== overlay) {
        return;
      }

      overlay.classList.add(HIDDEN_CLASS);
      overlay.removeEventListener('transitionend', finalize);
    };

    overlay.addEventListener('transitionend', finalize);
    setTimeout(finalize, 400);

    detachBackdropHandler(overlay);

    var index = overlayStack.lastIndexOf(overlay);
    if (index > -1) {
      overlayStack.splice(index, 1);
    }

    lockBodyScroll();
  };

  var handleTrigger = function handleTrigger(trigger) {
    var selector = trigger.getAttribute('data-hs-overlay');
    var overlay = resolveOverlay(selector);

    if (!overlay) {
      if (typeof console !== 'undefined') {
        console.warn('[ui-overlay] Target not found for selector', selector);
      }
      return;
    }

    if (!overlay.classList.contains('hs-overlay')) {
      return;
    }

    var currentOverlay = trigger.closest('.hs-overlay');
    var isSameOverlay = currentOverlay && currentOverlay === overlay;

    if (isSameOverlay && overlay.classList.contains(ACTIVE_CLASS)) {
      hideOverlay(overlay);
      trigger.setAttribute('aria-expanded', 'false');
      return;
    }

    if (!isSameOverlay && currentOverlay && currentOverlay.classList.contains(ACTIVE_CLASS)) {
      hideOverlay(currentOverlay);
    }

    if (overlay.classList.contains(ACTIVE_CLASS)) {
      hideOverlay(overlay);
      trigger.setAttribute('aria-expanded', 'false');
      return;
    }

    showOverlay(overlay);
    trigger.setAttribute('aria-expanded', 'true');
  };

  document.addEventListener('click', function (event) {
    var trigger = event.target.closest('[data-hs-overlay]');
    if (!trigger) {
      return;
    }

    event.preventDefault();
    handleTrigger(trigger);
  });

  document.addEventListener('keydown', function (event) {
    if (event.key !== 'Escape') {
      return;
    }

    for (var i = overlayStack.length - 1; i >= 0; i -= 1) {
      var overlay = overlayStack[i];
      hideOverlay(overlay, { skipStatic: true });
      if (!overlay.dataset.hsOverlayStatic) {
        break;
      }
    }
  });
})();
