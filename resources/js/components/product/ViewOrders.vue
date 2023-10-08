<template>
  <section class="content">
    <div class="container-fluid">
      <div class="row">

        <div class="col-12">

          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Orders</h3>
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
                  <th>Order Details</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="order in orders" :key="order.id">
                  <td>{{ order.id }}</td>
                  <td>{{ formattedDate(order.created_at) }}</td>
                  <td>£{{ order.shopping_cart_total }}</td>
                  <td>{{ order.user.name }}</td>
                  <td>{{ order.user.email }}</td>
                  <td style="text-align:center;cursor: pointer">
                    <span>view</span>
                  </td>

                  <td style="text-align:center;cursor: pointer">
                    <button type="button" class="btn btn-sm btn-primary">
                      <!--                      @click="openModal"-->
                      <span>raise invoice</span>
                    </button>
                  </td>
                </tr>
                </tbody>
              </table>
            </div>
            <!-- /.card-body -->
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

export default {
  name: "ViewOrders",
  components: {Modal},

  data() {
    return {
      orders: []
    }
  },

  methods: {
    loadOrders() {

      axios.get('/api/orders')
          .then(({data}) => {
            this.orders = data
          })
          .catch((error) => {
            console.log(error)
          });
    },

    formattedDate(dateToFormat) {
      const options = {weekday: "short", day: "numeric", month: "short"};
      const date = new Date(dateToFormat);

      return date.toLocaleDateString("en-US", options);
    },
  },

  created() {
    this.loadOrders()
  }
}
</script>

<style scoped>

</style>
