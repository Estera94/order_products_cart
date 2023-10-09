export default [
    {path: '/dashboard', component: require('./components/Dashboard.vue').default},
    {path: '/profile', component: require('./components/Profile.vue').default},
    {path: '/developer', component: require('./components/Developer.vue').default},
    {path: '/users', component: require('./components/Users.vue').default},
    {path: '/products', component: require('./components/product/Products.vue').default},
    {path: '/catalog', component: require('./components/product/Catalog.vue').default},
    {path: '/view/orders', component: require('./components/product/ViewOrders.vue').default},
    {path: '/product/tag', component: require('./components/product/Tag.vue').default},
    {path: '/product/category', component: require('./components/product/Category.vue').default},
    {path: '/view-invoice', component: require('./components/product/ViewInvoice.vue').default},
    {path: '/view-order', component: require('./components/product/ViewOrder.vue').default},

    {path: '*', component: require('./components/NotFound.vue').default}
];
