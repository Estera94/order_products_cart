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
                    {{ filterOption }}
                  </button>
                  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" @click="filter('All')">All</a>
                    <a class="dropdown-item" @click="filter('Invoiced')">Invoiced</a>
                    <a class="dropdown-item" @click="filter('Not Invoiced')">Not Invoiced</a>
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
                <tr v-for="order in filteredOrders" :key="order.id">
                  <td>{{ order.id }}</td>
                  <td>{{ formattedDate(order.created_at) }}</td>
                  <td>£{{ order.shopping_cart_total }}</td>
                  <td>{{ order.user.name }}</td>
                  <td>{{ order.user.email }}</td>

                  <td v-if="order.invoice_status == false" style="text-align:center;cursor: pointer">
                    <button type="button" class="btn btn-sm btn-primary" @click="invoice(order)">
                      <span>raise invoice</span>
                    </button>
                  </td>
                  <td v-else style="text-align:center;cursor: pointer">
                    <strong>View invoice</strong>
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
      orders: [],
      filterOption: "All",
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

    invoice(order) {
      axios.post(`/api/orders/${order.id}`)
          .then((data) => {
            console.log(data)

            Toast.fire({
              icon: 'success',
              title: `Invoice was created`,
              timer: 1300
            });

            this.loadOrders()
          })
          .catch((error) => {
            console.log(error)
          });
    },

    filter(option) {
      this.filterOption = option;
    },

    formattedDate(dateToFormat) {
      const options = {weekday: "short", day: "numeric", month: "short"};
      const date = new Date(dateToFormat);

      return date.toLocaleDateString("en-US", options);
    },
  },
  
  computed: {
    filteredOrders() {
      if (this.filterOption === "All") {
        return this.orders;
      } else if (this.filterOption === "Invoiced") {
        return this.orders.filter((order) => order.invoice_status);
      } else if (this.filterOption === "Not Invoiced") {
        return this.orders.filter((order) => !order.invoice_status);
      }
    },
  },

  created() {
    this.loadOrders()
  }
}
</script>
