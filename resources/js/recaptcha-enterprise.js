if (window.recaptchaEnterprise) {

  // Check for reCAPTCHA config.
  if (
    typeof window.recaptchaEnterprise.siteKey === 'undefined' ||
    window.recaptchaEnterprise.siteKey === ''
  ) {
    console.warn('A RECAPTCHA_ENTERPRISE_SITE_KEY has not been set in .env')
  }

  // reCAPTCHA Enterprise
  const recaptchaScript = document.createElement('script')
  document.head.appendChild(recaptchaScript)
  recaptchaScript.type = 'text/javascript'
  recaptchaScript.src = 'https://www.google.com/recaptcha/enterprise.js?render=' + window.recaptchaEnterprise.siteKey
  recaptchaScript.onload = initRecaptcha

  /**
   * reCAPTCHA is ready.
   */
  function initRecaptcha() {

    if (typeof grecaptcha === 'undefined') {
      console.error('grecaptcha not loaded.')
      return
    }

    if (typeof grecaptcha.enterprise === 'undefined') {
      console.error('grecaptcha.enterprise not loaded.')
      return
    }

    grecaptcha.enterprise.ready(async () => {

      // Verify on form submission.
      attachRecaptchaToForms()
    })
  }

  /** 
   * Attach the reCAPTCHA token and action to each form on submit.
   */
  function attachRecaptchaToForms() {
    const forms = document.querySelectorAll('form:not(.nocaptcha)')
    const actionUrl = 'formsubmission/' + (window.recaptchaEnterprise.action || 'default')

    forms.forEach(form => {
      form.addEventListener('submit', async (e) => {

        e.preventDefault()

        const token = await grecaptcha.enterprise.execute(window.recaptchaEnterprise.siteKey, {action: actionUrl})

        const tokenInput = document.createElement('input')
        tokenInput.type = 'hidden'
        tokenInput.name = 'captcha_token'
        tokenInput.value = token
        form.appendChild(tokenInput)

        const actionInput = document.createElement('input')
        actionInput.type = 'hidden'
        actionInput.name = 'captcha_action'
        actionInput.value = actionUrl
        form.appendChild(actionInput)

        form.submit()
      })
    })
  }
}
