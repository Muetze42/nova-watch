<template>
  <section class="p-0 max-w-xl w-full mx-auto">
    <form @submit.prevent="submit">
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
        <!-- Todo: Fields -->
        <div class="p-2">
          Scopes
          <textarea v-model="resource.scopes" class="form-textarea w-full" />
          Keep empty to get always a notification. Examples:
          <code class="text-xs w-full block">
            resources/css/nova.css<br>
            resources/css/*
          </code>
        </div>
      </div>
      <div class="border-t border-achromatic-600/20 px-2 py-1 font-medium text-center">
        <button type="submit" class="btn">Save</button>
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
      resource: reactive({
        scopes: this.scopes,
        active: this.active,
        config: this.config ? this.config : {}
      })
    }
  },
  methods: {
    submit() {}
  }
}
</script>
