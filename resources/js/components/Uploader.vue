<template>
  <div>
    <div class="absolute top-0 pt-3 text-gray-500 cursor-default">
      <span v-trans.colon="'session.ending'"></span>
      <countdown :global=true :time=countdownTime :duration=countdownTtl></countdown>
    </div>

    <vue-dropzone ref="vue-dropzone" id="dropzone"
      :options=dropzoneOptions
      @vdropzone-sending="beforeSending"
      @vdropzone-success="afterSending">
    </vue-dropzone>

    <div class="flex w-full justify-end mt-5">
      <label class="mr-3">
        <span class="mr-1" v-trans.colon="'storage.lifetime'"></span>
        <select-lifetime class="form-control w-32" v-model="storageTimeValue"></select-lifetime>
      </label>
      <button class="btn btn-teal" :class="btnClsDisabled" @click="submitStore"
        :disabled="!dzHasElements"
        v-trans="'store'"></button>
    </div>

    <modal
      :show=showModal
      :url="urlLink"
      :title="$trans('link.generated')"
      :withmail="mailEnabled"
      @close="showModal = false">
    </modal>
  </div>
</template>

<script>
import vue2Dropzone from 'vue2-dropzone'
import Modal from './Modal'
import SelectLifetime from './SelectLifetime'

export default {
  name: 'uploader',

  components: {
    vueDropzone: vue2Dropzone,
    modal: Modal,
    selectLifetime: SelectLifetime
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
        maxFilesize: 256,
        headers: {},
        method: 'post',
        dictDefaultMessage: this.$trans('dz.default.msg')
      },
      csrf: null,
      /** newer session time after storing files */
      dzSession: 0,
      dzHasElements: false,
      showModal: false,
      storageTimeValue: '',
      csrf: null,
      urlLink: '',
      mailEnabled: false
    }
  },

  created () {
    this.csrf = document.querySelector('meta[name="csrf"]').getAttribute('content')
    this.dropzoneOptions.maxFilesize = window.config && window.config.maxFilesize || 256
  },

  mounted () {
    let config = Object.assign({
      defaultStorageTime: '',
      mailEnabled: false
    }, window.config)

    this.storageTimeValue = config.defaultStorageTime
    this.mailEnabled = config.mailEnabled
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
