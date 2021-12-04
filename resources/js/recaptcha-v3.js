if (window.recaptchaV3) {

  // Check for reCAPTCHA config.
  if (
    window.recaptchaV3.siteKey == 'undefined' ||
    window.recaptchaV3.siteKey == ''
  ) {
    console.log('A RECAPTCHA_V3_SITE_KEY has not be set in .env')
  }

  // reCAPTCHA v3
  const recaptchaScript = document.createElement('script')
  document.head.appendChild(recaptchaScript)
  recaptchaScript.type = 'text/javascript'
  recaptchaScript.src = '//www.google.com/recaptcha/api.js?render=' + window.recaptchaV3.siteKey
  recaptchaScript.onload = initRecaptcha

  // Axios.js
  if (typeof axios == 'undefined') {
    const axiosScript = document.createElement('script')
    document.head.appendChild(axiosScript)
    axiosScript.type = 'text/javascript'
    axiosScript.src = '//cdnjs.cloudflare.com/ajax/libs/axios/0.24.0/axios.min.js'
  }

  // reCAPTCHA is ready.
  function initRecaptcha() {
    grecaptcha.ready(function() {
      const verifyUrl = '/!/statamic-recaptcha/verify-recaptcha-v3-token'

      // Verify on load, and remove forms from the DOM if the verification failed.
      grecaptcha.execute(window.recaptchaV3.siteKey, {action: 'pageload/' + window.recaptchaV3.action})
        .then(function(token) {
          const forms = document.querySelectorAll('form:not(.nocaptcha)')

          axios.post(verifyUrl, {token: token, action: 'pageload/' + window.recaptchaV3.action})
            .then(function(response) {

              // Attach the reCAPTCHA token and action to each form.
              for (let i = 0; i < forms.length; ++i) {
                grecaptcha.execute(window.recaptchaV3.siteKey, {action: 'formsubmission/' + window.recaptchaV3.action})
                  .then(function(token) {

                    const tokenInput = document.createElement('input')
                    tokenInput.type = 'hidden'
                    tokenInput.name = 'captcha_token'
                    tokenInput.value = token
                    forms[i].appendChild(tokenInput)

                    const actionInput = document.createElement('input')
                    actionInput.type = 'hidden'
                    actionInput.name = 'captcha_action'
                    actionInput.value = 'formsubmission/' + window.recaptchaV3.action
                    forms[i].appendChild(actionInput)
                  })
              }
            })
            .catch(function(error) {
              let errorMessage

              // Remove forms from the DOM and replace with an error message.
              for (let i = 0; i < forms.length; ++i) {
                errorMessage = document.createElement('div')
                errorMessage.className = 'alert alert-danger p-2 rounded bg-red-500'
                errorMessage.setAttribute('role', 'alert')
                errorMessage.innerHTML = error.response.data.error || 'Sorry, but you look like a robot.'

                forms[i].parentNode.replaceChild(errorMessage, forms[i])
              }
            })
        })
    })
  }
}
