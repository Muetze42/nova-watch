import './bootstrap'

import { createApp, h } from 'vue'
import { createInertiaApp, Link } from '@inertiajs/vue3'
import Layout from '@/Layout.vue'
import CompareSection from '@/Components/CompareSection.vue'
import CompareIcon from '@/Components/CompareIcon.vue'
import Dialog from '@/Components/Dialog/Dialog.vue'

/**
 * Font Awesome
 */
import { FontAwesomeIcon, FontAwesomeLayers } from '@fortawesome/vue-fontawesome'
import { library } from '@fortawesome/fontawesome-svg-core'
// Brands
import { faGithub } from '@fortawesome/free-brands-svg-icons'
library.add(faGithub)
// Regular
import { faSquarePlus, faSquareMinus, faSquare } from '@fortawesome/free-regular-svg-icons'
library.add(faSquarePlus, faSquareMinus, faSquare)
// Solid
import { faCircle, faSun, faMoon, faCode, faRightLeft } from '@fortawesome/free-solid-svg-icons'
library.add(faCircle, faSun, faMoon, faCode, faRightLeft)

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
            console.log(error)
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
      .component('Dialog', Dialog)
      .component('Link', Link)
      .component('CompareSection', CompareSection)
      .component('CompareIcon', CompareIcon)
      .component('FontAwesomeIcon', FontAwesomeIcon)
      .component('FontAwesomeLayers', FontAwesomeLayers)
      .mount(el)
  }
}).then()
