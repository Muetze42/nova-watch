/**
 * We'll load the axios HTTP library, which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import axios from 'axios'

window.axios = axios

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
window.axios.defaults.baseURL = '/api/'

window.errorHandler = function (error) {
  let status = error.response.status
  if (status === 419 || status === 503) {
    let message =
      status === 419
        ? 'Your session has expired. Click OK to reload the page.'
        : 'There is an update in progress. This lasts only a few seconds.'
    alert(message)
    location.reload()
  } else {
    error.response && error.response.data.message
      ? alert('Error ' + status + ': ' + error.response.data.message)
      : alert('Error ' + status)
  }
}
