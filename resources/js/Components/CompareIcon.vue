<template>
  <font-awesome-icon :icon="['far', actionIcon[action]]" size="lg" fixed-width />
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
  },
  data() {
    return {
      actionIcon: {
        created: 'square-plus',
        deleted: 'square-minus',
        updated: 'pen-to-square'
      }
    }
  }
}
</script>
