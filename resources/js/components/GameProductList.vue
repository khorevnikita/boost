<template>
    <div>
        <p class="game-title "><span class="text-primary">{{ game.title }}</span> items</p>
        <div class="d-flex justify-content-end align-items-center product-filters">
            <p style="margin: 0">Sort by: </p>&nbsp;
            <button class="btn btn-outline-secondary text-white b-r-30 ml-2" type="button" @click="search.sort_by='popularity'">
                Popularity
                &nbsp;
                <img :src="`/images/icons/${search.sort_by==='popularity'?'down':'up'}.png`"/>
            </button>
            <button class="btn btn-outline-secondary text-white b-r-30 ml-2" type="button" @click="search.sort_by='price'">
                Price
                &nbsp;
                <img :src="`/images/icons/${search.sort_by==='price'?'down':'up'}.png`"/>
            </button>
            <button type="button" class="btn btn-outline-secondary text-white b-r-30 ml-2" @click="show_categories = !show_categories">
                All categories &nbsp;
                <img src="/images/icons/down.png"/>
            </button>
            <div class="search-form">
                <img src="/images/icons/search.png"/>
                <input type="text" class="form-inline b-r-30 ml-2" id="search" v-model="search.q" placeholder="Search">
            </div>

        </div>

        <div class="mt-3 js-category-list " v-bind:class="{'d-none':!show_categories}">
            <a @click="search.category_id = category.id" v-bind:class="{'ml-3':i > 0}" v-for="(category,i) in game.categories" style="font-size: 22px;cursor: pointer"><span
                class="badge badge-primary">{{ category.title }}</span></a>
        </div>
        <div class="row mt-5">
            <div v-for="product in products" class="col-12 col-sm-6 col-md-4 mt-4">
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
                currency: currency,
                rate: rate,
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
.game-title {
    margin: 13px 0px 0px 0px;
    float: left;
}

.search-form {
    width: fit-content;
    position: relative;
}

.search-form img {
    position: absolute;
    left: 20px;
    top: 15px
}

.search-form .form-inline {
    padding: 10px 10px 10px 30px !important;
    border-radius: 20px !important;
}

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

    .game-title {
        float: initial;
    }

    .product-filters {
        flex-wrap: wrap;
        justify-content: flex-start !important;
        margin-top: 15px;
    }

    .product-filters > * {
        margin-top: 10px;
    }

    .product-filters > input {
        max-width: 183px;

    }
}

.page-item {
    margin: 0 10px;
    border-radius: 10px;
}

.page-item a {
    background: #1C1C1C;
    border-radius: 10px;
    border: 1px solid #5B5B5B;
    box-sizing: border-box;

}

.page-item:last-child .page-link,.page-item:first-child .page-link {
    border-top-right-radius: 10px;
    border-bottom-right-radius: 10px;
    border-top-left-radius: 10px;
    border-bottom-left-radius: 10px;
}
.page-item.active .page-link{
    background: #1c1c1c;
    color: #D96321;
}
</style>
