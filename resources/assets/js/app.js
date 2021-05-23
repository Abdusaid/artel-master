
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
window.VueRouter = require('vue-router').default;
var VueCookie = require('vue-cookie');
Vue.use(VueRouter);
Vue.use(VueCookie);
window.NProgress = require('nprogress');
NProgress.configure({ easing: 'ease'});
window.Materialize = require('materialize-css');


window.Login = require('./components/Login.vue');
window.Warehouseman = require('./components/warehouseman/Main.vue');
window.Warehousemanager = require('./components/warehousemanager/Main.vue');
window.Manager = require('./components/warehousemanager/Main.vue');
window.Laboratorian = require('./components/laboratorian/Main.vue');
window.Requestor = require('./components/requestor/Main.vue');
window.Notice = require('./components/laboratorian/Notice.vue');
window.LabImport = require('./components/laboratorian/Import.vue');
window.Remainder = require('./components/warehousemanager/Remainder.vue');
window.Import = require('./components/warehousemanager/Import.vue');
window.Export = require('./components/warehousemanager/Export.vue');
window.RemainderStore = require('./components/warehouseman/Remainder.vue');
window.ImportStore = require('./components/warehouseman/Import.vue');
window.ExportStore = require('./components/warehouseman/Export.vue');
window.Reload = require('./components/warehouseman/Reload.vue');
window.Loading = require('./components/warehouseman/Loading.vue');
window.ExportCard = require('./components/warehouseman/ExportCard.vue');
window.History = require('./components/warehouseman/History.vue');
window.Admin = require('./components/admin/Admin.vue');
window.AdminRaw = require('./components/admin/Raw.vue');

window.Rawman = require('./components/rawman/Main.vue');
window.MixtureRemainder = require('./components/rawman/Remainder.vue');

window.firms = require('./components/mixins.js').firms;
//window.Statistics = require('./components/director/Statistics.vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */




//Vue.component('example-component', require('./components/ExampleComponent.vue'));
