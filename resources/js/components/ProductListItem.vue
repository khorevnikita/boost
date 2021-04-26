<template>
    <div class="card product-item-card">
        <div class="card-body text-center">
            <span class="product-item-price" v-if="!short">
                {{ product.price }} {{ currency === 'usd' ? "$" : "€" }}
            </span>

            <div class="position-absolute d-flex flex-column">
                <span v-if="product.category" class="badge badge-secondary" :id="`cat_${product.category.id}`" v-bind:style="{'background':background}">
                    {{ product.category.title }}
                </span>
                <span v-if="product.is_hot" style="margin: 10px" class="badge badge-danger">HOT</span>
                <span v-if="product.is_new" style="margin: 10px" class="badge badge-danger">NEW</span>
            </div>


            <img :src="product.banner" class="img-fluid">
        </div>
        <div v-if="!short" class="card-footer text-white text-center" style="    margin-top: -20px;">
            {{ product.title }}
        </div>
    </div>
</template>

<script>
export default {
    name: "ProductListItem",
    props: ['product', 'currency', 'short'],
    computed: {
        color_from() {
            try {

                return JSON.parse(this.product.category.colors).from;
            } catch (e) {
                return "#FF7A00"
            }
        },
        color_to() {
            try {
                return JSON.parse(this.product.category.colors).to;
            } catch (e) {
                return "#A6A020"
            }
        },
        background() {
            return `linear-gradient(233.78deg, ${this.color_from} 10.37%, rgba(255, 255, 255, 0) 71.14%), ${this.color_to}`;
        }
    }
}
</script>

<style scoped>

</style>
