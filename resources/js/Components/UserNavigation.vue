<script setup>
import { Menu, MenuButton, MenuItems, MenuItem } from '@headlessui/vue'
</script>
<template>
  <div v-if="user">
    <Menu v-slot="{ open }" as="div" class="relative inline-block">
      <MenuButton
        as="button"
        class="transition-all transform btn h-7 min-w-[8rem] flex justify-between gap-1 pr-2"
      >
        {{ user.name }}
        <font-awesome-icon :icon="['fas', 'caret-right']" :class="{ 'rotate-90': open }" />
      </MenuButton>
      <transition
        enter-active-class="transition duration-100 ease-out"
        enter-from-class="transform scale-95 opacity-0"
        enter-to-class="transform scale-100 opacity-100"
        leave-active-class="transition duration-75 ease-in"
        leave-from-class="transform scale-100 opacity-100"
        leave-to-class="transform scale-95 opacity-0"
      >
        <MenuItems
          class="absolute w-full bg-achromatic-200 dark:bg-achromatic-800 menu-items border border-achromatic-400/50 dark:border-achromatic-700/50 divide-y divide-achromatic-400/30 dark:divide-achromatic-700/30"
        >
          <MenuItem as="button" type="button" @click="logout">Logout</MenuItem>
        </MenuItems>
      </transition>
    </Menu>
  </div>
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
    },
    logout() {
      if (confirm('Do you really want to log out?')) {
        router.post('/auth/logout')
      }
    }
  }
}
</script>
