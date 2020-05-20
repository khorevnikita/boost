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
        props: ['product', 'options'],
        data() {
            return {
                selected_options: [],
                added: this.product.in_order,
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
                return summ;
            }
        },
        created() {
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
                axios.post("/api/orders", {product_id: this.product.id, options: this.selected_options.map(o => o.id)}).then(r => {
                    if (r.data.status === "success") {
                        this.added = true;
                    }
                })
            }
        }
    }
</script>

<style scoped>

</style>
