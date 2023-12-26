<template>
  <footer class="border-t border-primary-200 dark:bg-primary-900 dark:border-primary-800 text-sm">
    <div class="content flex flex-wrap items-center justify-center gap-2 py-1">
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
    </div>
  </footer>
</template>
<script setup></script>
