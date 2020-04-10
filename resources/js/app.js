window.Vue = require('vue')

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
