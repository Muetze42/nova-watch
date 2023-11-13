import './bootstrap'

import { createApp, h } from 'vue'
import { createInertiaApp, Link } from '@inertiajs/vue3'
import Layout from '@/Layout.vue'
import CompareSection from '@/Components/CompareSection.vue'
import CompareIcon from '@/Components/CompareIcon.vue'
import Spinner from '@/Components/Spinner.vue'
import Dialog from '@/Components/Dialog/Dialog.vue'

/**
 * Font Awesome
 */
import { FontAwesomeIcon, FontAwesomeLayers } from '@fortawesome/vue-fontawesome'
import { library } from '@fortawesome/fontawesome-svg-core'
import { faGithub } from '@fortawesome/free-brands-svg-icons'

library.add(faGithub)

import {
  faSquarePlus,
  faSquareMinus,
  faSquare,
  faPenToSquare
} from '@fortawesome/free-regular-svg-icons'

library.add(faSquarePlus, faSquareMinus, faSquare, faPenToSquare)

import {
  faCircle,
  faSun,
  faMoon,
  faCode,
  faRightLeft,
  faSpinner,
  faCheck,
  faCaretRight,
  faFileLines
} from '@fortawesome/free-solid-svg-icons'

library.add(
  faCircle,
  faSun,
  faMoon,
  faCode,
  faRightLeft,
  faSpinner,
  faCheck,
  faCaretRight,
  faFileLines
)

createInertiaApp({
  resolve: (name) => {
    const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
    let page = pages[`./Pages/${name}.vue`]
    page.default.layout = page.default.layout || Layout
    return page
  },
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .mixin({
        computed: {
          user() {
            return this.$page.props.user
          },
          licensed() {
            return this.$page.props.licensed
          }
        },
        methods: {
          errorHandler(error) {
            let status = error.response.status
            if (status === 419 || status === 503) {
              let message =
                status === 419
                  ? 'Your session has expired. Click OK to reload the page.'
                  : 'There is an update in progress. This lasts only a few seconds.'
              alert(message)
              location.reload()
            } else {
              error.response && error.response.data && error.response.data.message
                ? alert('Error ' + status + ': ' + error.response.data.message)
                : alert('Error ' + status)
            }
          },
          /**
           * @param {Number} count
           * @param {String} singular
           * @param {String} plural
           * @return {string}
           */
          transChoice(count, singular = 'file', plural = 'files') {
            return count === 1 ? singular : plural
          }
        }
      })
      .component('Spinner', Spinner)
      .component('Dialog', Dialog)
      .component('Link', Link)
      .component('CompareSection', CompareSection)
      .component('CompareIcon', CompareIcon)
      .component('FontAwesomeIcon', FontAwesomeIcon)
      .component('FontAwesomeLayers', FontAwesomeLayers)
      .mount(el)
  }
}).then()
