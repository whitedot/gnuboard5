document.addEventListener('DOMContentLoaded', function() {
    window.AdminShell.init();
    window.PopupManager.init();
    window.AdminConfigForm.init();
    window.AdminMemberList.init();
    window.AdminMemberExport.init();
    window.AdminMemberForm.init();
    document.addEventListener('click', function(event) {
        window.AdminSecurity.injectSubmitToken(event);
    });
});
