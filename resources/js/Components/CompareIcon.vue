<template>
  <font-awesome-layers v-if="action === 'updated'" fixed-width>
    <font-awesome-icon :icon="['far', 'square']" size="lg" />
    <font-awesome-icon
      :icon="['fas', 'circle']"
      size="2xs"
      class="text-achromatic-700 dark:text-achromatic-400"
    />
  </font-awesome-layers>
  <font-awesome-icon
    v-else
    :icon="['far', 'square-' + (action === 'created' ? 'plus' : 'minus')]"
    size="lg"
    fixed-width
  />
</template>

<script>
export default {
  name: 'CompareIcon',
  props: {
    action: {
      type: String,
      required: false,
      default: 'updated',
      validator(value) {
        return ['created', 'deleted', 'updated'].includes(value)
      }
    }
  }
}
</script>
