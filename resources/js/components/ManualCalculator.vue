<template>
    <div class="row">
        <div class="col-12">
            <h4>{{ calculator.name }}</h4>
        </div>
        <div class="col text-center">
            <label>{{ calculator.min_title }}</label>
            <p>{{ range.from }}</p>
        </div>
        <div class="col text-center"><p>{{ calculator.description }}</p></div>
        <div class="col text-center">
            <label>{{ calculator.max_title }}</label>
            <p>{{ range.to }}</p>
        </div>

        <div class="col-12 col-sm-10 offset-sm-1 mt-3 mb-3">
            <!-- MANUAL -->
            <vue-slider ref="slider"
                        v-model="slider_value"
                        :order="true"
                        :min="0"
                        :max="calc_marks_array.length-1"
                        :step="calculator.step"
                        :data="calc_marks_array"
                        :marks="true"
                        :adsorb="true"
                        :enable-cross="false"
            ></vue-slider>
        </div>
    </div>
</template>

<script>
import VueSlider from 'vue-slider-component'
import 'vue-slider-component/theme/default.css'

export default {
    name: "ManualCalculator",
    props: ['calculator', 'product'],
    components: {VueSlider},
    data() {
        return {
            slider_value: null,
            range: {from: this.calculator.min_value, to: this.calculator.max_value},
            slider_price: 0,
            calc_marks_array: [],
        }
    },
    created() {
        this.slider_value = [this.calculator ? this.calculator.min_value : 0, this.calculator ? this.calculator.max_value : 0]
        for (var s of this.calculator.sorted_steps) {
            this.calc_marks_array.push(s.title);
        }
        this.calcValue(this.slider_value[0], this.slider_value[1]);
    },
    watch: {
        slider_value: {
            handler: function (value) {
                if (!this.calculator.multiple && value[0] !== this.calculator.min_value) {
                    this.slider_value[0] = this.calculator ? this.calculator.min_value : 0;
                }
                this.calcValue(value[0], value[1]);
            },
            deep: true
        },
    },
    mounted() {
        if (!this.calculator.multiple) {
            document.querySelector(".vue-slider-dot").style.display = "none";
        }
    },
    methods: {
        calcValue(from, to) {
            var from_step = this.calculator.steps.filter(s => s.title === from)[0];
            var to_step = this.calculator.steps.filter(s => s.title === to)[0];
            this.range.from = from_step.title;
            this.range.to = to_step.title;
            this.slider_price = Math.abs(to_step.price - from_step.price);
            let productCurrency = this.product.currency ? this.product.currency : "eur";
            if (productCurrency !== currency) {
                if (currency === "eur") {
                    this.slider_price = this.slider_price / rate;
                } else {
                    this.slider_price = this.slider_price * rate;
                }
            }
            this.$emit("change", {slider_price: this.slider_price, range: {from: from_step.price, to: to_step.price}});
        }
    }
}
</script>

<style scoped>

</style>
