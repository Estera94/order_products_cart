<template>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3>Order Details</h3>
                       
                            <div class="card-details">
                                <div>
                                    <h5>Ordered by</h5>
                                    <div>
                                        <p>{{ orderData.user?.name }}</p>
                                        <p>{{ orderData.user?.email }}</p>
                                    </div>
                                </div>
                                <div class="card-details__order">
                                    <div>Order Number</div>
                                    <span>{{ orderData.id }}</span>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-header -->

                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Description</th>
                                    <th>Quantity</th>
                                    <th>Date</th>
                                    <th style="text-align: end">Price</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(order) in orderData.shopping_cart_items" :key="order.id">
                                    <td>{{ order.product.name }}</td>
                                    <td>{{ order.product.description | truncate(50, '...') }}</td>
                                    <td>{{ order.quantity }}</td>
                                    <td>{{ formattedDate(order.created_at) }}</td>
                                    <td style="text-align: end">{{ order.product.price }}</td>
                                </tr>
                                </tbody>
                            </table>
                            <div class="total-cell">
                                <div>Total</div>
                                <div>£ {{ orderData.shopping_cart_total }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import axios from "axios";
import formattedDate from '../Partials/Helper'

export default {
    name: "ViewOrder",

    data() {
        return {
            orderData: []
        }
    },

    methods: {
        formattedDate,

        loadOrders() {
            axios.get(`/api/view-order?order_id=${this.$route.query.order_id}`)
                .then(({data}) => this.orderData = data.data)
                .catch((error) => {
                    console.log(error)
                });
        }
    },

    filters: {
        truncate: function (text, length, suffix) {
            return text.substring(0, length) + suffix;
        },
    },

    created() {
        this.loadOrders()
    },
}
</script>

<style scoped lang="scss">
.card-header {

    .card-details {
        display: flex;
        justify-content: space-between;
        padding: 10px;

        &__order {
            text-align: center;
        }
    }
}

.total-cell {
    background: rgba(124, 128, 152, 0.04);
    padding: 20px;
    display: flex;
    gap: 100px;
    justify-content: right;
    border-top: 1px solid rgba(241, 242, 245, 1);
    color: rgba(73, 73, 73, 1);
    font-size: 16px;
}
</style>
