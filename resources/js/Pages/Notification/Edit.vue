<script setup>
import { TransitionRoot } from '@headlessui/vue'
</script>
<template>
  <section class="p-0 max-w-xl w-full mx-auto">
    <form @submit.prevent="submit" @input="saved = false">
      <div class="border-b border-achromatic-600/20 px-2 py-1 font-medium">
        <a href="/notifications">Notifications</a> &raquo; {{ label }}
      </div>
      <div
        class="flex flex-col gap-2 divide-y divide-achromatic-400/30 dark:divide-achromatic-700/30"
      >
        <div class="p-2">
          <label class="checkbox">
            <input v-model="resource.active" type="checkbox" class="form-checkbox" value="1" />
            Active
          </label>
        </div>
        <div v-for="field in fields" :key="field.column" class="p-2">
          {{ field.label }}
          <input
            v-model="resource.config[field.column]"
            :type="field.type"
            class="form-input w-full"
            :required="field.required"
          />
        </div>
        <div class="p-2">
          Scopes
          <textarea v-model="resource.scopes" class="form-textarea w-full" />
          Keep empty to get always a notification.<br />
          Or specify files for which notifications are to be sent. One entry per line. Wildcard
          Wildcard (<code class="font-light">*</code>) possible. Example:
          <code class="text-xs font-light w-full block">
            resources/css/nova.css<br />
            resources/css/*
          </code>
        </div>
      </div>
      <TransitionRoot
        :show="saved"
        enter="transition-opacity duration-75"
        enter-from="opacity-0"
        enter-to="opacity-100"
        leave="transition-opacity duration-150"
        leave-from="opacity-100"
        leave-to="opacity-0"
        as="div"
        class="success"
      >
        Successfully saved
      </TransitionRoot>
      <div v-if="formError" class="p-2">
        <div class="danger">
          {{ formError }}
        </div>
      </div>
      <div class="border-t border-achromatic-600/20 px-2 py-1 font-medium text-center">
        <button type="submit" class="btn px-2.5" :disabled="processing">
          <Spinner v-if="processing" />
          <font-awesome-icon v-else :icon="['fas', 'floppy-disk']" fixed-width />
          Save
        </button>
      </div>
    </form>
  </section>
</template>
<script>
import { reactive } from 'vue'

export default {
  props: {
    config: {
      type: Object,
      required: false
    },
    fields: {
      type: Object,
      required: true
    },
    label: {
      type: String,
      required: true
    },
    slug: {
      type: String,
      required: true
    },
    scopes: {
      type: String,
      required: false
    },
    active: {
      type: Boolean,
      required: true
    }
  },
  data() {
    return {
      formError: null,
      processing: false,
      saved: false,
      resource: reactive({
        scopes: this.scopes,
        active: this.active,
        config: this.config
      })
    }
  },
  methods: {
    submit() {
      this.processing = true
      this.saved = false
      let ref = this
      axios
        .post('/notifications/' + this.slug, {
          resource: this.resource
        })
        .then(function (response) {
          if (response && response.status === 200) {
            ref.processing = false
            ref.saved = true
          }
        })
        .catch(function (error) {
          error.response.status === 422
            ? (ref.formError = error.response.data.message)
            : ref.errorHandler(error)
          ref.processing = false
        })
    }
  }
}
</script>
