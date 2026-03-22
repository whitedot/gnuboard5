function chk_captcha()
{
	var responseField = document.getElementById("g-recaptcha-response");
	if (!responseField || !responseField.value) {
		grecaptcha.execute();
		return false;
	}

	return true;
}

function recaptcha_validate(token) {
    var responseField = document.getElementById("g-recaptcha-response");
    var form = responseField ? responseField.closest("form") : null;

    if (form) {
        form.submit();
    }

}
