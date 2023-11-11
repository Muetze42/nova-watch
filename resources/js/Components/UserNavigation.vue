<template>
  <div v-if="user"></div>
  <div v-else>
    <button class="btn h-7" type="button" @click="showLogin = true">
      <font-awesome-icon :icon="['fab', 'github']" fixed-width />
      Login
    </button>
  </div>
  <Dialog :show="showLogin" title="Login" :with-footer="false" @close="showLogin = false">
    <div class="p-4 text-center flex flex-col gap-4">
      <label class="checkbox">
        <input v-model="accepted" type="checkbox" class="form-checkbox" value="1" />
        I Agree to <a href="https://huth.it/privacy" target="_blank">Privacy Policy</a>
      </label>
      <div>
        <button type="button" class="btn px-2" :disabled="!accepted" value="1" @click="login">
          <Spinner v-if="processing" />
          <font-awesome-icon v-else :icon="['fab', 'github']" fixed-width />
          Login
        </button>
      </div>
    </div>
  </Dialog>
</template>

<script>
import { router } from '@inertiajs/vue3'

export default {
  data() {
    return {
      accepted: false,
      showLogin: false,
      processing: false
    }
  },
  methods: {
    login() {
      let ref = this
      this.processing = true
      axios
        .post('/auth/github')
        .then(function (response) {
          if (response && response.status === 200) {
            window.location.href = response.data
          }
        })
        .catch(function (error) {
          error.response.status === 422
            ? (ref.formError = error.response.data.message)
            : this.errorHandler(error)
          ref.processing = false
        })
    }
  }
}
</script>
