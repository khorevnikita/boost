<template>
    <div class="row">
        <div class="col-12">
            <p class="text-center">{{ calculator.name }}</p>
          <p class="text-center f-14">{{ calculator.description }}</p>
        </div>
        <div class="col-6 " v-if="calculator.multiple">
            <label class="f-14">{{ calculator.min_title }}</label>
            <input v-model="range.from" type="text" class="form-control">
        </div>

        <div class="col-6 " v-if="calculator.multiple">
            <label class="f-14">{{ calculator.max_title }}</label>
            <input v-model="range.to" type="text" class="form-control">
        </div>
        <div class="col-12 mt-3">
            <!-- AUTO -->
            <vue-slider ref="slider"
                        v-model="slider_value"
                        :order="true"
                        :min="calculator.min_value"
                        :max="calculator.max_value"
                        :interval="parseInt(calculator.step)"
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
    name: "AutoCalculator",
    props: ['calculator', 'product'],
    components: {VueSlider},
    data() {
        return {
            slider_value: [this.calculator ? this.calculator.min_value : 0, this.calculator ? this.calculator.max_value : 0],
            range: {from: this.calculator.min_value, to: this.calculator.max_value},
            slider_price: 0,
            calc_marks_array: null,
        }
    },
    watch: {
        slider_value: {
            handler: function (value) {
                if (!this.calculator.multiple && value[0] !== this.calculator.min_value) {
                    this.slider_value[0] = this.calculator ? this.calculator.min_value : 0;
                }
                this.range.from = value[0];
                this.range.to = value[1];
            },
            deep: true
        },
        range: {
            handler: function (range) {
                this.slider_value = [parseInt(range.from), parseInt(range.to)];
                this.calcValue(parseInt(range.from), parseInt(range.to));
            },
            deep: true,
        }
    },
    created() {
        this.calcValue(this.slider_value[0], this.slider_value[1])
    },
    mounted() {
        if (!this.calculator.multiple) {
            document.querySelector(".vue-slider-dot").style.display="none";
        }
    },
    methods: {
        calcValue(from, to) {
            let difference = to - from;
            if (this.calculator.step_type === "abs") {
                this.slider_price = difference * this.calculator.step_price;
                let productCurrency = this.product.currency;
                if (productCurrency !== currency) {
                    if (productCurrency === "usd") {
                        this.slider_price = this.slider_price / rate;
                    } else {
                        this.slider_price = this.slider_price * rate;
                    }
                }
            } else {
                let b1 = this.calculator.start_value ? this.calculator.start_value : 1;
                let q = (1 + this.calculator.step_price / 100);
                let min_price = b1 * (q ** this.range.from - 1) / (q - 1);
                let max_price = b1 * (q ** this.range.to - 1) / (q - 1);
                this.slider_price = Math.round(max_price - min_price);
            }
            this.$emit("change", {slider_price: this.slider_price, range: this.range});
        }
    }
}
</script>

<style scoped>
.f-14{
  font-size: 14px !important;
}
label.f-14{
  color: #636363 !important;
}
</style>
