<script setup>
import { TransitionRoot } from '@headlessui/vue'
import Spinner from '@/Components/Spinner.vue'
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
    <section v-else class="justify-center text-center flex flex-col gap-4">
      <div>
        <ul class="inline-flex flex-col">
          <li
            v-for="(count, action) in comparison"
            :key="action"
            :class="action"
            class="py-0.5 px-1 flex items-center justify-between gap-2"
          >
            <span class="pr-0.5">
              <CompareIcon :action="action" />
            </span>
            <span class="pl-0.5">
              {{ count }}
              {{ transChoice(count) }}
              {{ action }}
            </span>
          </li>
        </ul>
      </div>
      <div
        class="info"
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
      @close="closeValidateLicence"
    >
      <form class="flex flex-col divide-y divide-achromatic-600/20" @submit.prevent="submit">
        <div class="p-2 text-center">Each verification is valid for a maximum of 24 hours.</div>
        <label class="p-2 pb-3.5">
          url:
          <input v-model="form.url" class="form-input w-full" type="text" placeholder="url" />
        </label>
        <label class="p-2 pb-3.5">
          Key:
          <input v-model="form.key" class="form-input w-full" type="password" placeholder="Key" />
        </label>
        <div v-if="user" class="p-2 pb-3.5 text-center">
          <label class="checkbox">
            <input v-model="form.save" class="form-checkbox" type="checkbox" />
            <span>Save this data and verify the licence each login</span>
          </label>
        </div>
        <div v-if="formError" class="p-2 pb-3.5 text-center">
          <div class="danger">
            {{ formError }}
          </div>
        </div>
        <div class="p-2 text-center">
          <button type="submit" class="btn" :disabled="!form.url || !form.key || processing">
            <Spinner v-if="processing" />
            <font-awesome-icon v-else :icon="['fas', 'check']" fixed-width />
            Validate
          </button>
        </div>
      </form>
    </Dialog>
  </TransitionRoot>
</template>

<script>
import { router } from '@inertiajs/vue3'

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
      processing: false,
      showLicenceValidation: false,
      showComparison: false,
      form: {
        url: null,
        key: null,
        save: false
      },
      formError: null
    }
  },
  methods: {
    submit() {
      let ref = this
      this.processing = true
      axios
        .post('/validate', this.form)
        .then(function (response) {
          if (response && response.status === 200) {
            ref.formError = null
            ref.showLicenceValidation = false
            ref.form = {
              url: null,
              key: null,
              save: false
            }
            ref.processing = false
            router.reload()
          }
        })
        .catch(function (error) {
          ref.form.key = null
          error.response.status === 422
            ? (ref.formError = error.response.data.message)
            : this.errorHandler(error)
          ref.processing = false
        })
    },
    closeValidateLicence() {
      this.form.key = null
      this.showLicenceValidation = false
    },
    setShowComparison(stat) {
      this.showComparison = stat
    }
  }
}
</script>
