<template>
    <section class="content">
        <div class="container-fluid">
            <div class="row">

                <div class="col-12">

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Orders</h3>

                            <div class="card-tools">
                                <div class="dropdown">
                                    <button
                                        class="btn btn-secondary dropdown-toggle"
                                        type="button" id="dropdownMenuButton"
                                        data-toggle="dropdown"
                                        aria-haspopup="true"
                                        aria-expanded="false"
                                    >
                                        {{ filterOption.order_status }}
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" @click="filter('all')">All</a>
                                        <a class="dropdown-item" @click="filter('invoiced')">Invoiced</a>
                                        <a class="dropdown-item" @click="filter('not_invoiced')">Not Invoiced</a>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>Order No</th>
                                    <th>Order Date</th>
                                    <th>Total</th>
                                    <th>Customer Name</th>
                                    <th>Customer Email</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(order, index) in orders.data" :key="order.id">
                                    <td style="cursor: pointer">{{ order.id }}</td>
                                    <td>{{ formattedDate[index] }}</td>
                                    <td>£{{ order.shopping_cart_total }}</td>
                                    <td>{{ order.user.name }}</td>
                                    <td>{{ order.user.email }}</td>

                                    <td v-if="order.invoice_status == false" style="text-align:center;cursor: pointer">
                                        <button type="button" class="btn btn-sm btn-primary" @click="invoice(order)">
                                            <span>raise invoice</span>
                                        </button>
                                    </td>
                                    <td v-else style="text-align:center;cursor: pointer" @click="goToInvoice(order)">
                                        <strong>View invoice</strong>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <!--            /.card-body-->
                        <div class="card-footer">
                            <pagination :data="orders" @pagination-change-page="setPageNo"></pagination>
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import axios from "axios";
import Modal from "./Partials/Modal.vue";
import {debounce} from "lodash";
import moment from "moment";

export default {
    name: "ViewOrders",
    components: {Modal},

    data() {
        return {
            orders: {},
            filterOption: {
                order_status: 'all',
                page: 1
            },
        }
    },

    methods: {
        getResults() {
            const searchParams = new URLSearchParams(this.filterOption).toString();
            axios.get(`/api/orders?${searchParams}`).then(({data}) => (this.orders = data));
        },

        setPageNo(page) {
            this.filterOption.page = page
        },

        filter(option) {
            this.filterOption.order_status = option
        },

        invoice(order) {
            axios.post(`/api/orders/${order.id}`)
                .then((data) => {
                    console.log(data)

                    Toast.fire({
                        icon: 'success',
                        title: `Invoice was created`,
                        timer: 1300
                    });

                    order.invoice_status = 1
                })
                .catch((error) => {
                    console.log(error)
                });
        },

        goToInvoice(order) {
            window.location.href = `/view-invoice?invoice_id=${order.id}`
        }
    },

    created() {
        this.getResults();
    },

    computed: {
        formattedDate() {
            return this.orders.data.map(order => {
                return moment(order.created_at).format('ddd, MMM D');
            })
        }
    },

    watch: {
        filterOption: {
            handler: debounce(function () {
                this.getResults();
            }, 500),
            deep: true
        }
    }
}
</script>
