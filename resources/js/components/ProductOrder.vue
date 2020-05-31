<template>
    <div>
        <h2>${{common_price}}</h2>
        <div class="form-check mt-3" v-for="option in options">
            <input @click="toggleOption(option)" type="checkbox" class="form-check-input" :id="'opt'+option.id">
            <label class="form-check-label" :for="'opt'+option.id">
                {{option.title}}
                <span>+ <span v-if="option.type=='abs'">$</span><span v-else>%</span>{{option.price}}</span>
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
        props: ['product', 'options', 'calculator'],
        data() {
            return {
                selected_options: [],
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
                return summ;
            }
        },
        methods: {
            initSlider() {
                let self = this;
                var sliderElement = $("#slider-range");
                if (sliderElement) {
                    sliderElement.slider({
                        range: true,
                        step: this.calculator.step,
                        min: this.calculator.min_value,
                        max: this.calculator.max_value,
                        values: [this.calculator.min_value, this.calculator.max_value],
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
                axios.post("/api/orders", {order_hash: localStorage.getItem('order_hash'), product_id: this.product.id, options: this.selected_options.map(o => o.id)}).then(r => {
                    if (r.data.status === "success") {
                        this.added = true;
                        localStorage.setItem("order_hash", r.data.hash);
                    }
                })
            }
        }
    }
</script>

<style scoped>

</style>
