import { createRouter, createWebHistory } from 'vue-router';
import ProductList from './components/products/ProductList.vue';
import ProductForm from './components/products/ProductForm.vue';
import OrdersList from './components/orders/OrdersList.vue';
import OrderDetails from './components/orders/OrderDetails.vue';
import BannerList from './components/banners/BannerList.vue';
import BannerForm from './components/banners/BannerForm.vue';
import CategoryList from './components/categories/CategoryList.vue';

import UsersList from './components/users/UsersList.vue';
import CustomersList from './components/customers/CustomersList.vue';

// Placeholder components - will be replaced by actual files later
import DashboardHome from './components/DashboardHome.vue';

const routes = [
    { 
        path: '/dashboard', 
        name: 'Dashboard', 
        component: DashboardHome 
    },
    { 
        path: '/dashboard/products', 
        name: 'Products', 
        component: ProductList 
    },
    { 
        path: '/dashboard/products/create', 
        name: 'ProductCreate', 
        component: ProductForm 
    },
    { 
        path: '/dashboard/products/:id/edit', 
        name: 'ProductEdit', 
        component: ProductForm 
    },
    { 
        path: '/dashboard/orders', 
        name: 'Orders', 
        component: OrdersList 
    },
    { 
        path: '/dashboard/orders/:id', 
        name: 'OrderDetails', 
        component: OrderDetails 
    },
    { 
        path: '/dashboard/users', 
        name: 'Users', 
        component: UsersList 
    },
    {
        path: '/dashboard/customers',
        name: 'Customers',
        component: CustomersList
    },
    {
        path: '/dashboard/categories',
        name: 'Categories',
        component: CategoryList
    },
    {
        path: '/dashboard/categories/create',
        name: 'CategoryCreate',
        component: () => import('./components/categories/CategoryForm.vue')
    },
    {
        path: '/dashboard/categories/:id/edit',
        name: 'CategoryEdit',
        component: () => import('./components/categories/CategoryForm.vue')
    },
    { 
        path: '/dashboard/banners', 
        name: 'Banners', 
        component: BannerList 
    },
    { 
        path: '/dashboard/banners/create', 
        name: 'BannerCreate', 
        component: BannerForm 
    },
    { 
        path: '/dashboard/banners/:id/edit', 
        name: 'BannerEdit', 
        component: BannerForm 
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
