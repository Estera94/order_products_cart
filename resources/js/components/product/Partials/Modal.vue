<template>
    <div class="modal fade" id="cartModal" tabindex="-1" role="dialog" aria-labelledby="cartModal"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Cart</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="cart-container" v-for="(product, index) in productsCart" :key="`cart-${index}`">
                    <div class="cart-container__content">
                        <div class="item-container">
                            <div class="item">
                                <h3 class="reset-margin">{{ product.product.name }}</h3>
                                <p class="reset-margin">{{ product.category }}</p>
                            </div>
                            <div @click.prevent="$emit('deleteItem', product.product)">
                                <span>
                                   <i class="fa fa-minus" aria-hidden="true"></i>
                                </span>
                            </div>
                        </div>

                        <div class="main-bottom">
                            <div class="quantity-selector">
                                <button class="decrease" @click.prevent="decrease(index)">-</button>
                                <input type="text" :value="product.quantity" readonly>
                                <button class="increase" @click.prevent="increase(index)">+</button>
                            </div>
                            <p class="reset-margin">{{ product.product.price * product.quantity }}</p>
                        </div>
                    </div>
                </div>

                <div v-if="productsCart.length > 0" class="subtotal">
                    <p class="reset-margin">Subtotal:</p>
                    <p class="reset-margin">£{{ subtotalOfProducts }}</p>
                </div>

                <div v-if="productsCart.length === 0" class="empty-message">
                    <span>No items in the cart</span>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Continue shopping
                    </button>
                    <button
                        @click.prevent="$emit('checkout')"
                        type="submit" class="btn btn-primary"
                        :class="{'disable': productsCart.length === 0}"
                    >
                        Checkout
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "Modal",

    emits: ['deleteItem', 'increase', 'decrease'],

    props: {
        productsCart: {
            type: Array,
            default: () => {
            }
        }
    },

    methods: {
        increase(index) {
            this.productsCart[index].quantity = this.productsCart[index].quantity + 1

            this.$emit('increase', this.productsCart[index].product)
        },

        decrease(index) {
            if (this.productsCart[index].quantity === 1) return;

            this.productsCart[index].quantity = this.productsCart[index].quantity - 1

            this.$emit('decrease', this.productsCart[index].product)
        },
    },

    computed: {
        subtotalOfProducts() {
            let subtotal = 0;
            for (const product of this.productsCart) {
                subtotal += product.product.price * product.quantity;
            }
            return subtotal;
        },
    },
}
</script>

<style scoped lang="scss">
.cart-container {
    display: flex;
    align-items: center;
    padding: 30px;
    border-bottom: 1px solid rgba(0, 0, 0, 0.1);

    &__content {
        width: 100%;

        .item-container {
            display: flex;
            justify-content: space-between;
            cursor: pointer;

            .item {
                p {
                    color: rgba(0, 0, 0, 0.4);
                }
            }
        }

        .main-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;

            .quantity-selector {
                input {
                    line-height: 28px;
                    border: none;
                    border-top: 1px solid rgba(0, 0, 0, 0.1);
                    border-bottom: 1px solid rgba(0, 0, 0, 0.1);
                    width: 45px;
                    text-align: center;
                    font-size: 13px;
                    color: #000;
                    padding: 0;
                    transform: scaleX(1.2);
                }

                input:focus {
                    outline: none;
                }

                button {
                    background: transparent;
                    width: 30px;
                    height: 30px;
                    border: 1px solid rgba(0, 0, 0, 0.1);
                    border-radius: 4px;
                    position: relative;
                    z-index: 1;
                }
            }
        }
    }
}

.modal {
    .reset-margin {
        margin-bottom: 0;
    }

    .subtotal {
        display: flex;
        justify-content: space-between;
        background-color: rgba(0, 0, 0, 0.05);

        p {
            margin: 30px;
            font-size: 18px;
        }
    }

    .empty-message {
        text-align: center;
        padding: 30px
    }
}

.disable {
    background: #dad3d3;
    pointer-events: none;
    color: black;
}
</style>
