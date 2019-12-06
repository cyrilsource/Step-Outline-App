require('./bootstrap');

window.Vue = require('vue')

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// eslint-disable-next-line no-undef
Vue.component('tasks', require('./components/Tasks.vue').default)
Vue.component('editable', require('./components/ContentEditable.vue').default)
// eslint-disable-next-line no-new
new Vue({
  el: '#app',
  data: {
    search: ''
  }

})
