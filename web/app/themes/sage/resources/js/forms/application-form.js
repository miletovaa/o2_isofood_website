const form = document.getElementById('isofood-application-form')

if (form) {
  const submitBtn = form.querySelector('[type="submit"]')
  const statusEl = form.querySelector('.form-status')
  const tsField = form.querySelector('[name="isofood_ts"]')

  if (tsField) {
    tsField.value = window.isofoodApi?.renderedAt ?? Math.floor(Date.now() / 1000)
  }

  form.addEventListener('submit', async (e) => {
    e.preventDefault()

    if (submitBtn) {
      submitBtn.disabled = true
      submitBtn.textContent = submitBtn.dataset.loadingText || 'Submitting…'
    }
    if (statusEl) {
      statusEl.textContent = ''
      statusEl.className = 'form-status'
    }

    try {
      const response = await fetch(`${window.isofoodApi.root}applications`, {
        method: 'POST',
        headers: { 'X-WP-Nonce': window.isofoodApi.nonce },
        body: new FormData(form),
      })

      const data = await response.json()

      if (!response.ok) {
        throw new Error(data.message || 'Something went wrong. Please try again.')
      }

      form.reset()
      form.hidden = true
      const successEl = document.getElementById('isofood-application-success')
      if (successEl) {
        successEl.hidden = false
        successEl.scrollIntoView({ behavior: 'smooth' })
      }
    } catch (err) {
      if (statusEl) {
        statusEl.textContent = err.message
        statusEl.className = 'form-status field-error'
      }
      if (submitBtn) {
        submitBtn.disabled = false
        submitBtn.textContent = submitBtn.dataset.defaultText || 'Submit Application'
      }
    }
  })
}
