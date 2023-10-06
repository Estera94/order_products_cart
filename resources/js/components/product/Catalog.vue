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
                    <div @click="addToCart(product)">
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
      <div class="modal fade" id="cartModal" tabindex="-1" role="dialog" aria-labelledby="cartModal" aria-hidden="true">
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
                    <h3 class="reset-margin">{{ product.name }}</h3>
                    <p class="reset-margin">{{ product.category }}</p>
                  </div>
                  <div>delete</div>
                </div>

                <div class="main-bottom">
                  <div class="quantity-selector">
                    <button class="decrease" @click.prevent="decrease(index)">-</button>
                    <input type="text" :value="product.quantity" readonly>
                    <button class="increase" @click.prevent="increase(index)">+</button>
                  </div>
                  <p class="reset-margin">{{ product.price * product.quantity }}</p>
                </div>
              </div>
            </div>

            <div class="subtotal">
              <p class="reset-margin">Subtotal:</p>
              <p class="reset-margin">£{{ subtotalOfProducts }}</p>
            </div>


            <div v-if="productsCart.length === 0" class="empty-message">
              <span>No items in the cart</span>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Continue shopping</button>
              <button type="submit" class="btn btn-primary" :class="{'disable': productsCart.length === 0}">
                Checkout
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>

export default {


  data() {
    return {
      products: {},
      productsCart: [],
    }
  },

  methods: {
    getResults(page = 1) {
      axios.get('api/product?page=' + page).then(({data}) => (this.products = data.data));
    },

    loadProducts() {
      axios.get("api/product").then(({data}) => (this.products = data.data));
    },

    addToCart(product) {
      console.log(product)

      Toast.fire({
        icon: 'success',
        title: `${product.name} added to the cart`,
        timer: 1300
      });

    },

    openModal() {
      $('#cartModal').modal('show');
    },

    increase(index) {
      this.productsCart[index].quantity = this.productsCart[index].quantity + 1
    },

    decrease(index) {
      if (this.productsCart[index].quantity === 1) return;

      this.productsCart[index].quantity = this.productsCart[index].quantity - 1
    },

  },

  filters: {
    truncate: function (text, length, suffix) {
      return text.substring(0, length) + suffix;
    },
  },

  computed: {
    subtotalOfProducts() {
      let subtotal = 0;
      for (const product of this.productsCart) {
        subtotal += product.price * product.quantity;
      }
      return subtotal;
    },
  },

  created() {
    this.loadProducts();
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
