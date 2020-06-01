<template>
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
            <input :checked="selected_options.filter(so=>so.id===option.id)[0]" @click="toggleOption(option)" type="checkbox" class="form-check-input" :id="'opt'+option.id">
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
</template>

<script>
    export default {
        name: "ProductOrder",
        props: ['product', 'options', 'calculator', 'currency'],
        data() {
            return {
                selected_options: this.product.selected_options ? this.product.selected_options : [],
                added: this.product.in_order,
                range: {from: null, to: null},
                slider_price: 0,
            }
        },
        mounted() {
            this.initSlider();
        },
        watch: {
            range: {
                handler: function (range) {
                    let difference = range.to - range.from;
                    if (this.calculator.step_type === "abs") {
                        this.slider_price = difference * this.calculator.step_price;
                    } else {
                        let b1 = this.calculator.start_value ? this.calculator.start_value : 1;
                        let q = (1 + this.calculator.step_price / 100);
                        let min_price = b1 * (q ** this.range.from - 1) / (q - 1);
                        let max_price = b1 * (q ** this.range.to - 1) / (q - 1);
                        this.slider_price = Math.round(max_price - min_price);
                    }
                },
                deep: true,
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
            initSlider() {
                let self = this;
                var sliderElement = $("#slider-range");
                if (sliderElement.length > 0) {
                    sliderElement.slider({
                        range: true,
                        step: this.calculator.step,
                        min: this.calculator.min_value,
                        max: this.calculator.max_value,
                        values: [this.product.pivot ? this.product.pivot.range.from : this.calculator.min_value, this.product.pivot ? this.product.pivot.range.to : this.calculator.max_value],
                        slide: function (event, ui) {
                            $("#slider-from").val(ui.values[0]);
                            $("#slider-to").val(ui.values[1]);
                            self.range.from = ui.values[0];
                            self.range.to = ui.values[1];

                        }
                    });
                    $("#slider-from").change(function () {
                        sliderElement.slider("values", 0, $(this).val());
                        self.range.from = parseInt($(this).val());
                    });
                    $("#slider-to").change(function () {
                        sliderElement.slider("values", 1, $(this).val());
                        self.range.to = parseInt($(this).val());
                    });


                    this.range.from = parseInt($("#slider-from").val());
                    this.range.to = parseInt($("#slider-to").val());
                }
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
