window.Vue = require('vue')

import axios from 'axios'
import VueAxios from 'vue-axios'
import Lang from './lang.plugin'
import Toastr from 'vue-toastr'

Vue.use(VueAxios, axios)
Vue.use(Lang)
Vue.use(Toastr, {
  defaultTimeout: 2000,
  defaultPosition: 'toast-bottom-right',
  defaultProgressBar: false
})

require('./polyfills')


// Define global countdown timeout function as session timeout
window.Vue.prototype.countdownTimeout = function (handler) {
  window.location.href = '/logout'
};

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('uploader', require('./components/Uploader.vue').default)
Vue.component('countdown', require('./components/Countdown.vue').default)

const app = new Vue({
    el: '#app'
})
