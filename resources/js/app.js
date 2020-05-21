/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */
import AvatarCropper from "vue-avatar-cropper"

const files = require.context('./', true, /\.vue$/i)
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('image-cropper', AvatarCropper);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    methods: {
        reload() {
            window.location.reload();
        }
    }
});


$(".js-menu-toggle").click(function () {
    var menu = $(".fixed-list");
    if (menu.css("display") === 'none') {
        menu.show();
    } else {
        menu.hide();
    }
});

/*$(".js-toggle-category").click(function () {
    $(".js-category-list").toggleClass('d-none');
});
$(".js-sort-by").change(function () {
    $(this).parents("form").submit();
});*/

$(".js-remove-item-from-order").click(function () {
    let data = $(this).data();
    console.log(data);
    axios.put("/api/orders", data).then(r => {
        window.location.reload();
    });
});
