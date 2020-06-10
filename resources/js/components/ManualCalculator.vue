<template>
    <div class="row">
        <div class="col-12">
            <h4>{{calculator.name}}</h4>
        </div>
        <div class="col">
            <label>{{calculator.min_title}}</label>
            <input v-model="range.from" type="text" class="form-control">
        </div>
        <div class="col text-center"><p>{{calculator.description}}</p></div>
        <div class="col">
            <label>{{calculator.max_title}}</label>
            <input v-model="range.to" type="text" class="form-control">
        </div>

        <div class="col-12 mt-3 mb-3">
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
        props: ['calculator'],
        components: {VueSlider},
        data() {
            return {
                slider_value: [this.calculator ? this.calculator.min_value : 0, this.calculator ? this.calculator.max_value : 0],
                range: {from: this.calculator.min_value, to: this.calculator.max_value},
                slider_price: 0,
                calc_marks_array: [],
            }
        },
        created() {
            for (var s of this.calculator.steps) {
                this.calc_marks_array.push(s.title);
            }
            this.calcValue(this.slider_value[0], this.slider_value[1]);
        },
        watch: {
            slider_value: {
                handler: function (value) {
                    this.calcValue(value[0], value[1]);
                },
                deep: true
            },
        },
        methods: {
            calcValue(from, to) {
                var from_step = this.calculator.steps.filter(s => s.title === from)[0];
                var to_step = this.calculator.steps.filter(s => s.title === to)[0];
                this.range.from = from_step.title;
                this.range.to = to_step.title;
                this.slider_price = to_step.price - from_step.price;
                this.$emit("change", this.slider_price);
            }
        }
    }
</script>

<style scoped>

</style>
