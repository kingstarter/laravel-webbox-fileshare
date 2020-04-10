<template>
  <div>
    <div class="absolute top-0 pt-3 text-gray-500 cursor-default">
      <span>Ablauf der Session: </span>
      <countdown :global=true :time=countdownTime :duration=countdownTtl></countdown>
    </div>

    <vue-dropzone ref="vue-dropzone" id="dropzone"
      :options=dropzoneOptions
      @vdropzone-sending="beforeSending"
      @vdropzone-success="afterSending">
    </vue-dropzone>
  </div>
</template>

<script>
import vue2Dropzone from 'vue2-dropzone'

export default {
  name: 'uploader',

  components: {
    vueDropzone: vue2Dropzone
  },

  props: {
    sessionname: String,
    sessiontime: Number,
    sessionttl: Number
  },

  data () {
    return {
      dropzoneOptions: {
        url: '/store',
        thumbnailWidth: 150,
        maxFilesize: 2,
        headers: {},
        method: 'post'
      },
      csrf: null,
      /** newer session time after storing files */
      dzSession: 0
    }
  },

  computed: {
    countdownTime () {
      return this.dzSession || this.sessiontime
    },
    countdownTtl () {
      return this.sessionttl
    }
  },

  methods: {
    beforeSending (file, xhr, formData) {
      formData.append(
        '_token', document.querySelector('meta[name="csrf"]').getAttribute('content')
      )
    },
    afterSending (file, response) {
      let data = Object.assign({ time: 0 }, response)
      this.dzSession = data.time
    }
  }
}
</script>
