<template>
    <section class="content">
        <div class="container-fluid">
            <div class="row">

                <div class="col-12">

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Catalog List</h3>

                            <div class="card-tools">

                                <button type="button" class="btn btn-sm btn-primary" @click="openModal">
                                    <span>View</span>
                                    <i class="fa fa-shopping-cart"></i>
                                    <span class="quantity">{{ totalQuantity }}</span>
                                </button>

                            </div>
                        </div>

                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th style="text-align: center">Add to cart</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="product in products.data" :key="product.id">

                                    <td>{{ product.id }}</td>
                                    <td>{{ product.name }}</td>
                                    <td>{{ product.description | truncate(30, '...') }}</td>
                                    <td>{{ product.category.name }}</td>
                                    <td>{{ product.price }}</td>
                                    <!-- <td><img v-bind:src="'/' + product.photo" width="100" alt="product"></td> -->
                                    <td style="text-align:center;cursor: pointer">
                                        <div @click="addToCart(product,'increase')">
                                            <a href="#">
                                                <i class="fa fa-cart-plus"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <pagination :data="products" @pagination-change-page="getResults"></pagination>
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
            </div>

            <!-- Modal -->
            <Modal
                :products-cart="productsCart"
                @deleteItem="(id)=>deleteItem(id)"
                @increase="(product) => addToCart(product, 'increase')"
                @decrease="(product) => addToCart(product, 'decrease')"
            />
        </div>
    </section>
</template>

<script>
import Modal from "./Partials/Modal.vue";

export default {
    components: {Modal},

    data() {
        return {
            products: {},
            productsCart: [],
            totalQuantity: 0,
        }
    },

    methods: {
        getResults(page = 1) {
            axios.get('api/product?page=' + page).then(({data}) => (this.products = data.data));
        },

        loadProducts() {
            axios.get("api/product").then(({data}) => (this.products = data.data));
        },

        loadCartItems() {
            axios.get("/api/cart-items")
                .then(({data}) => {
                    this.productsCart = data.products_in_cart
                    this.totalQuantity = data.total_quantity
                })
                .catch(error => {
                    console.log(error)
                });
            ;
        },

        addToCart(product, operator) {
            axios.post('/api/cart-items', {
                product_id: product.id,
                quantity: 1,
                operator: operator
            })
                .then(({data}) => {

                    Toast.fire({
                        icon: 'success',
                        title: `${product.name} added to the cart`,
                        timer: 1300
                    });

                    this.totalQuantity = data.original.total_quantity

                    this.loadCartItems()
                })
                .catch(error => {
                    console.log(error)
                });
        },

        deleteItem(product) {
            axios.delete(`/api/cart-items/${product.id}`)
                .then(response => {
                    Toast.fire({
                        icon: 'success',
                        title: `${product.name} was removed from the cart`,
                        timer: 1300
                    });
                    this.loadCartItems()
                })
                .catch(error => {
                    console.error(error);
                });
        },

        openModal() {
            $('#cartModal').modal('show');
        },
    },

    filters: {
        truncate: function (text, length, suffix) {
            return text.substring(0, length) + suffix;
        },
    },

    created() {
        this.loadProducts();
        this.loadCartItems();
    },
}
</script>

<style scoped lang="scss">
.card-header {
    position: relative;

    .quantity {
        position: absolute;
        background: red;
        color: #ffffff;
        border-radius: 50%;
        right: 2px;
        top: 4px;
        width: 22px;
        padding: 1px;
    }
}
</style>
