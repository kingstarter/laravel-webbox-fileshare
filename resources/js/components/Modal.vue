<template>
  <!--Modal-->
  <transition name="modal-fade">
    <div class="modal fixed flex z-20 top-0 left-0 w-full h-full overflow-auto backdrop justify-around pt-6 sm:pt-12 md:pt-32"
      v-if="show" @click.prevent="handleClick">

      <div class="modal-container bg-white w-full sm:w-4/5 md:w-3/5 max-w-md rounded" :class="modalHeight" @click.prevent="handleClick">

        <!-- Add margin if you want to see some of the overlay behind the modal-->
        <div class="modal-content py-4 text-left px-6">
          <!--Title-->
          <div class="flex justify-between items-center pb-3">
            <p class="text-2xl font-bold" v-text="title"></p>
            <!-- Close button -->
            <div class="modal-close cursor-pointer z-50" @click.prevent="emitModalClose">
              <svg class="fill-current text-black" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18">
                <path d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z"></path>
              </svg>
            </div>
          </div>

          <!--Body-->
          <div>
            <div class="w-full p-3 border rounded shadow bg-gray-100 truncate mb-4 sm:mb-3" v-text="url + 'a'"></div>

            <!-- Mail input -->
            <div class="flex my-4" v-if="withmail">
              <input type="email" class="form-control text-left rounded-r-none" v-model="email" @keyup.enter="sendEmail" :placeholder="$trans('email.placeholder')" />
              <button class="btn btn-teal rounded-l-none" :title="$trans('link.email')" @click="sendEmail()" :class="btnClsDisabled" :disabled="!emailValid">
                <span class="fa fa-envelope"></span>
              </button>
            </div>
          </div>

          <!-- footer -->
          <div class="w-full flex flex-col sm:flex-row justify-around items-center h-24 sm:h-20 pt-8 sm:pt-0">
            <button class="btn btn-teal text-center w-56 sm:w-40 my-2 sm:my-0 sm:mr-2" @click="copyToClipboard">
              <span class="fa fa-copy"></span>
              <span v-trans="'link.copy'"></span>
            </button>
            <button class="btn btn-teal text-center w-56 sm:w-40 my-2 sm:my-0 sm:ml-2" @click="openPath">
              <span class="fa fa-external-link-alt"></span>
              <span v-trans="'link.open'"></span>
            </button>
          </div>

        </div>
      </div>
    </div>
  </transition>

</template>

<script>
export default {
  props: {
    show: Boolean,
    title: {
      type: String,
      default: 'Modal title'
    },
    url: String,
    withmail: {
      type: Boolean,
      default: false
    }
  },

  data () {
    return {
      email: '',
      sending: false
    }
  },

  computed: {
    fullPath () {
      let basepath = window.location.href
      return basepath.endsWith('/') ? basepath + this.url : basepath + '/' + this.url
    },
    modalHeight () {
      return this.withmail ? 'h-84 sm:h-72' : 'h-72 sm:h-56'
    },
    emailValid () {
      return /\S+@\S+\.\S+/.test(this.email)
    },
    btnClsDisabled () {
      return !this.emailValid || this.sending ? 'opacity-50 cursor-not-allowed' : ''
    }
  },

  methods: {
    handleClick (event) {
      if (event.target.classList.contains('backdrop'))
        this.emitModalClose()
    },
    emitModalClose () {
      this.$emit('close')
    },
    openPath () {
      window.open(this.fullPath)
    },
    async copyToClipboard () {
      await navigator.clipboard.writeText(this.fullPath);
      this.$toastr.s(this.$trans('link.copied'));
    },
    sendEmail () {
      if (this.emailValid || this.sending) {
        this.sending = true
        this.$http.post('/sendmail', { 'email': this.email }).then(reponse => {
          this.$toastr.s(this.$trans('email.send.succ'))
        }).catch(err => {
          this.$toastr.e(this.$trans('email.send.fail'))
        }).then(() => {
          this.sending = false
        })
      }
    }
  }
}
</script>

<style scoped>
  .modal-fade-enter,
  .modal-fade-leave-active {
    opacity: 0;
  }

  .modal-fade-enter-active,
  .modal-fade-leave-active {
    transition: opacity .5s ease
  }
</style>
