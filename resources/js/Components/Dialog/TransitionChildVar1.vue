<script setup>
import { TransitionChild } from '@headlessui/vue'
</script>

<template>
  <TransitionChild
    as="template"
    enter="ease-out duration-300"
    enter-from="opacity-0"
    enter-to="opacity-100"
    leave="ease-in duration-200"
    leave-from="opacity-100"
    leave-to="opacity-0"
  >
    <slot />
  </TransitionChild>
</template>
