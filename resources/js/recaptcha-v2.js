if (window.recaptchaV2) {

  // Check for reCAPTCHA config.
  if (
    window.recaptchaV2.siteKey == 'undefined' ||
    window.recaptchaV2.siteKey == ''
  ) {
    console.warn('A RECAPTCHA_V2_SITE_KEY has not been set in .env')
  }

  // reCAPTCHA v2
  const recaptchaScript = document.createElement('script')
  document.head.appendChild(recaptchaScript)
  recaptchaScript.type = 'text/javascript'
  recaptchaScript.src = '//www.google.com/recaptcha/api.js?onload=onloadRecaptchaCallback&render=explicit'
  recaptchaScript.setAttribute('async', true)
  recaptchaScript.setAttribute('defer', true)

  // reCAPTCHA is ready.
  function onloadRecaptchaCallback() {
    const forms = document.querySelectorAll('form:not(.nocaptcha)')

    for (let i = 0; i < forms.length; i++) {

      // Invisible
      if (window.recaptchaV2.size == 'invisible') {
        if (forms[i].querySelector('button[type="submit"]')) {
          grecaptcha.render(forms[i].querySelector('button[type="submit"]'), {
            sitekey: window.recaptchaV2.siteKey,
            size: 'invisible',
            callback: function(token) {
              forms[i].querySelector('button[type="submit"]').disabled = true

              const tokenInput = document.createElement('input')
              tokenInput.type = 'hidden'
              tokenInput.name = 'g-recaptcha-response'
              tokenInput.value = token
              forms[i].appendChild(tokenInput)
              forms[i].submit()
            }
          })
        }

      // Checkbox
      } else {
        if (forms[i].querySelector('.g-recaptcha')) {
          grecaptcha.render(forms[i].querySelector('.g-recaptcha'), {
            sitekey: window.recaptchaV2.siteKey,
            theme: window.recaptchaV2.theme || 'light',
            size: window.recaptchaV2.size || 'normal',
            tabindex: window.recaptchaV2.tabindex || 0
          })
        }
      }
    }
  }
}
