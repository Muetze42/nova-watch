<template>
  <section class="flex flex-col gap-2">
    <p class="text-center">
      Select 2 different
      <a href="https://nova.laravel.com/" target="_blank">Laravel Nova</a> versions to compare.
    </p>
    <div class="flex justify-center">
      <div class="inline-flex flex-col gap-2">
        <div class="inline-flex justify-center gap-2">
          <select
            v-for="(key, index) in selected"
            :key="index"
            v-model="selected[index]"
            class="form-select"
            :class="'v' + key"
            @change="updated"
          >
            <option v-for="version in $page.props.versions" :key="version" :value="version">
              {{ version }}
            </option>
          </select>
        </div>
        <div>
          <button
            class="btn w-full"
            :disabled="
              selected[0] === selected[1] ||
              (current.includes(selected[0]) && current.includes(selected[1]))
            "
            @click="compare"
          >
            <font-awesome-icon :icon="['fas', 'right-left']" fixed-width />
            Compare
          </button>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import { reactive } from 'vue'
import { router } from '@inertiajs/vue3'

export default {
  name: 'CompareSection',
  emits: ['show'],
  data() {
    return {
      current: [this.$page.props.selected[0], this.$page.props.selected[1]],
      selected: reactive({
        0: this.$page.props.selected[0],
        1: this.$page.props.selected[1]
      })
    }
  },
  mounted() {
    this.updated()
  },
  methods: {
    compare() {
      router.get('/' + this.selected[0] + '/' + this.selected[1])
    },
    updated() {
      this.$emit(
        'show',
        this.selected[0] !== this.selected[1] &&
          this.current.includes(this.selected[0]) &&
          this.current.includes(this.selected[1])
      )
    }
  }
}
</script>
