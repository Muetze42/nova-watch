<template>
  <font-awesome-icon
    :icon="['fas', 'spinner']"
    class="animate-spin"
    :class="iconClass"
    fixed-width
  />
</template>

<script>
export default {
  props: {
    iconClass: {
      type: String,
      required: false,
      default: null
    }
  }
}
</script>
