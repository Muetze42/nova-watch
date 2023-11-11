<template>
  <header
    class="fixed w-full bg-white bg-opacity-70 border-b border-primary-200 backdrop-blur dark:bg-primary-900 dark:border-primary-800"
  >
    <div class="content py-2 flex justify-between items-center">
      <ul role="menu" class="inline-flex gap-2">
        <li>
          <Link href="/" class="brand">
            <span>Nova</span>
            <span>Wat.ch</span>
          </Link>
        </li>
        <li>
          <Link href="/faq" class="font-medium hover:text-sky-500">FAQ</Link>
        </li>
      </ul>
      <div class="flex gap-2">
        <UserNavigation />
        <button
          type="button"
          class="btn h-7"
          :aria-label="'Enable ' + (lightTheme ? 'dark' : 'light') + ' theme'"
          @click="switchTheme(lightTheme)"
        >
          <font-awesome-icon v-if="lightTheme" :icon="['fas', 'moon']" fixed-width />
          <font-awesome-icon v-else :icon="['fas', 'sun']" fixed-width />
        </button>
      </div>
    </div>
  </header>
  <main class="content mt-16 grow flex flex-col gap-2">
    <slot />
  </main>
  <Footer />
</template>

<script>
import UserNavigation from '@/Components/UserNavigation.vue'
import Footer from '@/Components/Footer.vue'

export default {
  components: { Footer, UserNavigation },
  data() {
    return {
      lightTheme: false
    }
  },
  mounted() {
    this.applyTheme()
  },
  methods: {
    switchTheme(applyDark) {
      applyDark ? localStorage.removeItem('theme') : localStorage.setItem('theme', 'light')
      this.applyTheme()
    },
    applyTheme() {
      let classList = document.documentElement.classList
      localStorage.theme === 'light' ? classList.remove('dark') : classList.add('dark')
      this.lightTheme = localStorage.theme === 'light'
    }
  }
}
</script>
