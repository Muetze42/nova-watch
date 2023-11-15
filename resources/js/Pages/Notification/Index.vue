<template>
  <section
    v-for="resource in resources"
    :key="resource"
    class="flex items-center relative gap-1.5 max-w-sm w-full mx-auto"
  >
    <span class="absolute -right-1 -top-1 flex h-3 w-3">
      <span
        class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75"
        :class="resource.active ? 'bg-green-400' : 'bg-red-400'"
      />
      <span
        class="relative inline-flex rounded-full h-3 w-3"
        :class="resource.active ? 'bg-green-500' : 'bg-red-500'"
      />
    </span>
    <Link :href="'notifications/' + resource.path">
      {{ resource.label }}
    </Link>
  </section>
</template>

<script>
export default {
  props: {
    resources: {
      type: Object,
      required: true
    }
  }
}
</script>
