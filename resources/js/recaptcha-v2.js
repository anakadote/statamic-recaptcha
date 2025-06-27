if (window.recaptchaV2) {

  // Check for reCAPTCHA config.
  if (
    typeof window.recaptchaV2.siteKey === 'undefined' ||
    window.recaptchaV2.siteKey === ''
  ) {
    console.warn('A RECAPTCHA_V2_SITE_KEY has not been set in .env')
  }

  // reCAPTCHA v2
  const recaptchaScript = document.createElement('script')
  document.head.appendChild(recaptchaScript)
  recaptchaScript.type = 'text/javascript'
  recaptchaScript.setAttribute('async', true)
  recaptchaScript.setAttribute('defer', true)

  // Build the URL.
  recaptchaScript.src = (() => {
    const baseUrl = 'https://www.google.com/recaptcha/api.js'
    let params = {
      onload: 'onloadRecaptchaCallback',
      render: 'explicit',
    }

    if (window.recaptchaV2.lang) {
      params.hl = window.recaptchaV2.lang
    }

    const url = new URL(baseUrl)
    for (const key in params) {
      url.searchParams.append(key, params[key])
    }

    return url.toString()
  })()

  /**
   * reCAPTCHA is ready.
   */
  window.onloadRecaptchaCallback = function() {
    if (typeof grecaptcha === 'undefined') {
      console.error('grecaptcha not loaded.')
      return
    }

    const forms = document.querySelectorAll('form:not(.nocaptcha)')

    forms.forEach(form => {

      // Invisible
      if (window.recaptchaV2.size == 'invisible') {
        const submitButton = form.querySelector('button[type="submit"]')

        if (submitButton) {
          grecaptcha.render(submitButton, {
            sitekey: window.recaptchaV2.siteKey,
            size: 'invisible',
            callback: (token) => {
              submitButton.disabled = true

              const tokenInput = document.createElement('input')
              tokenInput.type = 'hidden'
              tokenInput.name = 'g-recaptcha-response'
              tokenInput.value = token
              form.appendChild(tokenInput)
              form.submit()
            }
          })
        }

      // Checkbox
      } else {
        const recaptchaEl = form.querySelector('.g-recaptcha')

        if (recaptchaEl) {
          grecaptcha.render(recaptchaEl, {
            sitekey: window.recaptchaV2.siteKey,
            theme: window.recaptchaV2.theme || 'light',
            size: window.recaptchaV2.size || 'normal',
            tabindex: window.recaptchaV2.tabindex || 0,
          })
        }
      }
    })
  }
}
