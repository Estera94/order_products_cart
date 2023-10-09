<template>
    <div>hello</div>
</template>

<script>
import axios from "axios";

export default {
    name: "ViewOrder",

    methods: {
        loadOrders() {
            console.log(this.$route.query.order_id)
            axios.get(`/api/view-invoice?order_id=${this.$route.query.order_id}`).then(response => {
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
