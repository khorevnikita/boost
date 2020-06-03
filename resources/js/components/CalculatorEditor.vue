<template>
    <div>
        <div v-if="creating">
            <div class="form-group">
                <label for="title">Title</label>
                <input id="title" type="text" name="name" class="form-control" v-model="calc.name">
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" class="form-control" v-model="calc.description"></textarea>
            </div>
            <div class="form-group">
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="step-switch" v-model="is_manual">
                    <label class="custom-control-label" for="step-switch">Manual steps</label>
                </div>
            </div>
            <div class="auto-steps" v-if="!is_manual">
                <div class="form-group row">
                    <div class="col-6">
                        <label for="min_title">Min title</label>
                        <input id="min_title" type="text" class="form-control" v-model="calc.min_title">
                    </div>
                    <div class="col-6">
                        <label for="min_value">Min value</label>
                        <input placeholder="0" id="min_value" type="number" class="form-control" v-model="calc.min_value">
                    </div>

                </div>
                <div class="form-group row">
                    <div class="col-6">
                        <label for="max_title">Max title</label>
                        <input id="max_title" type="text" class="form-control" v-model="calc.max_title">
                    </div>
                    <div class="col-6">
                        <label for="max_value">Max value</label>
                        <input id="max_value" type="number" class="form-control" v-model="calc.max_value">
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col">
                        <label for="step">Step</label>
                        <input placeholder="1" id="step" type="number" class="form-control" v-model="calc.step">
                    </div>
                    <div class="col">
                        <label for="step_type">Step type</label>
                        <select v-model="calc.step_type" id="step_type" class="form-control">
                            <option value="abs">Absolute</option>
                            <option value="percent">Percent</option>
                        </select>
                    </div>
                    <div class="col">
                        <label for="step_price">Step price</label>
                        <input placeholder="1" id="step_price" class="form-control" v-model="calc.step_price">
                    </div>
                    <div class="col" v-if="calc.step_type==='percent'">
                        <label>Start value</label>
                        <input type="number" class="form-control" v-model="calc.start_value">
                    </div>
                </div>
            </div>
            <div class="manual-steps mb-3" v-else>
                <div>
                    <div class="row">
                        <div class="col">
                            <label>Step title</label>
                        </div>
                        <div class="col">
                            <label>Step price</label>
                        </div>
                        <div class="col"></div>
                    </div>

                    <div class="form-group row" v-for="(step,i) in calc.steps">
                        <div class="col">
                            <input type="text" class="form-control" v-model="step.title">
                        </div>
                        <div class="col">
                            <input type="text" class="form-control" v-model="step.price">
                        </div>
                        <div class="col">
                            <button type="button" class="btn btn-link text-danger" @click="calc.steps.splice(i,1)">X</button>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-link text-primary" @click="calc.steps.push({title:'',price:''})">Add step</button>
            </div>

            <div class="form-group">
                <button type="button" class="btn btn-primary" @click="save()">Save calculator</button>
            </div>
            <div style="clear:both"></div>
        </div>
        <div class="form-group" v-if="!calculator">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="customSwitch1" v-model="creating">
                <label class="custom-control-label" for="customSwitch1"> Create calculator</label>
            </div>
        </div>
    </div>

</template>

<script>
    export default {
        name: "CalculatorEditor",
        props: ['product', 'calculator'],
        data() {
            return {
                calc: {
                    product_id: this.product.id,
                    name: "",
                    description: "",
                    min_title: "",
                    min_value: "",
                    max_title: "",
                    max_value: "",
                    step: "",
                    step_price: "",
                    step_type: "",
                    start_value: "",
                    steps: []
                },
                creating: false,
                is_manual: false,
            }
        },
        watch: {
            is_manual: function (is_manual) {
                if (is_manual) {
                    if (!this.calc.steps) {
                        this.$set(this.calc, 'steps', []);
                        //this.calc.steps = [];
                    }
                } else {
                    this.calc.steps = [];
                }
            }
        },
        created() {
            if (this.calculator) {
                this.creating = true;
                this.calc = this.calculator;
                if (this.calculator.steps && this.calculator.steps.length > 0) {
                    this.is_manual = true;
                }
            }
        },
        methods: {
            save() {
                if (!this.calc.id) {
                    axios.post("/admin/calculator", this.calc).then(r => {

                    })
                } else {
                    axios.put("/admin/calculator/" + this.calc.id, this.calc).then(r => {

                    })
                }
            }
        }
    }
</script>

<style scoped>

</style>
