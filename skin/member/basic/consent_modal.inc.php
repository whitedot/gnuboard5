<?php if (!defined('_GNUBOARD_')) exit; ?>
<div id="consentDialog"
    class="modal-overlay modal-overlay-fade hs-overlay hidden pointer-events-none opacity-0"
    role="dialog"
    tabindex="-1"
    aria-hidden="true"
    aria-labelledby="consentDialogTitle">
    <div class="modal-dialog modal-dialog-center">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="consentDialogTitle" class="modal-title">안내</h3>
                <button type="button" class="modal-close" aria-label="닫기" data-hs-overlay="#consentDialog">
                    <span class="sr-only">닫기</span>
                    <span class="close-icon" aria-hidden="true"></span>
                </button>
            </div>
            <div id="consentDialogBody" class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary modal-action" data-hs-overlay="#consentDialog">닫기</button>
                <button type="button" class="btn btn-primary modal-action js-consent-agree">동의합니다</button>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
  var overlay = document.getElementById('consentDialog');
  if (!overlay) return;

  var body = document.getElementById('consentDialogBody');
  var title = document.getElementById('consentDialogTitle');

  var normalizeSelector = function (selector) {
    return selector && selector.charAt(0) === '#' ? selector : '#' + selector;
  };

  document.addEventListener('click', function (event) {
    var trigger = event.target.closest('.js-open-consent');
    if (trigger) {
      var templateSelector = trigger.getAttribute('data-template');
      var template = templateSelector ? document.querySelector(templateSelector) : null;

      title.textContent = trigger.getAttribute('data-title') || '안내';
      body.innerHTML = template ? template.innerHTML : '';
      overlay.dataset.check = trigger.getAttribute('data-check') || '';
      overlay.dataset.checkGroup = trigger.getAttribute('data-check-group') || '';
      trigger.setAttribute('data-hs-overlay', '#consentDialog');
      return;
    }

    var agreeButton = event.target.closest('.js-consent-agree');
    if (!agreeButton) {
      return;
    }

    var checkSelector = overlay.dataset.check;
    var checkGroupSelector = overlay.dataset.checkGroup;

    if (checkGroupSelector) {
      document.querySelectorAll(checkGroupSelector).forEach(function (checkbox) {
        checkbox.checked = true;
        checkbox.dispatchEvent(new Event('change', { bubbles: true }));
      });
    }

    if (checkSelector) {
      var checkbox = document.querySelector(checkSelector);
      if (checkbox) {
        checkbox.checked = true;
        checkbox.dispatchEvent(new Event('change', { bubbles: true }));
      }
    }

    var closeButton = overlay.querySelector('[data-hs-overlay="#consentDialog"]');
    if (closeButton) {
      closeButton.click();
    }
  });

  overlay.addEventListener('transitionend', function () {
    if (overlay.classList.contains('hs-overlay-open') || overlay.classList.contains('open')) {
      return;
    }

    body.innerHTML = '';
    overlay.dataset.check = '';
    overlay.dataset.checkGroup = '';
  });
})();
</script>
