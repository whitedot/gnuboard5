function chk_captcha()
{
	var responseField = document.getElementById("g-recaptcha-response");
	if (!responseField || !responseField.value) {
		alert("자동등록방지를 반드시 체크해 주세요.");
		return false;
	}

	return true;
}
