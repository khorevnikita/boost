<template>
    <div class="row mt-3">
        <!--<div class="col-12 col-md-8">
            <div v-if="calculator">
                <manual-calculator v-on:change="calcChanged" :calculator="calculator" v-if="calculator.steps && calculator.steps.length>0"></manual-calculator>
                <auto-calculator v-on:change="calcChanged" :calculator="calculator" v-else></auto-calculator>
            </div>
            <div style="word-break: break-word;" v-if="ww>=768" class=" pb-5 mt-3" v-html="product.short_description"></div>
            <div style="word-break: break-word;" v-if="ww>=768" v-html="product.description"></div>
        </div>-->
        <div class="col-12 col-md-4">
            <product-list-item :short="true" :product="product"/>
            <button class="btn btn-primary b-r-30 btn-block" @click="addToOrder()">
                <span v-if="added">Already in Cart</span>
                <span v-else>
                    Add to Cart {{ common_price }} {{ currency === 'usd' ? "$" : "€" }}
                </span>
            </button>
            <button data-toggle="modal" data-target="#purchase-modal" class="btn btn-outline-secondary text-white btn-block b-r-30">Buy now</button>
            <div class="card card-options" v-if="options.length>0">
                <div class="card-body">
                    <p class="text-center">Add options</p>
                    <div class="form-check mt-3" v-for="option in options">
                        <label class="form-check-label">
                            <span class="checkbox">
                                <img src="/images/icons/checkbox.svg" v-if="selected_options.filter(so=>so.id===option.id)[0]">
                            </span>
                            <input :checked="selected_options.filter(so=>so.id===option.id)[0]"
                                   @click="toggleOption(option)"
                                   type="checkbox"
                                   class="form-check-input">
                            <span>

                            {{ option.title }}
                            <span v-if="parseFloat(option.price) > 0">
                            +  {{ option.price }}
                            <span v-if="option.type=='abs'">
                                {{ currency === 'usd' ? "$" : "€" }}
                            </span>
                            <span v-else>%</span>
                                </span>

                        </span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="card card-options" v-if="calculator">
                <div class="card-body">
                    <manual-calculator v-on:change="calcChanged" :calculator="calculator" :product="product"
                                       v-if="calculator.steps && calculator.steps.length>0"></manual-calculator>
                    <auto-calculator v-on:change="calcChanged" :calculator="calculator" :product="product" v-else></auto-calculator>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-8">
            <div class="d-none d-sm-block">
                <a :href="`/${game.rewrite}`" class="btn btn-outline-secondary float-right b-r-30">
                    Back to deals
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <g id="arrow_forward_ios_24px">
                            <path id="icon/navigation/arrow_forward_ios_24px"
                                  d="M6.16504 20.13L7.93504 21.9L17.835 12L7.93504 2.10001L6.16504 3.87001L14.295 12L6.16504 20.13H6.16504Z" fill="currentColor"
                                  fill-opacity="1"/>
                        </g>
                    </svg>
                </a>
                <h4><span v-if="product.category" class="text-primary">{{ product.category.title }} -</span> {{ product.title }}</h4>

            </div>
            <p style="word-break: break-word;" v-if="ww<768" class="col-12 pb-5 mt-3" v-html="product.short_description"></p>
            <p style="word-break: break-word;" v-if="ww>=768" class="col-12" v-html="product.description"></p>

            <div class="row" v-if="product.requirements">
                <div class="col-12 col-sm-4 requirements">
                    <p class="text-primary">Service Requirements</p>
                </div>
                <div class="col-12 col-sm-8">
                    <div class="requirements" v-html="product.requirements"></div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="purchase-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body" style="padding-bottom: 35px;">
                        <button data-dismiss="modal" aria-label="Close" type="button" class="btn btn-primary double-btn close-btn float-right">
                            <span>x</span>
                        </button>
                        <p class="text-center" id="exampleModalLabel">Buy now</p>
                        <div style="clear:both"></div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <p class="d-flex justify-content-between">
                                    <span>Choose payment method:</span>
                                    <span>total:
                                    <span class="text-primary" style="font-size: 22px">
                                       {{ after_promo() }}
                                       <span v-html="currency==='usd'?'$':'&euro;'"></span>
                                    </span>
                                </span>
                                </p>
                                <div style="clear:both"></div>
                                <div class="row">
                                    <!--<div class="col-6 my-2">
                                        <button @click="operator='payapp'" class="btn btn-block" v-bind:class="{'btn-primary':operator==='payapp','btn-outline-secondary':operator!=='payapp'}" style="padding: 20px;border-radius: 10px;">
                                            <img src="/images/pay/visa_title.png">
                                            <img src="/images/pay/visa_logo.png">
                                        </button>
                                    </div>-->

                                    <div class="col-6 my-2">
                                        <button @click="operator='stripe'" class="btn btn-block"
                                                v-bind:class="{'btn-primary':operator==='stripe','btn-outline-secondary':operator!=='stripe'}"
                                                style="padding: 20px;border-radius: 10px;">
                                            <img src="/images/pay/stripe.png">
                                        </button>
                                    </div>
                                    <div class="col-6 my-2">
                                        <button @click="operator='paypal'" class="btn btn-block"
                                                v-bind:class="{'btn-primary':operator==='paypal','btn-outline-secondary':operator!=='paypal'}"
                                                style="padding: 20px;border-radius: 10px;">
                                            <img src="/images/pay/paypal.png">
                                        </button>
                                    </div>
                                    <!--<div class="col-6 my-2">
                                        <button class="btn btn-outline-secondary btn-block" style="padding: 20px;border-radius: 10px;">
                                            <img src="/images/pay/amazon.png">
                                        </button>
                                    </div>
                                    -->

                                </div>
                                <a @click="show_promo=!show_promo" role="button" class="text-primary">Do you have promocode?</a>
                                <div v-if="show_promo" class="card mt-3" style="border: 1px solid;">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-6 col-sm-6">
                                                <input type="text" class="form-control" v-model="promocode" placeholder="Enter promocode" style="    height: 52px">
                                                <p v-if="promo_error" class="text-danger">{{ promo_error }}</p>
                                                <p v-if="promo_success" class="text-success">{{ promo_success }}</p>
                                            </div>
                                            <div class="col-6 col-sm-6">
                                                <button @click="setPromocode()" type="button" class="btn btn-primary btn-block" style="    height: 52px">Activate</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="card mt-3" style="    border: 1px solid;">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <input type="text" class="form-control" v-model="purchase.email" placeholder="E-mail">
                                                <p v-if="error" class="text-danger">{{ error }}</p>
                                            </div>
                                        </div>
                                        <p class="text-primary">We are going to send details and data for further authorisation to entered email.</p>
                                    </div>
                                </div>
                                <div style="clear:both"></div>
                                <div class="row mt-3">
                                    <div class="col-12 col-sm-6 mt-2">
                                        <button @click="formPurchase()" class="btn btn-primary btn-block" style="    height: 52px">Pay Now</button>
                                    </div>
                                    <div class="col-12 col-sm-6 d-flex mt-2">
                                        <label class="form-check-label" @click="agree = !agree">
                                        <span class="checkbox">
                                            <img src="/images/icons/checkbox.svg" v-if="agree">
                                        </span>
                                            <a href="/agreement" target="_blank">I agree to Term of Use</a>
                                        </label>
                                    </div>
                                </div>
                                <p v-if="agree_error" class="text-danger">{{ agree_error }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import AutoCalculator from "./AutoCalculator";
import ManualCalculator from "./ManualCalculator";
import ProductListItem from "./ProductListItem";

export default {
    name: "ProductOrder",
    props: ['product', 'options', 'calculator', 'currency', 'images', 'rate', 'game'],
    components: {
        ProductListItem,
        ManualCalculator,
        AutoCalculator,
    },
    data() {
        return {
            selected_options: this.product.selected_options ? this.product.selected_options : [],
            added: this.product.in_order,
            slider_price: 0,
            range: null,
            ww: window.innerWidth,
            currencyRate: parseFloat(this.rate),
            purchase: {},
            error: null,
            promo_error: null,
            promo_success: null,
            promocode: null,
            agree: true,
            agree_error: null,
            show_promo: false,
            operator: "stripe"
        }
    },
    computed: {
        common_price: function () {
            var summ = this.product.price;
            summ = summ + this.slider_price;
            let abs_options = this.selected_options.filter(o => o.type === "abs");
            for (var o of abs_options) {
                summ = summ + o.price;
            }

            let p_options = this.selected_options.filter(o => o.type === "percent");
            for (var po of p_options) {
                summ = summ + this.product.price * po.price / 100
            }
            return Math.round(summ * 100) / 100;
        },
    },
    methods: {
        after_promo() {
            //console.log("after promo")
            if (!this.purchase.promocode) {
                return this.common_price;
            }
            let promocode = this.purchase.promocode;
            // console.log(promocode.currency, currency, rate, promocode.value);
            let price = this.common_price;
            if (promocode.currency === "usd") {
                if (currency === "usd") {
                    price = this.common_price - promocode.value;
                } else {
                    price = this.common_price - (promocode.value / rate);
                }
            } else if (promocode.currency === "eur") {
                if (currency === "eur") {
                    price = this.common_price - promocode.value;
                } else {
                    price = this.common_price - (promocode.value * rate);
                }
            } else if (promocode.currency === "%") {
                price = this.common_price * (1 - promocode.value / 100);
            }
            return Math.round(price * 100) / 100;
        },
        calcChanged(e) {
            this.slider_price = e.slider_price;
            this.range = e.range;
        },
        toggleOption(option) {
            let index = this.selected_options.indexOf(option);
            if (index > -1) {
                this.selected_options.splice(index, 1)
            } else {
                this.selected_options.push(option)
            }
        },
        addToOrder() {
            axios.post("/api/orders", {range: this.range, product_id: this.product.id, options: this.selected_options.map(o => o.id), rate: rate, currency: currency}).then(r => {
                if (r.data.status === "success") {
                    if (!this.added) {
                        let currentOrderProductsCount = parseInt($(".price-badge:first").text());
                        $(".price-badge").removeClass("d-none").text(currentOrderProductsCount + 1);
                    }
                    this.added = true;

                }
            })
        },
        formPurchase() {
            this.error = null;
            this.agree_error = null;
            if (!this.agree) {
                this.agree_error = "You should agree with the Terms to continue";
                return;
            }
            axios.post("/purchase", {
                range: this.range,
                product_id: this.product.id,
                options: this.selected_options.map(o => o.id),
                rate: rate,
                currency: currency,
                email: this.purchase.email,
                promocode: this.purchase.promocode ? this.purchase.promocode.code : null,
                operator: this.operator,
            }).then(r => {
                if (r.data.status === "error") {
                    this.error = r.data.msg;
                } else if(r.data.status === "success") {
                    if (r.data.response && r.data.response.processingUrl) {
                        window.location.href = r.data.response.processingUrl;
                    } else if (r.data.sessionId && r.data.key) {
                        var stripe = Stripe(r.data.key);
                        stripe.redirectToCheckout({sessionId: r.data.sessionId});
                    } else if (r.data.response && r.data.response.links) {
                        window.location.href = r.data.response.links[0].href;
                    }
                }
            }).catch(err => {
                console.warn(err);
                this.error = Object.values(err.response.data.errors)[0][0];
            })
        },
        setPromocode() {
            this.promo_error = null;
            this.promo_success = null;
            axios.get(`/promocode?code=${this.promocode}`).then(r => {
                if (r.data.status === "success") {
                    this.purchase.promocode = r.data.promocode;
                    this.promo_success = "Promocode activated"
                } else {
                    this.promo_error = r.data.msg;
                }
            })
        }
    }
}
</script>

<style scoped>
.card-options {
    margin-top: 10px;
    border: 1px solid #343434;
    box-sizing: border-box;
    border-radius: 20px;
}

.card-options .card-body {
    /* padding: 20px 0px 30px 25px;*/
}

.form-check {
    padding-right: 15px;
}

.requirements {
    display: flex;
    justify-content: center;
    align-items: center;
    border: 1px solid;
    border-color: #343434;
    border-radius: 20px;
    flex-flow: column;
    padding: 15px 10px;
}

.double-btn {
    position: relative;
    height: 30px;
    width: 30px;
    width: 30px;
    height: 30px;
    background: linear-gradient(
        237.8deg, #FF3D00 9.27%, rgba(255, 114, 35, 0) 69.32%), #D96321;
    border-radius: 10px;
    padding: 0;
    padding: 7px 14px !important;
}

.double-btn span {
    position: absolute;
    bottom: 10%;
    left: 5%;
    padding: 3px 10px;
    background: linear-gradient(
        195.24deg, rgba(255, 255, 255, 0.25) -37.92%, rgba(255, 255, 255, 0) 46.01%), rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.35);
    box-sizing: border-box;
    -webkit-backdrop-filter: blur(10px);
    backdrop-filter: blur(10px);
    border-radius: 10px;
}

.card-body {
    background: #242424;
    border: 1px solid #3e3e3e;
    border-radius: 12px;
}

</style>
