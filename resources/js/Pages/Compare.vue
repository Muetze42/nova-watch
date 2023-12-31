<script setup>
import { TransitionRoot, Disclosure, DisclosureButton, DisclosurePanel } from '@headlessui/vue'
import Spinner from '@/Components/Spinner.vue'
</script>
<template>
  <CompareSection @show="setShowComparison" />
  <TransitionRoot
    :show="showComparison && comparison.files !== null"
    enter="transition-opacity duration-250"
    enter-from="opacity-0"
    enter-to="opacity-100"
    leave="transition-opacity duration-250"
    leave-from="opacity-100"
    leave-to="opacity-0"
    class="flex flex-col gap-2"
  >
    <section class="flex justify-center gap-2 items-center">
      <div>Released {{ comparison.published_at[$page.props.selected[0]] }}</div>
      <div>
        <button class="btn py-0.5" :disabled="processing" @click="openNotes">
          <font-awesome-icon v-if="!processing" :icon="['fas', 'file-lines']" fixed-width />
          <Spinner v-else />
          Notes
        </button>
      </div>
      <div>Released {{ comparison.published_at[$page.props.selected[1]] }}</div>
    </section>
    <section class="flex flex-col gap-1 compare-index">
      <template v-for="(files, action) in comparison.files" :key="action">
        <Disclosure v-if="files.length" v-slot="{ open }">
          <DisclosureButton
            class="px-1 select-none py-0.5 rounded cursor-pointer ring-1 ring-transparent hover:ring-accent-500 brightness-75 hover:brightness-100"
            as="div"
            :class="[action, { 'brightness-100': open }]"
          >
            <font-awesome-icon
              v-if="files.length"
              :icon="['fas', 'caret-right']"
              :class="{ 'rotate-90': open }"
            />
            {{ files.length }}
            {{ transChoice(files.length) }}
            {{ action }}
          </DisclosureButton>
          <transition
            enter-active-class="transition duration-100 ease-out"
            enter-from-class="transform scale-95 opacity-0"
            enter-to-class="transform scale-100 opacity-100"
            leave-active-class="transition duration-75 ease-out"
            leave-from-class="transform scale-100 opacity-100"
            leave-to-class="transform scale-95 opacity-0"
          >
            <DisclosurePanel>
              <ul
                class="section p-0 divide-y divide-achromatic-400/30 dark:divide-achromatic-700/30"
              >
                <li
                  v-for="file in files"
                  :key="file"
                  class="px-2 p-1 font-mono text-sm flex items-center gap-1"
                >
                  <button
                    type="button"
                    title="Compare file"
                    class="btn text-sm"
                    @click="licensed ? compare(file) : (showLicenceValidation = true)"
                  >
                    <font-awesome-icon
                      v-if="!processing"
                      :icon="['fas', 'right-left']"
                      fixed-width
                    />
                    <Spinner v-else />
                  </button>
                  {{ file }}
                </li>
              </ul>
            </DisclosurePanel>
          </transition>
        </Disclosure>
        <div v-else :class="action" class="px-1 py-0.5 rounded opacity-50">
          {{ files.length }}
          {{ transChoice(files.length) }}
          {{ action }}
        </div>
      </template>
    </section>
  </TransitionRoot>
  <Dialog
    :show="showLicenceValidation"
    title="Validate Nova Licence"
    :with-footer="false"
    @close="closeValidateLicence"
  >
    <form class="flex flex-col divide-y divide-achromatic-600/20" @submit.prevent="submit">
      <div class="p-2 text-center notes">
        In order to see code changes, a licence must be validated by
        <a href="https://nova.laravel.com/licenses" target="_blank">Nova</a> in order not to violate
        the <a href="https://nova.laravel.com/terms" target="_blank">Terms of Service</a>.
      </div>
      <div class="p-2 text-center">Each verification is valid for a maximum of 24 hours.</div>
      <label class="p-2 pb-3.5">
        Nova License Url:
        <input
          v-model="form.url"
          class="form-input w-full"
          type="text"
          placeholder="Nova License Url"
        />
      </label>
      <label class="p-2 pb-3.5">
        Nova LicenseKey:
        <input
          v-model="form.key"
          class="form-input w-full"
          type="password"
          placeholder="Nova LicenseKey"
        />
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
  <Dialog
    :show="showFileCompare"
    :title="fileCompare"
    size="max-w-4xl"
    @close="showFileCompare = false"
  >
    <div class="dialog-content scrollbar-thin text-sm">
      <pre class="language-diff"><code v-html="highlightedFileCompareData" /></pre>
    </div>
  </Dialog>
  <Dialog :show="showNotes" title="Notes" @close="showNotes = false">
    <div class="flex flex-col gap-1 p-1 dialog-content scrollbar-thin">
      <div
        v-for="(data, version) in notes"
        :key="version"
        class="border border-primary-200 dark:border-primary-700/50 rounded p-1"
      >
        <div class="flex justify-between">
          <div class="font-medium">Nova v{{ version }}</div>
          <div class="text-sm font-light dark:text-primary-200/50">{{ data.published_at }}</div>
        </div>
        <div class="dark:text-primary-200/70 font-light notes" v-html="data.notes" />
      </div>
    </div>
  </Dialog>
  <TransitionRoot
    :show="showValidatedNotify"
    enter="transform transition duration-[400ms]"
    enter-from="opacity-0 rotate-[-120deg] scale-50"
    enter-to="opacity-100 rotate-0 scale-100"
    leave="transform duration-200 transition ease-in-out"
    leave-from="opacity-100 rotate-0 scale-100 "
    leave-to="opacity-0 scale-95 "
    class="absolute top-1 z-50 bg-emerald-400 text-black text-xl rounded px-2 py-1"
  >
    License validated
  </TransitionRoot>
</template>

<script>
import { router } from '@inertiajs/vue3'
import Prism from 'prismjs'
import 'prismjs/components/prism-diff.js'
import 'prismjs/themes/prism.css'

/**
 * @property {Array|Number} comparison.files.created
 * @property {Array|Number} comparison.files.deleted
 * @property {Array|Number} comparison.files.updated
 * @property {Array} comparison.published_at
 * @property {String} fileCompareData
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
      notes: null,
      showNotes: false,
      form: {
        url: null,
        key: null,
        save: false
      },
      formError: null,
      showFileCompare: false,
      fileCompare: null,
      fileCompareData: null,
      showValidatedNotify: false
    }
  },
  computed: {
    highlightedFileCompareData() {
      return Prism.highlight(this.fileCompareData, Prism.languages.diff, 'language-diff')
    }
  },
  methods: {
    openNotes() {
      this.processing = true
      if (!this.notes) {
        let ref = this
        axios
          .post('/notes', {
            v1: ref.$page.props.selected[0],
            v2: ref.$page.props.selected[1]
          })
          .then(function (response) {
            ref.notes = response.data
            ref.showNotes = true
            ref.processing = false
          })
          .catch(function (error) {
            ref.errorHandler(error)
            ref.processing = false
          })
      } else {
        this.showNotes = true
        this.processing = false
      }
    },
    compare(file) {
      this.processing = true
      if (!this.fileCompare || !this.fileCompare !== file) {
        this.fileCompare = file
        let ref = this
        axios
          .post('/diff', {
            file: file,
            v1: ref.$page.props.selected[0],
            v2: ref.$page.props.selected[1]
          })
          .then(function (response) {
            ref.fileCompareData = response.data
            ref.showFileCompare = true
            ref.processing = false
          })
          .catch(function (error) {
            error.response.status === 422
              ? (ref.formError = error.response.data.message)
              : ref.errorHandler(error)
            ref.processing = false
          })
      }
    },
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
            ref.showValidatedNotify = true
            setTimeout(() => {
              ref.showValidatedNotify = false
            }, 1500)
          }
        })
        .catch(function (error) {
          ref.form.key = null
          error.response.status === 422
            ? (ref.formError = error.response.data.message)
            : ref.errorHandler(error)
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
