<script setup>
import { TransitionRoot } from '@headlessui/vue'
import { reactive } from 'vue'
import { router } from '@inertiajs/vue3'

const form = reactive({
  url: null,
  key: null
})

function submit() {
  router.post('/users', form)
}
</script>
<template>
  <CompareSection @show="setShowComparison" />
  <TransitionRoot
    :show="showComparison && comparison !== null"
    enter="transition-opacity duration-250"
    enter-from="opacity-0"
    enter-to="opacity-100"
    leave="transition-opacity duration-250"
    leave-from="opacity-100"
    leave-to="opacity-0"
    class="flex flex-col gap-2"
  >
    <section v-if="licensed">A</section>
    <section v-else class="justify-center text-center flex flex-col gap-2">
      <ul>
        <li v-for="(count, action) in comparison" :key="action">
          <CompareIcon :action="action" />
          {{ count }}
          {{ transChoice(count) }}
          {{ action }}
        </li>
      </ul>
      <div
        class="inline-flex ring-1 ring-sky-500/50 flex-col gap-1 max-w-lg border-l-4 border-blue-400 bg-blue-50 p-4 rounded text-blue-700 mx-auto font-medium shadow"
      >
        <p>
          The file list and a file comparison of the changed files is only displayed if a Laravel
          Nova license is validated.
        </p>
        <p>
          <a href="https://nova.laravel.com/terms" target="_blank">Laravel Nova Terms of Service</a>
        </p>
        <p>
          <button type="button" class="btn px-2" @click="showLicenceValidation = true">
            Validate Nova Licence
          </button>
        </p>
      </div>
    </section>
    <Dialog
      :show="showLicenceValidation"
      title="Validate Nova Licence"
      :with-footer="false"
      @close="showLicenceValidation = false"
    >
      Content Here
    </Dialog>
  </TransitionRoot>
</template>

<script>
/**
 * @property {Array|Number} comparison.created
 * @property {Array|Number} comparison.deleted
 * @property {Array|Number} comparison.updated
 * */
export default {
  props: {
    comparison: {
      type: Object,
      required: false,
      default: null
    }
  },
  data() {
    return {
      showLicenceValidation: false,
      showComparison: false
    }
  },
  methods: {
    setShowComparison(stat) {
      this.showComparison = stat
    }
  }
}
</script>
