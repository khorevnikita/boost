<template>
    <div class="row mt-3">
        <div class="col-12 col-md-8">
            <manual-calculator v-on:change="(v)=>{slider_price = v}" :calculator="calculator" v-if="calculator.steps && calculator.steps.length>0"></manual-calculator>
            <auto-calculator v-on:change="(v)=>{slider_price = v}" :calculator="calculator" v-else></auto-calculator>

            <div class=" pb-5 mt-3" v-html="product.short_description"></div>

            <div v-html="product.description"></div>
        </div>
        <div class="col-12 col-md-4">
            <div v-if="images && images.length > 0" id="carouselExampleControls" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner">
                    <div v-for="(img,i) in images" class="carousel-item @if(!$k) active @endif " v-bind:class="{'active':i==0}">
                        <img class="d-block w-100" :src="img.url" alt="">
                    </div>
                </div>
                <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
            <div>
                <h2>{{common_price}}
                    <span v-if="currency==='usd'">$</span>
                    <span v-else>
                        <svg width="24" height="24" viewBox="4 3 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<g id="euro_symbol_24px">
<path id="icon/action/euro_symbol_24px"
      d="M15 18.5C12.49 18.5 10.32 17.08 9.24 15H15V13H8.58C8.53 12.67 8.5 12.34 8.5 12C8.5 11.66 8.53 11.33 8.58 11H15V9H9.24C10.32 6.92 12.5 5.5 15 5.5C16.61 5.5 18.09 6.09 19.23 7.07L21 5.3C19.41 3.87 17.3 3 15 3C11.08 3 7.76 5.51 6.52 9H3V11H6.06C6.02 11.33 6 11.66 6 12C6 12.34 6.02 12.67 6.06 13H3V15H6.52C7.76 18.49 11.08 21 15 21C17.31 21 19.41 20.13 21 18.7L19.22 16.93C18.09 17.91 16.62 18.5 15 18.5Z"
      fill="black" fill-opacity="1"/>
</g>
</svg>
                    </span>
                </h2>
                <div class="form-check mt-3" v-for="option in options">
                    <input :checked="selected_options.filter(so=>so.id===option.id)[0]" @click="toggleOption(option)" type="checkbox" class="form-check-input"
                           :id="'opt'+option.id">
                    <label class="form-check-label" :for="'opt'+option.id">
                        {{option.title}}
                        <span>
                            +  {{option.price}}
                            <span v-if="option.type=='abs'">
                                <span v-if="currency==='usd'">$</span>
                                <span v-else>
                                    <svg width="15" height="15" viewBox="4 3 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<g id="euro_symbol_24px">
<path id="icon/action/euro_symbol_24px"
      d="M15 18.5C12.49 18.5 10.32 17.08 9.24 15H15V13H8.58C8.53 12.67 8.5 12.34 8.5 12C8.5 11.66 8.53 11.33 8.58 11H15V9H9.24C10.32 6.92 12.5 5.5 15 5.5C16.61 5.5 18.09 6.09 19.23 7.07L21 5.3C19.41 3.87 17.3 3 15 3C11.08 3 7.76 5.51 6.52 9H3V11H6.06C6.02 11.33 6 11.66 6 12C6 12.34 6.02 12.67 6.06 13H3V15H6.52C7.76 18.49 11.08 21 15 21C17.31 21 19.41 20.13 21 18.7L19.22 16.93C18.09 17.91 16.62 18.5 15 18.5Z"
      fill="black" fill-opacity="1"/>
</g>
</svg>
                                </span>
                            </span>
                            <span v-else>%</span>
                        </span>
                    </label>
                </div>

                <div class="mt-5">
                    <button class="btn btn-block" v-bind:class="{'btn-primary':!added,'btn-success':added}" @click="addToOrder()">
                        <span v-if="!added">Add to cart</span>
                        <span v-else>Already in cart</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    import AutoCalculator from "./AutoCalculator";
    import ManualCalculator from "./ManualCalculator";

    export default {
        name: "ProductOrder",
        props: ['product', 'options', 'calculator', 'currency', 'images'],
        components: {
            ManualCalculator,
            AutoCalculator,
        },
        data() {
            return {
                selected_options: this.product.selected_options ? this.product.selected_options : [],
                added: this.product.in_order,
                slider_price: 0,
            }
        },

        computed: {
            common_price: function () {
                var summ = this.product.price;
                for (var o of this.selected_options) {
                    if (o.type === "abs") {
                        summ = summ + o.price;
                    } else {
                        summ = summ + this.product.price * o.price / 100
                    }

                }
                summ = summ + this.slider_price;
                return Math.round(summ * 100) / 100;
            }
        },
        methods: {
            toggleOption(option) {
                let index = this.selected_options.indexOf(option);
                if (index > -1) {
                    this.selected_options.splice(index, 1)
                } else {
                    this.selected_options.push(option)
                }
            },
            addToOrder() {
                axios.post("/api/orders", {range: this.range, product_id: this.product.id, options: this.selected_options.map(o => o.id)}).then(r => {
                    if (r.data.status === "success") {
                        this.added = true;
                        Swal.fire({
                            icon: 'success',
                            title: 'Item added to cart',
                            showConfirmButton: false,
                            timer: 1500
                        })
                    }
                })
            }
        }
    }
</script>

<style scoped>

</style>
