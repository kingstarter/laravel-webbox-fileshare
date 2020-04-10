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
      /** newer ttl after storing files */
      dropzonettl: 0
    }
  },

  computed: {
    countdownTime () {
      return this.sessiontime
    },
    countdownTtl () {
      return this.dropzonettl || this.sessionttl
    }
  },

  methods: {
    beforeSending (file, xhr, formData) {
      formData.append(
        '_token', document.querySelector('meta[name="csrf"]').getAttribute('content')
      )
    },
    afterSending (file, response) {
      console.log(file)
      console.log(response)
    }
  }
}
</script>
