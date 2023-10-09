<template>
    <div>invoice</div>
</template>

<script>
import axios from "axios";

export default {
    name: "ViewInvoice",


    methods: {
        loadOrders() {
            axios.get(`/api/view-invoice?invoice_id=${this.$route.query.invoice_id}`).then(response => {
                console.log(response)
            })
        }
    },

    created() {
        this.loadOrders()
    }
}
</script>


<style scoped>

</style>
