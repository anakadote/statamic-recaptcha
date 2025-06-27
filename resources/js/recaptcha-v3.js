if (window.recaptchaV3) {

  // Check for reCAPTCHA config.
  if (
    typeof window.recaptchaV3.siteKey === 'undefined' ||
    window.recaptchaV3.siteKey === ''
  ) {
    console.warn('A RECAPTCHA_V3_SITE_KEY has not been set in .env')
  }

  // reCAPTCHA v3
  const recaptchaScript = document.createElement('script')
  document.head.appendChild(recaptchaScript)
  recaptchaScript.type = 'text/javascript'
  recaptchaScript.src = 'https://www.google.com/recaptcha/api.js?render=' + window.recaptchaV3.siteKey
  recaptchaScript.onload = initRecaptcha

  /**
   * reCAPTCHA is ready.
   */
  function initRecaptcha() {
    if (typeof grecaptcha === 'undefined') {
      console.error('grecaptcha not loaded.')
      return
    }

    grecaptcha.ready(async () => {

      // Verify on form submission.
      attachRecaptchaToForms()

      // Verify on page load? 
      if (window.recaptchaV3.verifyOnPageLoad) {
        verifyOnPageLoad()
      }
    })
  }

  /**
   * Verify on page load.
   * 
   * This will remove forms from the DOM if the verification fails.
   */
  async function verifyOnPageLoad() {
    const token = await grecaptcha.execute(window.recaptchaV3.siteKey, {action: 'pageload/' + window.recaptchaV3.action})

    try {
      const resp = await fetch('/!/statamic-recaptcha/verify-recaptcha-v3-token', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
          'X-Xsrf-Token': getToken(),
        },
        body: JSON.stringify({
          token: token, 
          action: 'pageload/' + window.recaptchaV3.action,
        })
      })

      if (! resp.ok) {
        const data = await resp.json().catch(() => ({}))
        handleVerifyOnPageLoadError(data.error)
      }

    } catch (err) {
      handleVerifyOnPageLoadError()
    }
  }

  /**
   * Handle an on page load verification error.
   */
  function handleVerifyOnPageLoadError(msg) {
    const forms = document.querySelectorAll('form:not(.nocaptcha)')
    let errorMessage

    // Remove forms from the DOM and replace with an error message.
    forms.forEach(form => {
      errorMessage = document.createElement('div')
      errorMessage.className = 'alert alert-danger p-2 rounded bg-red-500'
      errorMessage.setAttribute('role', 'alert')
      errorMessage.innerHTML = msg || 'Sorry, but you look like a robot.'

      form.parentNode.replaceChild(errorMessage, form)
    })
  }

  /** 
   * Attach the reCAPTCHA token and action to each form on submit.
   */
  function attachRecaptchaToForms() {
    const forms = document.querySelectorAll('form:not(.nocaptcha)')
    const actionUrl = 'formsubmission/' + (window.recaptchaV3.action || 'default')

    forms.forEach(form => {
      form.addEventListener('submit', async (e) => {

        e.preventDefault()

        const token = await grecaptcha.execute(window.recaptchaV3.siteKey, {action: actionUrl})

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

  /**
   * Get the CSRF/XSRF token.
   */
  function getToken() {
    const cookieString = document.cookie || ''
    const cookies = cookieString.split(';')

    const xsrfCookies = cookies
      .map(c => c.trim())
      .filter(c => c.startsWith('XSRF-TOKEN='))

    if (xsrfCookies.length === 0) {
      return null
    }

    return decodeURIComponent(xsrfCookies[0].split('=')[1])
  }
}
