<template>
  <div>
    <div class="absolute top-0 pt-3 text-gray-500 cursor-default">
      <span v-trans.colon="'session.ending'">Ablauf der Session: </span>
      <countdown :global=true :time=countdownTime :duration=countdownTtl></countdown>
    </div>

    <vue-dropzone ref="vue-dropzone" id="dropzone"
      :options=dropzoneOptions
      @vdropzone-sending="beforeSending"
      @vdropzone-success="afterSending">
    </vue-dropzone>

    <div class="flex w-full justify-end mt-5">
      <label class="mr-3">
        <span class="mr-1" v-trans.colon="'storage.lifetime'">Speicherdauer:</span>
        <select class="form-control w-32" v-model="storageTimeValue">
          <option v-for="item in storageTimeItems" :key="item.value" :value="item.value" v-text="item.label"></option>
        </select>
      </label>
      <button class="btn btn-teal" :class="btnClsDisabled" @click="submitStore"
        :disabled="!dzHasElements"
        v-trans="'store'">Alles speichern</button>
    </div>

    <modal :show=showModal :url="urlLink" :title="$trans('link.generated')"
      @close="showModal = false">
    </modal>
  </div>
</template>

<script>
import vue2Dropzone from 'vue2-dropzone'
import Modal from './Modal'

export default {
  name: 'uploader',

  components: {
    vueDropzone: vue2Dropzone,
    modal: Modal
  },

  props: {
    sessiontime: Number,
    sessionttl: Number
  },

  data () {
    return {
      dropzoneOptions: {
        url: '/upload',
        thumbnailWidth: 150,
        maxFilesize: 2,
        headers: {},
        method: 'post',
        dictDefaultMessage: this.$trans('dz.default.msg')
      },
      csrf: null,
      /** newer session time after storing files */
      dzSession: 0,
      dzHasElements: false,
      showModal: false,
      storageTimeItems: [
        { value: '1 day',    label: '1 day' },
        { value: '2 days',   label: '2 days' },
        { value: '3 days',   label: '3 days' },
        { value: '1 week',   label: '1 week' },
        { value: '2 weeks',  label: '2 weeks' },
        { value: '1 month',  label: '1 month' },
        { value: '2 months', label: '2 months' },
        { value: '3 months', label: '3 months' },
        { value: '1 year',   label: '1 year' },
      ],
      storageTimeValue: '',
      csrf: null,
      urlLink: ''
    }
  },

  created () {
    this.csrf = document.querySelector('meta[name="csrf"]').getAttribute('content')
    this.dropzoneOptions.maxFilesize = window.config && window.config.maxFilesize || 256
  },

  mounted () {
    let storageTimes = window.config && window.config.storageTimes ? window.config.storageTimes : null
    if (typeof storageTimes === 'object' && storageTimes.length)
      this.storageTimeItems = storageTimes

    this.storageTimeValue = window.config && window.config.defaultStorageTime ?
      window.config.defaultStorageTime : this.storageTimeItems[0].value
  },

  computed: {
    countdownTime () {
      return this.dzSession || this.sessiontime
    },
    countdownTtl () {
      return this.sessionttl
    },
    btnClsDisabled () {
      return this.dzHasElements ? '' : 'opacity-50 cursor-not-allowed'
    }
  },

  methods: {
    beforeSending (file, xhr, formData) {
      formData.append(
        '_token', this.csrf
      )
    },
    afterSending (file, response) {
      let data = Object.assign({ time: 0 }, response)
      this.dzSession = data.time
      this.dzHasElements = true
    },
    submitStore () {
      this.$http.post('/store', { 'time': this.storageTimeValue }).then(response => {
        let data = Object.assign({ time: 0, url: '' }, response.data)
        this.dzSession = data.time
        this.urlLink = data.url
        this.showModal = true
      })
    }
  }
}
</script>
