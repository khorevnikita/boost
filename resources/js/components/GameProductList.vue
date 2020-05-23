<template>
    <div>
        <form>
            <div class="row">
                <div class="col-5">
                    <div class="input-group mb-2 mr-sm-2">
                        <input type="text" class="form-control" id="search" v-model="search.q" placeholder="Search">
                        <div class="input-group-append">
                            <div class="input-group-text bg-white">
                                <img style="width: 20px" src="/images/icons/search.svg">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <button type="button" class="btn btn-primary" @click="show_categories = !show_categories">
                        Categories &nbsp; <img src="/images/icons/chevron-bottom.svg" style="width: 15px">
                    </button>
                </div>
                <div class="col">
                    <label>Sort by: </label>&nbsp;
                    <button type="button" @click="search.sort_by='price'" class="btn text-white" v-bind:class="{'btn-primary':search.sort_by==='price','btn-info':search.sort_by!=='price'}">Price</button>
                    <button type="button" @click="search.sort_by='popularity'" class="btn text-white" v-bind:class="{'btn-primary':search.sort_by==='popularity','btn-info':search.sort_by!=='popularity'}">Popularity</button>
                </div>
            </div>
            <div class="mt-3 js-category-list " v-bind:class="{'d-none':!show_categories}">
                <a @click="search.category_id = category.id" v-bind:class="{'ml-3':i > 0}" v-for="(category,i) in game.categories" style="font-size: 22px;cursor: pointer"><span
                    class="badge badge-primary">{{category.title}}</span></a>
            </div>
        </form>
        <div class="row mt-5">
            <div v-for="product in products" class="col-12 col-sm-6 mt-4">
                <a :href="'/'+product.category.game_id + '/' + product.id">
                    <div class="product-item" v-bind:style="{'background-image':'url(' + product.banner + ')'}">
                        <span style="margin: 10px" class="badge badge-secondary">{{product.category.title}}</span>

                        <span v-if="product.is_hot" style="margin: 10px" class="badge badge-danger float-right">HOT</span>

                        <span v-if="product.is_new" style="margin: 10px" class="badge badge-danger float-right">NEW</span>
                        <div class="product-item-footer">
                            <span class="float-right">{{product.price}}</span>
                            {{product.title}}
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "GameProductList",
        props: ['game'],
        data() {
            return {
                products: [],
                search: {
                    q: "",
                    sort_by: "",
                    category_id: 0,
                    game_id: this.game.id,
                },
                show_categories: false,
            }
        },
        created() {
            this.fetchProducts();
        },
        watch: {
            search: {
                handler: function () {
                    this.fetchProducts();
                },
                deep: true,
            }
        },
        methods: {
            fetchProducts() {
                axios.get("/api/products?" + $.param(this.search)).then(r => {
                    this.products = r.data.products;
                })
            }
        }
    }
</script>

<style scoped>

</style>
