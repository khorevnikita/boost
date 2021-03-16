<template>
    <div>
        <div class="row">
            <div class="col-6 col-sm-4">
                <button type="button" class="btn btn-outline-secondary text-white b-r-30" @click="show_categories = !show_categories">
                    Categories &nbsp; <img src="/images/icons/chevron-bottom.svg" style="width: 15px">
                </button>
            </div>

            <div class="col-6 col-sm-4">
                <!--<div class="input-group">
                    <input type="text" class="form-control b-r-30" id="search" v-model="search.q" placeholder="Search">
                    <div class="input-group-append">
                        <div class="input-group-text bg-dark">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g id="search_24px">
                                    <path id="icon/action/search_24px" fill-rule="evenodd" clip-rule="evenodd"
                                          d="M14.965 14.255H15.755L20.745 19.255L19.255 20.745L14.255 15.755V14.965L13.985 14.685C12.845 15.665 11.365 16.255 9.755 16.255C6.16504 16.255 3.255 13.345 3.255 9.755C3.255 6.16501 6.16504 3.255 9.755 3.255C13.345 3.255 16.255 6.16501 16.255 9.755C16.255 11.365 15.665 12.845 14.6851 13.985L14.965 14.255ZM5.255 9.755C5.255 12.245 7.26501 14.255 9.755 14.255C12.245 14.255 14.255 12.245 14.255 9.755C14.255 7.26501 12.245 5.255 9.755 5.255C7.26501 5.255 5.255 7.26501 5.255 9.755Z"
                                          fill="currentColor" fill-opacity="1"/>
                                </g>
                            </svg>
                        </div>
                    </div>
                </div>-->
                <input type="text" class="form-control b-r-30" id="search" v-model="search.q" placeholder="Search">
            </div>
            <!-- <div class="col-6 col-sm-4">
                 <label>Sort by: </label>&nbsp;
                 <div class="dropdown d-inline-block">
                     <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                         {{ search.sort_by === 'price' ? "Price" : "Popularity" }}
                     </button>
                     <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                         <a class="dropdown-item" role="button" @click="search.sort_by='price'">Price</a>
                         <a class="dropdown-item" role="button" @click="search.sort_by='popularity'">Popularity</a>
                     </div>
                 </div>
             </div>
 -->
        </div>
        <div class="row mt-3">
            <div class="col-3">
                <p>Sort by: </p>&nbsp;
            </div>

            <div class="col-5">
                <button class="btn btn-outline-secondary text-white b-r-30" type="button" @click="search.sort_by='popularity'">
                    Popularity
                    &nbsp; <img src="/images/icons/chevron-bottom.svg" style="width: 15px">
                </button>
            </div>
            <div class="col-4">
                <button class="btn btn-outline-secondary text-white b-r-30" type="button" @click="search.sort_by='price'">
                    Price
                    &nbsp; <img src="/images/icons/chevron-bottom.svg" style="width: 15px">
                </button>
            </div>
        </div>
        <div class="mt-3 js-category-list " v-bind:class="{'d-none':!show_categories}">
            <a @click="search.category_id = category.id" v-bind:class="{'ml-3':i > 0}" v-for="(category,i) in game.categories" style="font-size: 22px;cursor: pointer"><span
                class="badge badge-primary">{{ category.title }}</span></a>
        </div>
        <div class="row mt-5">
            <div v-for="product in products" class="col-12 col-sm-6 mt-4">
                <a :href="product.url">
                    <product-list-item :product="product" :currency="currency" :key="product.id"/>
                </a>
            </div>
        </div>

        <div class="text-center mt-5">
            <nav aria-label="Page navigation example" style="    width: fit-content; margin: auto;">
                <ul class="pagination">
                    <li class="page-item" v-if="search.page > 1">
                        <a class="page-link" role="button" style="cursor: pointer" aria-label="Previous" @click="search.page--">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <li class="page-item" v-bind:class="{'active':search.page===(i)}" v-for="i of pagesCount"><a class="page-link" role="button"
                                                                                                                 @click="search.page = (i)">{{ (i) }}</a></li>
                    <li class="page-item" v-if="search.page < pagesCount">
                        <a class="page-link" role="button" style="cursor: pointer" aria-label="Next" @click="search.page++">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</template>

<script>
import ProductListItem from "./ProductListItem";

export default {
    name: "GameProductList",
    components: {ProductListItem},
    props: ['game', 'currency'],
    data() {
        return {
            products: [],
            search: {
                q: "",
                sort_by: "popularity",
                category_id: 0,
                game_id: this.game.id,
                page: 1,
            },
            pagesCount: 1,
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
                this.pagesCount = r.data.pages_count;
                //    console.log(this.pagesCount);
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
