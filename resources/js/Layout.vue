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
      <div>
        <button
          type="button"
          class="btn"
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
  <footer class="border-t border-primary-200 dark:bg-primary-900 dark:border-primary-800">
    <div class="content flex items-center justify-center gap-2 py-1">
      <a href="https://github.com/Muetze42/nova-watch" target="_blank">
        <font-awesome-icon :icon="['fab', 'github']" fixed-width />
        Source
      </a>
      <font-awesome-icon
        :icon="['fas', 'circle']"
        class="text-primary-800/20 dark:text-primary-200/10"
        size="2xs"
      />
      <a href="https://github.com/Muetze42/nova-watch/issues" target="_blank">
        <font-awesome-icon :icon="['fab', 'github']" fixed-width />
        Issues
      </a>
      <font-awesome-icon
        :icon="['fas', 'circle']"
        class="text-primary-800/20 dark:text-primary-200/10"
        size="2xs"
      />
      <span>
        <a href="https://huth.it" target="_blank">
          <font-awesome-icon :icon="['fas', 'code']" title="Created by Norman Huth" /> 2023 by
          Norman Huth
        </a>
      </span>
    </div>
  </footer>
</template>

<script>
export default {
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
