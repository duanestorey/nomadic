
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
require('./bootstrap');


import * as L from 'leaflet';
import 'leaflet.markercluster';
import 'jquery.typewatch';

L.Icon.Default.imagePath = '/images/vendor/leaflet/dist/';

L.Icon.Default.prototype.options = {
   	iconHtml: '<i class="glyphicon glyphicon-user" style="color: red"></i>',
    iconSize: [20, 70],
    iconAnchor: [10, 70],
    // ...etc, with all the L.Icon desired/needed options.
}

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example-component', require('./components/ExampleComponent.vue'));

const app = new Vue({
    el: '#app' 
});


