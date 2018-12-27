
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

//import pagination from './components/LaravelVuePagination.vue';
Vue.component('example-component', require('./components/ExampleComponent.vue'));

Vue.component('pagination', require('laravel-vue-pagination'));


import datePicker from 'vue-bootstrap-datetimepicker';
import 'pc-bootstrap4-datetimepicker/build/css/bootstrap-datetimepicker.css';

 import VueBootstrapTypeahead from 'vue-bootstrap-typeahead';

 Vue.component('vue-bootstrap-typeahead', VueBootstrapTypeahead)


Vue.use(datePicker);




