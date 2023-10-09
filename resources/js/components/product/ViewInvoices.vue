<template>
    <section class="content">
        <div class="container-fluid">
            <div class="row">

                <div class="col-12">

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Invoices</h3>
                        </div>


                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>Invoice No</th>
                                    <th>Invoice Date</th>
                                    <th>Total</th>
                                    <th>Customer Name</th>
                                    <th>Customer Email</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(invoice, index) in invoices.data" :key="`invoice-${index}`">
                                    <td style="cursor: pointer">{{ invoice.id }}</td>
                                    <td>{{ formattedDate(invoice.created_at) }}</td>
                                    <td>£{{ invoice.shopping_cart_total }}</td>
                                    <td>{{ invoice.user.name }}</td>
                                    <td>{{ invoice.user.email }}</td>
                                    <td>view order</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <!--            /.card-body-->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import axios from "axios";
import formattedDate from "./Partials/Helper";

export default {
    name: "ViewInvoices",

    data() {
        return {
            invoices: []
        }
    },

    methods: {
        formattedDate,

        loadInvoices() {
            axios.get('/api/invoices')
                .then(({data}) => (this.invoices = data))
                .catch((error) => {
                    console.log(error)
                });
        }
    },

    created() {
        this.loadInvoices()
    }
}
</script>
