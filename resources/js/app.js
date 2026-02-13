import './bootstrap';
import { createApp } from 'vue';
import i18n from './i18n';

import Login from './components/auth/Login.vue';
import Register from './components/auth/Register.vue';
import Profile from './components/customer/Profile.vue';
import OrderList from './components/customer/OrderList.vue';
import OrderDetail from './components/customer/OrderDetail.vue';
import FavoritesList from './components/customer/FavoritesList.vue';
import CartCount from './components/ui/CartCount.vue';
import SideCart from './components/ui/SideCart.vue';
import Toast from './components/ui/Toast.vue';
import { useCart } from './stores/cart';
import { useNotification } from './stores/notification';

// Expose stores globally for Blade integration
window.cartStore = useCart();
window.notification = useNotification();

const app = createApp({});

app.use(i18n);

app.component('login-component', Login);
app.component('register-component', Register);
app.component('profile-component', Profile);
app.component('order-list-component', OrderList);
app.component('order-detail-component', OrderDetail);
app.component('favorites-list-component', FavoritesList);
app.component('cart-count-component', CartCount);
app.component('side-cart-component', SideCart);
app.component('toast-component', Toast);

app.mount('#app');
