<template>
    <div>
        <form>
            <div class="row filters">
                <div class="col col-md-5">
                    <div class="input-group mb-2 mr-sm-2">
                        <input type="text" class="form-control" id="search" v-model="search.q" placeholder="Search">
                        <div class="input-group-append">
                            <div class="input-group-text bg-white">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g id="search_24px">
                                        <path id="icon/action/search_24px" fill-rule="evenodd" clip-rule="evenodd"
                                              d="M14.965 14.255H15.755L20.745 19.255L19.255 20.745L14.255 15.755V14.965L13.985 14.685C12.845 15.665 11.365 16.255 9.755 16.255C6.16504 16.255 3.255 13.345 3.255 9.755C3.255 6.16501 6.16504 3.255 9.755 3.255C13.345 3.255 16.255 6.16501 16.255 9.755C16.255 11.365 15.665 12.845 14.6851 13.985L14.965 14.255ZM5.255 9.755C5.255 12.245 7.26501 14.255 9.755 14.255C12.245 14.255 14.255 12.245 14.255 9.755C14.255 7.26501 12.245 5.255 9.755 5.255C7.26501 5.255 5.255 7.26501 5.255 9.755Z"
                                              fill="black" fill-opacity="0.54"/>
                                    </g>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <label>Sort by: </label>&nbsp;
                    <div class="dropdown d-inline-block">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{search.sort_by==='price'?"Price":"Popularity"}}
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" role="button" @click="search.sort_by='price'">Price</a>
                            <a class="dropdown-item" role="button" @click="search.sort_by='popularity'">Popularity</a>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <button type="button" class="btn btn-primary" @click="show_categories = !show_categories">
                        Categories &nbsp; <img src="/images/icons/chevron-bottom.svg" style="width: 15px">
                    </button>
                </div>

            </div>
            <div class="mt-3 js-category-list " v-bind:class="{'d-none':!show_categories}">
                <a @click="search.category_id = category.id" v-bind:class="{'ml-3':i > 0}" v-for="(category,i) in game.categories" style="font-size: 22px;cursor: pointer"><span
                    class="badge badge-primary">{{category.title}}</span></a>
            </div>
        </form>
        <div class="row mt-5">
            <div v-for="product in products" class="col-12 col-sm-6 mt-4">
                <a :href="product.url">
                    <div class="product-item" v-bind:style="{'background-image':'url(' + product.banner + ')'}">
                        <span style="margin: 10px" class="badge badge-secondary">{{product.category.title}}</span>

                        <span v-if="product.is_hot" style="margin: 10px" class="badge badge-danger float-right">HOT</span>

                        <span v-if="product.is_new" style="margin: 10px" class="badge badge-danger float-right">NEW</span>
                        <div class="product-item-footer">
                            <span class="float-right">
                                {{product.price}}
                                <svg width="20" height="20" viewBox="3 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<g id="euro_symbol_24px">
<path id="icon/action/euro_symbol_24px"
      d="M15 18.5C12.49 18.5 10.32 17.08 9.24 15H15V13H8.58C8.53 12.67 8.5 12.34 8.5 12C8.5 11.66 8.53 11.33 8.58 11H15V9H9.24C10.32 6.92 12.5 5.5 15 5.5C16.61 5.5 18.09 6.09 19.23 7.07L21 5.3C19.41 3.87 17.3 3 15 3C11.08 3 7.76 5.51 6.52 9H3V11H6.06C6.02 11.33 6 11.66 6 12C6 12.34 6.02 12.67 6.06 13H3V15H6.52C7.76 18.49 11.08 21 15 21C17.31 21 19.41 20.13 21 18.7L19.22 16.93C18.09 17.91 16.62 18.5 15 18.5Z"
      fill="white" fill-opacity="1"/>
</g>
</svg>

                            </span>
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
                    sort_by: "popularity",
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
    @media (max-width: 767px) {
        .filters {
            flex-flow: column;
            text-align: center;
        }

        .filters > .col {
            margin-top: 15px;
        }

        .filters label {
            display: block;
        }
    }
</style>
