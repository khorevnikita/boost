<template>
    <div class="container">
        <a href="/" class="nav-link" style="border-radius: 25px">
            <img class="img-fluid" src="/images/boost_logo.png" style="width: 45px">
        </a>
        <button class="b-r-30 btn btn-secondary btn-cart d-sm-none" type="button" @click="openCart()">
            <span class="badge badge-primary price-badge" v-bind:class="{'d-none':!products_in_order}">{{ products_in_order }}</span>
            <img src="/images/cart_icon.png"/>
        </button>

        <button v-if="!user" class="b-r-30 btn-primary btn d-sm-none" @click="auth_dialog=!auth_dialog">
            Log in
        </button>
        <!--<button v-if="user" class="b-r-30 btn-outline-secondary text-white btn d-sm-none">
            {{ user.surname }} {{ user.name }}
        </button>
        <button v-if="user" class="b-r-30 btn-primary text-white btn d-sm-none" style="    margin-left: -30px;">
            <img src="/images/icons/sign_out.png">
        </button>-->


        <button class="navbar-toggler b-r-30 bg-primary" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                aria-expanded="false"
                aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse " id="navbarSupportedContent">

            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto ">
                <!-- <li v-for="game in games" class="nav-item dropdown">
                     <a class="nav-link text-white" :href="`/${game.rewrite}`">
                         {{ game.title }}
                     </a>
                 </li>-->
                <li>
                    <div class="dropdown text-center">
                        <button class="btn btn-primary b-r-30 dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                            Choose your game
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a v-for="game in games" class="dropdown-item" :href="`/${game.rewrite}`">{{ game.title }}</a>
                        </div>
                    </div>
                </li>
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                <!-- Authentication Links -->
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle b-r-30 text-white btn-currency" href="#" role="button" data-toggle="dropdown" aria-haspopup="true"
                       aria-expanded="false">
                        {{ currency.toUpperCase() }} <span class="caret"></span>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="/currency/eur">
                            EUR
                        </a>
                        <a class="dropdown-item" href="/currency/usd">
                            USD
                        </a>
                    </div>

                </li>
                <li class="nav-item d-none d-sm-block">
                    <a href="/home" v-if="user && user.role!=='user'" class="b-r-30 btn-primary text-white btn d-none d-sm-inline">
                        Dashboard
                    </a>
                    <button style="    margin-left: 10px;" id="cart-btn" class="b-r-30 btn btn-secondary btn-cart " type="button" @click="openCart()">
                        <span class="badge badge-primary price-badge" v-bind:class="{'d-none':!products_in_order}">{{ products_in_order }}</span>
                        <img style="width: 25px" src="/images/cart_icon.png"/>
                    </button>

                    <button id="login" v-if="!user" class="b-r-30 btn-primary btn d-none d-sm-inline" @click="auth_dialog=!auth_dialog">
                        Log in
                    </button>
                    <a role="button" href="/profile" v-if="user" class="b-r-30 btn-outline-secondary text-white btn d-none d-sm-inline nav-link">
                        Profile
                    </a>
                    <button @click="signout()" v-if="user" class="b-r-30 btn-primary text-white btn d-none d-sm-inline" style="margin-left: -10px;">
                        <img src="/images/icons/sign_out.png">
                    </button>
                    <a href="/home" v-if="user && user.role==='user'" class="b-r-30 btn-primary text-white btn d-none d-sm-inline">
                        My orders
                    </a>


                </li>
            </ul>
        </div>

        <div class="dropdown-menu auth-menu bg-dark" v-bind:class="{'show':auth_dialog}" v-bind:style="{'right':`${right}px`}">
            <form class="px-4 py-3" v-if="type==='login'">
                <!--<input type="hidden" name="_token" v-model="csrf">-->
                <button type="button" @click="auth_dialog=false;" class="btn btn-primary b-r-30 double-btn close-btn float-right">
                    <span>x</span>
                </button>
                <p class="text-center">Log In</p>
                <div class="form-group">
                    <input v-model="credentials.email" type="email" class="form-control" id="exampleDropdownFormEmail1" placeholder="Email">
                    <p class="text-primary position-absolute" v-if="auth_errors.email">{{ auth_errors.email[0] }}</p>
                </div>
                <div class="form-group">
                    <input v-model="credentials.password" type="password" class="form-control" id="exampleDropdownFormPassword1" placeholder="Password">
                    <p class="text-primary position-absolute" v-if="auth_errors.password">{{ auth_errors.password[0] }}</p>
                </div>
                <a style="font-size: 14px;margin: 3px 5px;" href="/password/reset" class="text-primary float-right">Forgot your password?</a>

                <div class="form-check float-left">
                    <label class="form-check-label">
                            <span class="checkbox">
                                <img src="/images/icons/checkbox.svg" v-if="credentials.remember">
                            </span>
                        <input v-model="credentials.remember"
                               type="checkbox"
                               class="form-check-input">
                        <span>Remember me</span>
                    </label>
                </div>
                <div class="btn-group mt-3" role="group" aria-label="Basic example">
                    <button type="button" @click="login()" class="btn btn-primary double-btn login-btn">
                        <span>Sign In</span>
                    </button>
                    <button @click="type='register'" type="button" class="btn btn-outline-secondary text-white" style="  width: 50%;  margin-left: -10px;">Sign Up</button>
                </div>
            </form>

            <form class="px-4 py-3" v-if="type==='register'">
                <!--<input type="hidden" name="_token" v-model="csrf">-->
                <button type="button" @click="auth_dialog=false;" class="btn btn-primary b-r-30 double-btn close-btn float-right">
                    <span>x</span>
                </button>
                <p class="text-center">Sign Up</p>
                <div class="form-group">
                    <input v-model="credentials.surname" type="text" class="form-control" id="surname" placeholder="Surname">
                    <p class="text-primary position-absolute" v-if="auth_errors.surname">{{ auth_errors.surname[0] }}</p>
                </div>
                <div class="form-group">
                    <input v-model="credentials.name" type="text" class="form-control" id="name" placeholder="Name">
                    <p class="text-primary position-absolute" v-if="auth_errors.name">{{ auth_errors.name[0] }}</p>
                </div>
                <div class="form-group">
                    <input v-model="credentials.email" type="email" class="form-control" id="email" placeholder="Email">
                    <p class="text-primary position-absolute" v-if="auth_errors.email">{{ auth_errors.email[0] }}</p>
                </div>

                <div class="form-group">
                    <input v-model="credentials.password" type="password" class="form-control" id="password" placeholder="Password">
                    <p class="text-primary position-absolute" v-if="auth_errors.password">{{ auth_errors.password[0] }}</p>
                </div>
                <div class="form-group">
                    <input v-model="credentials.password_confirmation" type="password" class="form-control" id="c_password" placeholder="Confirm password">
                </div>

                <div class="btn-group mt-3" role="group" aria-label="Basic example">
                    <button type="button" @click="register()" class="btn btn-primary double-btn login-btn">
                        <span>Sign Up</span>
                    </button>
                </div>
                <button @click="type='login'" type="button" class="btn btn-link text-white">
                    Already have account? <span class="text-primary">sign in</span>
                </button>
            </form>


        </div>

        <form v-if="user" method="post" action="/logout" id="logout-form">
            <input type="hidden" name="_token" v-model="csrf">
        </form>

        <form style="    max-height: calc(100vh - 85px);    border-radius: 15px 0px 0px 15px;
    overflow-y: scroll;" class="cart-dialog px-4 py-3 pretty-scroll" v-if="cart_dialog" action="/order/form" method="POST" v-bind:style="{'right':`${right}px`}">
            <input type="hidden" name="_token" v-model="csrf">
            <div v-if="!order"><p>The cart is empty</p></div>
            <div v-else>
                <p class="text-center">Order <span class="text-primary">#{{ order.id }}</span></p>
                <!--<p class=" text-center text-secondary">({{ order.updated_at }})</p>-->
                <ul class="list-unstyled mt-3">
                    <li v-for="product in order.products">
                        <div class="row">
                            <div class="col-10">
                                <p>
                                    {{ product.title }} <br>
                                    <span class="text-primary">{{ product.price }}
                                    <span v-if="currency==='eur'">&euro;</span>
                                    <span v-else>$</span>
                                </span>
                                </p>

                            </div>
                            <div class="col-2">
                                <button @click="removeProduct(product)" type="button" class="btn btn-primary double-btn">
                                    <span>x</span>
                                </button>
                            </div>
                        </div>
                        <ul v-if="product.selected_options">
                            <li v-for="option in product.selected_options">
                                <p>{{ option.title }} <br>
                                    <span class="text-primary">{{ option.price }}
                                        <span v-if="option.type==='percent'">%</span>
                                        <span v-else-if="currency==='eur'">&euro;</span>
                                        <span v-else>$</span>
                                </span>
                                </p>
                            </li>
                        </ul>
                        <p style="padding-left: 10px" v-if="product.calculator && product.pivot.range">
                            {{ product.calculator.from }}: <span class="text-primary">{{ range(product.pivot.range).from }}</span><br>
                            {{ product.calculator.to }}: <span class="text-primary">{{ range(product.pivot.range).to }}</span><br>
                            <span class="text-primary">
                                {{ product.calculator.amount }}
                                <span v-if="currency==='eur'">&euro;</span>
                                <span v-else>$</span>
                            </span>
                        </p>
                    </li>
                </ul>
                <!-- <br>
                 <div class="form-group">
                     <input type="text" class="form-control" v-model="promocode" placeholder="Promo code">
                 </div>
                 <div v-if="email_form" class="form-group">
                     <input type="email" class="form-control" v-model="new_email" placeholder="Email">
                 </div>

                 <p style="position: absolute" class="text-primary">{{ error }}</p>
 -->
                <div class="d-flex justify-content-between" style="align-items: center;margin-top:40px">
                    <p style="margin: 0">Total
                        <span class="text-primary">
                        {{ order.amount }}
                        <span v-if="currency==='eur'">&euro;</span>
                        <span v-else>$</span>
                    </span>
                    </p>
                    <button type="button" class="btn btn-primary b-r-30" @click="checkout()">Checkout</button>
                </div>


                <div style="clear:both"></div>
            </div>
        </form>
    </div>
</template>

<script>
export default {
    name: "HeaderWidget",
    props: ["games", "currency", "csrf", "user", "product_count"],
    data: () => ({
            auth_dialog: false,
            type: "login",
            right: 0,
            cart_dialog: false,
            order: {},
            email_form: false,
            new_email: null,
            products_in_order: 0,
            promocode: "",
            error: "",
            credentials: {},
            auth_errors: {}
        }
    ),
    created() {
        this.products_in_order = this.product_count ? parseInt(this.product_count) : 0
    },
    watch: {
        auth_dialog(v) {
            if (v) {
                this.cart_dialog = false;
                let btn_r = document.querySelector("#login").getBoundingClientRect().right;
                if (btn_r) {
                    this.right = document.body.clientWidth - btn_r;
                }
            }
        },
        cart_dialog(v) {
            if (v) {
                this.auth_dialog = false;
                let btn_r = document.querySelector("#cart-btn").getBoundingClientRect().right;
                if (btn_r) {
                    this.right = document.body.clientWidth - btn_r;
                }
            }
        }
    },
    computed: {
        cart_email() {
            return this.new_email ? this.new_email : (this.user ? this.user.email : null)
        }
    },
    methods: {
        signout() {
            let f = document.querySelector("#logout-form")
            if (f) {
                f.submit();
            }
        }
        ,
        openCart() {
            if (this.cart_dialog) {
                this.cart_dialog = false;
            } else {
                this.getOrder();
                this.cart_dialog = true;
            }
        },
        getOrder() {
            axios.get(`/api/order?currency=${this.currency}`).then(r => {
                this.order = r.data.order;
                let p_length = this.order ? this.order.products.length : 0;
                let badge = $(".price-badge");
                if (p_length === 0) {
                    badge.text("").addClass("d-none");
                    try {
                        if (app.$children[0].added) {
                            app.$children[0].added = false;
                        }
                    } catch (e) {
                    }
                } else {
                    badge.text(p_length);
                    try {
                        let opened_p = app.$children[0].product.id;
                        if (!this.order.products.filter(p => p.id === opened_p)[0]) {
                            if (app.$children[0].added) {
                                app.$children[0].added = false;
                            }
                        }
                    } catch (e) {

                    }
                }
            })
        },
        removeProduct(product) {
            axios.put(`/api/orders`, {type: "product", product: product.id}).then(r => {
                this.getOrder();
            })
        },
        /*form() {
            if (!this.cart_email) {
                this.email_form = true;
                return;
            }
            this.error = "";
            axios.post(`/api/orders/${this.order.id}/form`, {email: this.cart_email, currency: currency, rate: rate, promocode: this.promocode}).then(r => {
                console.log(r.data);
                if (r.data.status === "error") {
                    this.error = r.data.msg;
                } else if (r.data.response.processingUrl) {
                    window.location.href = r.data.response.processingUrl;
                }
            })
        },*/
        checkout() {
            window.location.href = `/order/${this.order.id}/pay`;
        },
        login() {
            this.auth_errors = {};
            axios.post(`/auth/login`, this.credentials).then(e => {
                window.location.reload();
            }).catch(err => {
                this.auth_errors = err.response.data.errors;
                console.log(err.response.data)
            })
        },
        register() {
            this.auth_errors = {};
            axios.post(`/auth/register`, this.credentials).then(e => {
                window.location.reload();
            }).catch(err => {
                this.auth_errors = err.response.data.errors;
                console.log(err.response.data)
            })
        },
        range(range) {
            let obj = {};
            try {
                obj = JSON.parse(range)
            } catch (e) {

            }
            return obj;
        }
    }
}
</script>

<style scoped>
.auth-menu {
    left: 10px;
    right: 10px;
    background-color: #212529;

    top: 0px;
    left: 0;
    right: 0;

    margin-left: auto;
    /* top: 0px; */
    left: 0;
    right: 0px;
    max-width: 420px;
}

@media (min-width: 767px) {
    .auth-menu {
        top: 70px;
        right: 50px;
    }
}

.double-btn {
    position: relative;
    height: 30px;
    width: 30px;
    width: 30px;
    height: 30px;
    background: linear-gradient(
        237.8deg, #FF3D00 9.27%, rgba(255, 114, 35, 0) 69.32%), #D96321;
    border-radius: 10px;
    padding: 0;
}

.double-btn span {
    position: absolute;
    bottom: 10%;
    left: 5%;
    padding: 3px 10px;
    background: linear-gradient(
        195.24deg, rgba(255, 255, 255, 0.25) -37.92%, rgba(255, 255, 255, 0) 46.01%), rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.35);
    box-sizing: border-box;
    -webkit-backdrop-filter: blur(10px);
    backdrop-filter: blur(10px);
    border-radius: 10px;
}

.login-btn {
    width: 50%;
    height: 50px;
    border-radius: 10px !important;
    z-index: 2;
}

.login-btn span {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 3;
}

.btn-group {
    width: 100%;
}

.cart-dialog {
    position: absolute;
    background: #1c1c1c;
    top: 70px;
    border-radius: 20px;
    min-width: 320px;
}

@media (min-width: 768px) {
    .cart-dialog {
        min-width: 400px;
    }
}

.form-group {
    margin-bottom: 2rem;
}

#logout-form {
    position: absolute;
    opacity: 0;
}
</style>
