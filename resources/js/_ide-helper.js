/* Pseudo file for IDE */
import Vue from 'vue'
import {Link} from '@inertiajs/vue3'

/**
 * @property {Object} user
 * @property {String} user.email
 * @property {String} user.name
 * */
Vue.component('Link', Link)
Vue.mixin({
  data() {
    return {
      user: {
        name: String,
        email: String
      },
      licensed: Boolean
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

window.errorHandler = function (error) {
  console.log(error)
}

/**
 * @param {Number} count
 * @param {String} singular
 * @param {String} plural
 */
window.transChoice = function (count, singular, plural) {
  return count === 1 ? singular : plural
}
