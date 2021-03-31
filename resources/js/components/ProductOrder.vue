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
            <button data-toggle="modal" data-target="#purchase-modal" class="btn btn-outline-secondary text-white btn-block b-r-30">Quick purchase</button>
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
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Quick purchase</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" style="padding-bottom: 35px;">
                        <div class="form-group">
                            <input type="email" class="form-control" v-model="purchase.email" placeholder="E-mail">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" v-model="purchase.promocode" placeholder="Promo code">
                        </div>
                        <p style="position: absolute" class="text-primary">{{ error }}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" @click="formPurchase()">Submit</button>
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
                summ = summ + summ * po.price / 100
            }

            return Math.round(summ * 100) / 100;
        }
    },
    methods: {
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
            axios.post("/api/purchase", {
                range: this.range,
                product_id: this.product.id,
                options: this.selected_options.map(o => o.id),
                rate: rate,
                currency: currency,
                email: this.purchase.email,
                promocode: this.purchase.promocode
            }).then(r => {
                if (r.data.status === "error") {
                    this.error = r.data.msg;
                } else if (r.data.response.processingUrl) {
                    window.location.href = r.data.response.processingUrl;
                }
            }).catch(err => {
                this.error = Object.values(err.response.data.errors)[0][0];
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

</style>
