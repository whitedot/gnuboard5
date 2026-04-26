window.AdminMemberList = {
    init() {
        const form = document.querySelector('[data-admin-member-list]');
        if (!form) {
            return;
        }

        const deleteForm = document.getElementById('member_delete_form');
        const checkAllInput = form.querySelector('#chkall');
        const placeSideview = dropdown => {
            if (!dropdown || !dropdown.classList.contains('hs-dropdown-open')) {
                return;
            }

            const toggle = dropdown.querySelector('.hs-dropdown-toggle');
            const menu = dropdown.querySelector('.hs-dropdown-menu');
            if (!toggle || !menu) {
                return;
            }

            menu.style.display = 'block';
            menu.style.position = 'fixed';
            menu.style.minWidth = '8.5rem';
            menu.style.maxWidth = '18rem';

            const toggleRect = toggle.getBoundingClientRect();
            const menuRect = menu.getBoundingClientRect();
            const viewportGap = 8;
            let top = Math.round(toggleRect.bottom + 10);
            let left = Math.round(toggleRect.left);

            if (left + menuRect.width > window.innerWidth - viewportGap) {
                left = Math.max(viewportGap, Math.round(toggleRect.right - menuRect.width));
            }

            if (top + menuRect.height > window.innerHeight - viewportGap) {
                const topAbove = Math.round(toggleRect.top - menuRect.height - 10);
                top = topAbove >= viewportGap
                    ? topAbove
                    : Math.max(viewportGap, window.innerHeight - menuRect.height - viewportGap);
            }

            menu.style.left = left + 'px';
            menu.style.top = top + 'px';
        };

        form.addEventListener('submit', event => {
            if (!window.AdminSelection.isChecked('chk[]')) {
                alert('선택삭제 하실 항목을 하나 이상 선택하세요.');
                event.preventDefault();
                return;
            }

            if (!confirm('선택한 자료를 정말 삭제하시겠습니까?')) {
                event.preventDefault();
            }
        });

        form.addEventListener('click', event => {
            const submitButton = event.target.closest('input[type="submit"][name="act_button"], button[type="submit"][name="act_button"]');
            if (submitButton) {
                document.pressed = submitButton.value;
            }

            const deleteButton = event.target.closest('[data-member-delete-id]');
            if (!deleteButton || !deleteForm) {
                return;
            }

            if (!confirm('이 회원을 삭제하시겠습니까?')) {
                return;
            }

            deleteForm.elements.mb_id.value = deleteButton.dataset.memberDeleteId || '';
            deleteForm.submit();
        });

        form.addEventListener('ui.dropdown.open', event => {
            placeSideview(event.target);
        });

        if (checkAllInput) {
            checkAllInput.addEventListener('change', () => {
                window.AdminSelection.checkAll(checkAllInput);
            });
        }

        const repositionSideviews = () => {
            form.querySelectorAll('.hs-dropdown.hs-dropdown-open').forEach(placeSideview);
        };

        window.addEventListener('resize', repositionSideviews);
        window.addEventListener('scroll', repositionSideviews, true);
    }
};
