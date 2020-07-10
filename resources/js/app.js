/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import 'jquery-ui/ui/widgets/autocomplete.js';
import 'jquery-ui/ui/widgets/slider.js';

window.Swal = require('sweetalert2');

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

$(".js-remove-item-from-order").click(function () {
    let data = $(this).data();
    axios.put("/api/orders", data).then(r => {
        window.location.reload();
    });
});

$(document).ready(function () {
    let modal = $("#paymentModal");
    if (modal.length > 0) {
        let radios = modal.find("input[type='radio']");
        radios.map(function () {
            $(this).change(function () {
                modal.find("label").removeClass("active");
                $(this).parent().addClass("active")
            })
        })
    }
});

$("#order-form").submit(function (e) {
    e.preventDefault();
    $(".text-danger").text("");
    var data = {};
    for (var v of $(this).serializeArray()) {
        data[v['name']] = v['value']
    }
    if (data['type'] === "bitcoin") {
        $("#paymentModal").modal("hide");
        let modal = $("#coinModal");
        $('#coinModal').on('shown.bs.modal', function (e) {
            // console.log("shown")
            var input = modal.find("#coinhash");
            //   console.log(input)
            input.val(r.data.data.bitcoin);
            document.querySelector("#coinhash").select();
            document.execCommand("copy");
            input.prop("disabled", true)
        });
        modal.modal("show");

    } else {
        //let order = r.data.data.order;
        //initCP(order);
        axios.post($(this).attr("action"), data).then(r => {
            if (r.data.status === 'success') {
                window.location.href = r.data.data.url;
                //window.open(r.data.data.url, '_blank');
            }
        }).catch(err => {
            let errors = err.response.data.errors;
            for (var key in errors) {
                var msg = errors[key][0];
                $("[data-key='" + key + "']").text(msg);
            }
            $("#paymentModal").modal("hide")
        });
    }

    return false;
});

function initCP(order) {
    var widget = new cp.CloudPayments({language: "en-US"});
    widget.charge({ // options
            publicId: 'pk_9b1b8ca37fa37329548c6541f127f',  //id из личного кабинета
            description: "Order #" + order.id, //назначение
            // amount: 10, //сумма
            amount: order.amount, //сумма
            currency: 'RUB', //валюта
            invoiceId: order.id, //номер заказа  (необязательно)
            accountId: order.user.email, //идентификатор плательщика (необязательно)
            skin: "mini", //дизайн виджета
            data: {
                myProp: 'myProp value' //произвольный набор параметров
            }
        },
        function (options) { // success
            axios.post("/api/orders/" + options.invoiceId + "/payed", {email: options.accountId}).then(r => {
                if (r.data.status === 'success') {
                    window.location.href = "/order/success";
                }
            });
            console.log(options);
            //действие при успешной оплате
        },
        function (reason, options) { // fail
            //действие при неуспешной оплате
        });
}

$(".js-logout").click(function (e) {
    e.preventDefault();
    return Swal.fire({
        title: 'Are you sure?',
        text: "You are trying to logout. Continue?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, forget me'
    }).then((result) => {
        if (result.value) {
            $("#logout-form").submit();
        } else {
            return false;
        }
    })
});

$(".js-copy").click(function () {
    let self = this;
    let key = $(this).data("target");
    var copyText = $("[data-key='" + key + "']").text();
    console.log(copyText);
    navigator.clipboard.writeText(copyText).then(function () {
        $(self).text("Copied to clickboard!");
    }, function (err) {
        console.error('Async: Could not copy text: ', err);
    });
});

function copy(target) {
    /* Get the text field */
    var copyText = document.getElementById(target + "-copy");
    copyText.value = $("[data-key=" + target + "]").text();

    /* Select the text field */
    copyText.select();
    copyText.setSelectionRange(0, 99999); /*For mobile devices*/

    /* Copy the text inside the text field */
    document.execCommand("copy");
}

$(".js-search").click(function () {

    Swal.fire({
        title: 'Search:',
        input: 'text',
        // inputValue: inputValue,
        showCancelButton: true,
        inputValidator: (value) => {
            if (!value) {
                return 'You need to write something!'
            } else {
                window.location.href = "/search?q=" + value;
            }
        }
    })
});
