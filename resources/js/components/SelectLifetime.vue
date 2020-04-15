<template>
  <select
    v-bind="$attrs"
    v-bind:value="value"
    @change.prevent="onchange"
  >
    <option v-for="item in optionList" :key="item.value" :value="item.value" v-text="item.label"></option>
  </select>
</template>

<script>
export default {
  inheritAttrs: false,

  model: {
    prop: 'value',
    event: 'change'
  },

  props: ['value'],

  data () {
    return {
      // Default storage lifetime array
      values: [
        '1 day',
        '2 days',
        '3 days',
        '1 week',
        '2 weeks',
        '1 month',
        '2 months',
        '3 months',
        '1 year',
      ]
    }
  },

  mounted () {
    // Retrieve config array
    if (window.config && Array.isArray(window.config.storageTimes) && window.config.storageTimes.length)
      this.values = window.config.storageTimes
  },

  computed: {
    optionList () {
      let options = []
      this.values.forEach(item => {
        let parts = item.split(' ')
        let msgcode = (parts.length > 1 ? 'time.' + parts[1] : 'timespan')
        msgcode.endsWith('s') || (msgcode = msgcode+'s')
        options.push({
          value: item,
          label: `${parts[0]} ${this.$choice(msgcode, parts[0])}`
        })
      })
      return options
    }
  },

  methods: {
    // Enable v-model binding
    onchange(event) {
      // When emitted inline the parent model seems to stay unchanged,
      // though moved event emitter to method
      this.$emit('change', event.target.value);
    }
  }
}
</script>
