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
import SiteSettings from './components/settings/SiteSettings.vue';

// Placeholder components - will be replaced by actual files later
import DashboardHome from './components/DashboardHome.vue';
import AuthorList from './components/authors/AuthorList.vue';
import AuthorForm from './components/authors/AuthorForm.vue';
import PublisherList from './components/publishers/PublisherList.vue';
import PublisherForm from './components/publishers/PublisherForm.vue';
import TranslatorList from './components/translators/TranslatorList.vue';
import TranslatorForm from './components/translators/TranslatorForm.vue';

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
        path: '/dashboard/authors',
        name: 'Authors',
        component: AuthorList
    },
    {
        path: '/dashboard/authors/create',
        name: 'AuthorCreate',
        component: AuthorForm
    },
    {
        path: '/dashboard/authors/:id/edit',
        name: 'AuthorEdit',
        component: AuthorForm
    },
    {
        path: '/dashboard/publishers',
        name: 'Publishers',
        component: PublisherList
    },
    {
        path: '/dashboard/publishers/create',
        name: 'PublisherCreate',
        component: PublisherForm
    },
    {
        path: '/dashboard/publishers/:id/edit',
        name: 'PublisherEdit',
        component: PublisherForm
    },
    {
        path: '/dashboard/translators',
        name: 'Translators',
        component: TranslatorList
    },
    {
        path: '/dashboard/translators/create',
        name: 'TranslatorCreate',
        component: TranslatorForm
    },
    {
        path: '/dashboard/translators/:id/edit',
        name: 'TranslatorEdit',
        component: TranslatorForm
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
    {
        path: '/dashboard/settings',
        name: 'SiteSettings',
        component: SiteSettings
    },
    {
        path: '/dashboard/pages',
        name: 'Pages',
        component: () => import('./components/pages/PageList.vue')
    },
    {
        path: '/dashboard/pages/create',
        name: 'PageCreate',
        component: () => import('./components/pages/PageForm.vue')
    },
    {
        path: '/dashboard/pages/:id/edit',
        name: 'PageEdit',
        component: () => import('./components/pages/PageForm.vue')
    },
    {
        path: '/dashboard/shipping-companies',
        name: 'ShippingCompanies',
        component: () => import('./components/shipping/ShippingCompanyList.vue')
    },
    {
        path: '/dashboard/shipping-companies/create',
        name: 'ShippingCompanyCreate',
        component: () => import('./components/shipping/ShippingCompanyForm.vue')
    },
    {
        path: '/dashboard/shipping-companies/:id/edit',
        name: 'ShippingCompanyEdit',
        component: () => import('./components/shipping/ShippingCompanyForm.vue')
    },
    {
        path: '/dashboard/shipping-zones',
        name: 'ShippingZones',
        component: () => import('./components/shipping/ShippingZoneList.vue')
    },
    {
        path: '/dashboard/shipping-zones/create',
        name: 'ShippingZoneCreate',
        component: () => import('./components/shipping/ShippingZoneForm.vue')
    },
    {
        path: '/dashboard/shipping-zones/:id/edit',
        name: 'ShippingZoneEdit',
        component: () => import('./components/shipping/ShippingZoneForm.vue')
    },
    {
        path: '/dashboard/coupons',
        name: 'Coupons',
        component: () => import('./components/coupons/CouponList.vue')
    },
    {
        path: '/dashboard/coupons/create',
        name: 'CouponCreate',
        component: () => import('./components/coupons/CouponForm.vue')
    },
    {
        path: '/dashboard/coupons/:id/edit',
        name: 'CouponEdit',
        component: () => import('./components/coupons/CouponForm.vue')
    },
    {
        path: '/dashboard/offers',
        name: 'Offers',
        component: () => import('./components/offers/OfferList.vue')
    },
    {
        path: '/dashboard/offers/create',
        name: 'OfferCreate',
        component: () => import('./components/offers/OfferForm.vue')
    },
    {
        path: '/dashboard/offers/:id/edit',
        name: 'OfferEdit',
        component: () => import('./components/offers/OfferForm.vue')
    },
    {
        path: '/dashboard/bank-accounts',
        name: 'BankAccounts',
        component: () => import('./components/bank-accounts/BankAccountList.vue')
    },
    {
        path: '/dashboard/bank-accounts/create',
        name: 'BankAccountCreate',
        component: () => import('./components/bank-accounts/BankAccountForm.vue')
    },
    {
        path: '/dashboard/bank-accounts/:id/edit',
        name: 'BankAccountEdit',
        component: () => import('./components/bank-accounts/BankAccountForm.vue')
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
