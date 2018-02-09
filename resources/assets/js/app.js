
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
window.Promise = require('promise');
// Element UI http://element.eleme.io/#/en-US
import ElementUI from 'element-ui';
import locale from 'element-ui/lib/locale/lang/ja';
Vue.use(ElementUI, {locale});
// Draggable Dialog
import DlgDraggable from "vue-element-dialog-draggable";
Vue.use(DlgDraggable);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
Vue.component('side-menu', require('./components/SideMenu.vue'));
Vue.component('calender', require('./components/Calender.vue'));
Vue.component('schedule', require('./components/Schedule.vue'));
Vue.component('reserve-dialog', require('./components/ReserveDialog.vue'));
Vue.component('list-dialog', require('./components/ListDialog.vue'));
Vue.component('app-main', require('./components/Main.vue'));

const app = new Vue({
  el: '#app-container',
  render: h => h(require('./components/App.vue'))
});
