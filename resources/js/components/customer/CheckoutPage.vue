<template>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12" dir="rtl">
        <header class="mb-10 text-right">
            <h1 class="text-4xl font-extrabold text-primary-dark tracking-tight">{{ t('Complete Your Order') }}</h1>
            <p class="mt-2 text-lg text-gray-500">{{ t('Finalize your purchase securely and quickly.') }}</p>
        </header>

        <div v-if="loading" class="flex flex-col justify-center items-center h-96 space-y-4">
            <div class="relative w-16 h-16">
                <div class="absolute inset-0 border-4 border-gray-200 rounded-full"></div>
                <div class="absolute inset-0 border-4 border-secondary-orange border-t-transparent rounded-full animate-spin"></div>
            </div>
            <p class="text-gray-500 font-medium animate-pulse">{{ t('Preparing your checkout...') }}</p>
        </div>

        <div v-else-if="state.items.length === 0" class="max-w-xl mx-auto text-center py-16 px-4 bg-white rounded-3xl shadow-xl border border-gray-100">
             <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
             </div>
            <h3 class="text-2xl font-bold text-gray-900">{{ t('Your cart is empty') }}</h3>
            <p class="mt-2 text-gray-500">{{ t('Looks like you haven\'t added any items yet.') }}</p>
            <div class="mt-10">
                <a href="/products" class="inline-flex items-center px-8 py-4 border border-transparent text-base font-bold rounded-2xl text-white bg-secondary-orange hover:bg-orange-600 transition-all transform hover:-translate-y-1 shadow-lg hover:shadow-orange-200">
                    {{ t('Continue Shopping') }}
                </a>
            </div>
        </div>

        <div v-else class="flex flex-col lg:flex-row gap-8 items-start">
            <!-- Right Side (Main Content): Cart Items -->
            <div class="flex-1 w-full space-y-8">
                <!-- Cart Items Section -->
                <section class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
                    <div class="px-4 sm:px-8 py-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
                        <h2 class="text-xl font-bold text-gray-900">{{ t('Your Cart') }}</h2>
                        <span class="bg-white px-3 py-1 rounded-full text-xs font-bold border border-gray-200 shadow-sm">{{ state.count }} {{ t('Items') }}</span>
                    </div>
                    
                    <!-- Free Shipping Progress -->
                    <div v-if="showProgressBar" class="px-4 sm:px-8 pt-6 pb-2">
                        <div class="bg-orange-50 rounded-2xl p-4 border border-orange-100">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-xs font-bold text-orange-700 uppercase tracking-tight">
                                    {{ t('Free Shipping Goal') }}
                                </span>
                                <span class="text-xs font-black text-orange-800">{{ Math.round(progressPercent) }}%</span>
                            </div>
                            <div class="overflow-hidden h-2.5 flex rounded-full bg-orange-200/50 p-0.5">
                                <div :style="'width:' + progressPercent + '%'" class="shadow-sm flex flex-col text-center rounded-full whitespace-nowrap text-white justify-center bg-secondary-orange transition-all duration-1000"></div>
                            </div>
                            <p class="text-[10px] text-orange-600 mt-2 font-medium">
                                {{ tp('Add {0} more for free shipping', [ remainingForFree + ' ' + t(state.currency) ]) }}
                            </p>
                        </div>
                    </div>

                    <!-- Cart Items List -->
                    <ul class="px-4 sm:px-8 py-4 divide-y divide-gray-50">
                        <transition-group name="list">
                            <li v-for="item in state.items" :key="item.id" class="py-5 flex gap-3 sm:gap-5 group items-center">
                                <div class="relative w-24 h-24 flex-shrink-0">
                                    <img :src="item.image.startsWith('http') || item.image.startsWith('/storage') || item.image.startsWith('storage') ? (item.image.startsWith('http') || item.image.startsWith('/') ? item.image : '/' + item.image) : '/storage/' + item.image" class="w-24 h-24 object-cover rounded-2xl border border-gray-100 shadow-sm transition-transform group-hover:scale-105">
                                    <div v-if="item.is_free" class="absolute -top-2 -left-2 bg-green-500 text-white text-[10px] uppercase font-black px-2 py-0.5 rounded-lg shadow-sm">
                                        {{ t('Free') }}
                                    </div>
                                </div>
                                
                                <div class="flex-1 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                                    <div class="flex-1">
                                        <div class="flex justify-between items-start">
                                            <h3 class="text-lg font-bold text-gray-900 line-clamp-2 leading-snug">{{ item.name }}</h3>
                                        </div>
                                        <p class="text-sm text-gray-500 mt-1">{{ item.type }}</p>
                                        <p class="text-sm text-gray-600 mt-0.5 font-medium">
                                            {{ item.price.toFixed(2) }} {{ t(state.currency) }} / {{ t('unit') }}
                                        </p>
                                    </div>
                                    
                                    <div class="flex items-center gap-2 sm:gap-6">
                                        <div class="flex items-center bg-gray-50 rounded-xl px-1 sm:px-2 py-1 border border-gray-100 shadow-inner scale-90 sm:scale-100 origin-right">
                                            <button @click="changeQty(item, -1)" class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-primary-dark transition-colors disabled:opacity-30" :disabled="item.is_free">-</button>
                                            <span class="px-1 sm:px-3 text-sm font-bold text-gray-900 min-w-[20px] sm:min-w-[30px] text-center">{{ item.quantity }}</span>
                                            <button @click="changeQty(item, 1)" class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-primary-dark transition-colors disabled:opacity-30" :disabled="item.is_free">+</button>
                                        </div>

                                        <div class="text-left min-w-[70px] sm:w-24">
                                            <p v-if="item.is_free" class="text-[10px] sm:text-sm font-black text-green-600 line-through decoration-gray-400/50">
                                                {{ item.original_price ? (item.original_price * item.quantity).toFixed(2) : '75.00' }} {{ t(state.currency) }}
                                            </p>
                                            <p :class="item.is_free ? 'text-green-600 font-black' : 'text-primary-dark font-black'" class="text-base sm:text-lg whitespace-nowrap">
                                                {{ item.is_free ? '0' : (item.price * item.quantity).toFixed(2) }} {{ t(state.currency) }}
                                            </p>
                                        </div>

                                        <button v-if="!item.is_free" @click="removeItem(item)" class="text-gray-400 hover:text-red-500 p-1 sm:p-2 rounded-lg hover:bg-red-50 transition-all">
                                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    </div>
                                </div>
                            </li>
                        </transition-group>
                    </ul>

                    <!-- Recommended Products -->
                    <div v-if="cartData.recommended && cartData.recommended.length > 0" class="px-4 sm:px-8 py-6 border-t border-gray-50 bg-gray-50/30">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-secondary-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" /></svg>
                            {{ t('You might also like') }}
                        </h3>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                            <div v-for="product in cartData.recommended" :key="product.id" class="bg-white p-3 rounded-2xl shadow-sm border border-gray-100 flex flex-col items-center text-center hover:shadow-md transition-all group">
                                <div class="relative mb-2">
                                     <img :src="product.image.startsWith('http') || product.image.startsWith('/storage') || product.image.startsWith('storage') ? (product.image.startsWith('http') || product.image.startsWith('/') ? product.image : '/' + product.image) : '/storage/' + product.image" class="w-16 h-16 object-cover rounded-xl">
                                </div>
                                <h4 class="text-xs font-bold text-gray-900 line-clamp-2 h-8 w-full mb-1">{{ product.name }}</h4>
                                <p class="text-[10px] text-gray-500 font-medium mb-3">{{ product.price.toFixed(2) }} {{ t(state.currency) }}</p>
                                <button @click="addToCart(product)" class="w-full py-1.5 bg-gray-50 text-gray-700 font-bold rounded-lg text-[10px] hover:bg-secondary-orange hover:text-white transition-all shadow-sm hover:shadow-orange-100">
                                    {{ t('Add') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <!-- Left Side (Sidebar): Address, Payment, Summary -->
            <!-- ... -->
            <div class="w-full lg:w-[30%] space-y-8 sticky top-24">
                <!-- Shipping Info Section -->
                <section class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden transition-all hover:shadow-md">
                    <div @click="toggleShipping" class="px-8 py-6 border-b border-gray-50 bg-gray-50/30 cursor-pointer flex justify-between items-center group">
                        <div class="flex items-center space-x-3 space-x-reverse">
                            <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center group-hover:bg-blue-100 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <h2 class="text-xl font-bold text-gray-900 mx-3">{{ t('Shipping Information') }}</h2>
                        </div>
                        <div class="transform transition-transform duration-300" :class="{'rotate-180': isShippingExpanded}">
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-primary-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </div>
                    </div>
                    
                    <transition name="accordion">
                    <div v-show="isShippingExpanded" class="p-8">
                        <div class="grid grid-cols-1 gap-y-7 gap-x-6 sm:grid-cols-2">
                            <div class="space-y-1.5 sm:col-span-2">
                                <label class="text-sm font-semibold text-gray-700 mr-1">{{ t('Full Name') }} <span class="text-red-500">*</span></label>
                                <input v-model="form.name" type="text" class="block w-full px-4 py-3 bg-gray-50 border-gray-200 rounded-xl shadow-sm focus:ring-2 focus:ring-secondary-orange/20 focus:border-secondary-orange transition-all outline-none" :placeholder="t('e.g. Abdullah Ahmed')">
                            </div>
                            
                            <div class="space-y-1.5 sm:col-span-2">
                                <label class="text-sm font-semibold text-gray-700 mr-1">{{ t('Phone Number') }} <span class="text-red-500">*</span></label>
                                <input v-model="form.phone" type="text" class="block w-full px-4 py-3 bg-gray-50 border-gray-200 rounded-xl shadow-sm focus:ring-2 focus:ring-secondary-orange/20 focus:border-secondary-orange transition-all outline-none text-left" dir="ltr" :placeholder="t('05xxxxxxxx')">
                            </div>

                            <div class="space-y-1.5 sm:col-span-2">
                                <label class="text-sm font-semibold text-gray-700 mr-1">{{ t('Email Address') }}</label>
                                <input v-model="form.email" type="email" class="block w-full px-4 py-3 bg-gray-50 border-gray-200 rounded-xl shadow-sm focus:ring-2 focus:ring-secondary-orange/20 focus:border-secondary-orange transition-all outline-none" :placeholder="t('example@email.com')">
                            </div>

                            <div class="space-y-1.5 sm:col-span-2">
                                <label class="text-sm font-semibold text-gray-700 mr-1">{{ t('Shipping District / Zone') }} <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <select v-model="form.shipping_zone_id" @change="updateZone" class="block w-full px-4 py-3 bg-gray-50 border-gray-200 rounded-xl shadow-sm focus:ring-2 focus:ring-secondary-orange/20 focus:border-secondary-orange transition-all outline-none appearance-none cursor-pointer">
                                        <option value="" disabled>{{ t('Select Zone') }}</option>
                                        <option v-for="zone in shippingZones" :key="zone.id" :value="zone.id">
                                            {{ getZoneName(zone) }}
                                        </option>
                                    </select>
                                    <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-gray-400">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </div>
                                </div>
                            </div>

                            <div class="sm:col-span-2 space-y-1.5">
                                <label class="text-sm font-semibold text-gray-700 mr-1">{{ t('Detailed Address') }} <span class="text-red-500">*</span></label>
                                <textarea v-model="form.address" rows="3" class="block w-full px-4 py-3 bg-gray-50 border-gray-200 rounded-xl shadow-sm focus:ring-2 focus:ring-secondary-orange/20 focus:border-secondary-orange transition-all outline-none" :placeholder="t('Street, Building, Apartment No...')"></textarea>
                            </div>
                        </div>
                    </div>
                    </transition>
                </section>

                <!-- Payment Method Section -->
                <section class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-8 py-6 border-b border-gray-50 bg-gray-50/30">
                        <div class="flex items-center space-x-3 space-x-reverse">
                            <div class="w-10 h-10 bg-green-50 text-green-600 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            </div>
                            <h2 class="text-xl font-bold text-gray-900 mx-3">{{ t('Payment Method') }}</h2>
                        </div>
                    </div>

                    <div class="p-8">
                        <div class="grid grid-cols-1 gap-4">
                            <label class="relative group cursor-pointer">
                                <input type="radio" v-model="form.payment_method" value="COD" class="peer sr-only">
                                <div class="p-5 border-2 border-gray-100 rounded-2xl group-hover:bg-gray-50 transition-all peer-checked:border-secondary-orange peer-checked:bg-orange-50/30 flex items-start space-x-4 space-x-reverse">
                                    <div class="p-3 bg-gray-100 rounded-xl peer-checked:bg-secondary-orange group-hover:scale-110 transition-transform">
                                        <svg class="h-6 w-6 text-gray-600 group-hover:text-secondary-orange peer-checked:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1 pt-0.5">
                                        <p class="font-bold text-gray-900">{{ t('Cash on Delivery') }}</p>
                                        <p class="text-xs text-gray-500 mt-1">{{ t('Pay when items arrive at your door') }}</p>
                                    </div>
                                    <div class="absolute top-4 left-4">
                                        <div class="w-5 h-5 rounded-full border-2 border-gray-300 peer-checked:border-secondary-orange peer-checked:bg-secondary-orange flex items-center justify-center">
                                            <div class="w-2 h-2 bg-white rounded-full"></div>
                                        </div>
                                    </div>
                                </div>
                            </label>

                            <label class="relative group cursor-pointer">
                                <input type="radio" v-model="form.payment_method" value="Card" class="peer sr-only">
                                <div class="p-5 border-2 border-gray-100 rounded-2xl group-hover:bg-gray-50 transition-all peer-checked:border-secondary-orange peer-checked:bg-orange-50/30 flex items-start space-x-4 space-x-reverse">
                                    <div class="p-3 bg-gray-100 rounded-xl peer-checked:bg-secondary-orange group-hover:scale-110 transition-transform">
                                        <svg class="h-6 w-6 text-gray-600 group-hover:text-secondary-orange peer-checked:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1 pt-0.5">
                                        <p class="font-bold text-gray-900">{{ t('Online Payment') }}</p>
                                        <p class="text-xs text-gray-500 mt-1">{{ t('Safe payment via Card or Apple Pay') }}</p>
                                    </div>
                                    <div class="absolute top-4 left-4">
                                        <div class="w-5 h-5 rounded-full border-2 border-gray-300 peer-checked:border-secondary-orange peer-checked:bg-secondary-orange flex items-center justify-center">
                                            <div class="w-2 h-2 bg-white rounded-full"></div>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                </section>

                <!-- Order Summary Section -->
                <section class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
                    <div class="px-8 py-6 border-b border-gray-50 flex justify-between items-center bg-primary-dark text-white">
                        <h2 class="text-xl font-bold">{{ t('Order Summary') }}</h2>
                    </div>
                    
                    <div class="p-0">
                        <!-- Coupon Section -->
                        <div class="px-8 py-6 bg-gray-50/50 border-y border-gray-50">
                            <label class="text-xs font-bold text-gray-700 uppercase tracking-widest block mb-3">{{ t('Promo Code') }}</label>
                            
                            <div class="flex gap-3">
                                <div class="flex-1 relative">
                                    <input 
                                        v-model="couponCode" 
                                        type="text" 
                                        :disabled="!!cartData.coupon_code"
                                        :class="[
                                            'block w-full px-4 py-3 border-gray-200 rounded-xl shadow-sm transition-all outline-none text-sm font-medium uppercase placeholder:normal-case',
                                            cartData.coupon_code ? 'bg-green-50 border-green-200 text-green-700 font-bold' : 'bg-white focus:ring-2 focus:ring-secondary-orange/20 focus:border-secondary-orange'
                                        ]"
                                        :placeholder="t('Enter code...')"
                                    >
                                    <div v-if="cartData.coupon_code" class="absolute inset-y-0 left-4 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                                        </svg>
                                    </div>
                                </div>
                                
                                <button 
                                    v-if="!cartData.coupon_code"
                                    @click="applyCoupon" 
                                    :disabled="couponLoading || !couponCode" 
                                    class="px-6 py-3 bg-primary-dark text-white rounded-xl font-bold text-sm hover:bg-gray-800 transition-all disabled:bg-gray-400 flex items-center shadow-md"
                                >
                                    <svg v-if="couponLoading" class="animate-spin h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    {{ t('Apply') }}
                                </button>
                                
                                <button 
                                    v-else
                                    @click="removeCoupon" 
                                    class="px-6 py-3 bg-red-500 text-white rounded-xl font-bold text-sm hover:bg-red-600 transition-all flex items-center gap-2 shadow-md"
                                >
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    {{ t('Remove') }}
                                </button>
                            </div>
                        </div>

                        <!-- Totals Breakdown -->
                        <div class="px-8 py-8 space-y-4">
                            <div class="flex justify-between text-sm font-medium">
                                <span class="text-gray-500">{{ t('Subtotal') }}</span>
                                <span class="text-gray-900">{{ (cartData.subtotal || 0).toFixed(2) }} {{ t(state.currency) }}</span>
                            </div>
                            
                            <div v-if="cartData.offer_discount > 0" class="flex justify-between text-sm font-medium">
                                <span class="text-gray-500">{{ t('Offers Savings') }}</span>
                                <span class="text-green-600 font-bold">-{{ (cartData.offer_discount || 0).toFixed(2) }} {{ t(state.currency) }}</span>
                            </div>

                            <div v-if="cartData.coupon_discount > 0" class="flex justify-between text-sm font-medium">
                                <span class="text-gray-500">{{ t('Promo Discount') }}</span>
                                <span class="text-green-600 font-bold">-{{ (cartData.coupon_discount || 0).toFixed(2) }} {{ t(state.currency) }}</span>
                            </div>

                            <div class="flex justify-between text-sm font-medium">
                                <span class="text-gray-500">{{ t('Shipping Fee') }}</span>
                                <span class="text-gray-900 font-bold">
                                    <span v-if="cartData.shipping_is_free" class="text-green-600 bg-green-50 px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-wider">{{ t('Free') }}</span>
                                    <span v-else>{{ (cartData.shipping_cost || 0).toFixed(2) }} {{ t(state.currency) }}</span>
                                </span>
                            </div>

                            <div class="pt-6 border-t border-gray-100 flex justify-between items-center">
                                <div>
                                    <span class="text-sm font-bold text-gray-400 uppercase tracking-widest block mb-1">{{ t('Total Amount') }}</span>
                                    <span class="text-3xl font-black text-primary-dark">{{ (cartData.total || 0).toFixed(2) }}</span>
                                    <span class="text-sm font-bold text-gray-500 mr-1 uppercase">{{ t(state.currency) }}</span>
                                </div>
                                <div class="text-left">
                                    <span v-if="cartData.total_discount > 0" class="inline-block bg-green-100 text-green-700 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-tighter">
                                        {{ tp('You saved {0}', [ (cartData.total_discount || 0).toFixed(2) + ' ' + t(state.currency) ]) }}
                                    </span>
                                </div>
                            </div>

                            <button @click="placeOrder" 
                                    :disabled="submitting || !form.shipping_zone_id"
                                    class="w-full mt-6 group flex items-center justify-center py-4 px-8 bg-secondary-orange text-white rounded-2xl font-black text-lg hover:bg-orange-600 transition-all transform hover:-translate-y-1 shadow-xl hover:shadow-orange-200 disabled:bg-gray-300 disabled:shadow-none disabled:transform-none disabled:cursor-not-allowed">
                                <template v-if="submitting">
                                     <svg class="animate-spin h-6 w-6 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                </template>
                                <template v-else>
                                    {{ t('Confirm Order') }}
                                    <svg class="w-5 h-5 ml-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                                </template>
                            </button>
                            <p v-if="!form.shipping_zone_id" class="text-[10px] text-red-500 mt-3 text-center font-bold animate-pulse">{{ t('Please select a shipping zone to enable checkout') }}</p>
                        </div>
                    </div>
                </section>
                
                <div class="px-8 text-center space-y-2">
                    <p class="text-[10px] text-gray-400 font-medium leading-relaxed">
                        {{ t('By clicking "Confirm Order", you agree to our Terms of Service and Privacy Policy.') }}
                    </p>
                    
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useI18n } from 'vue-i18n';
import { ref, onMounted, computed, reactive } from 'vue';
import { useCart } from '../../stores/cart';
import { useNotification } from '../../stores/notification';

const { t } = useI18n();

// Manual placeholder replacement helper to bypass i18n interpolation issues
const tp = (key, args) => {
    let msg = t(key);
    if (Array.isArray(args)) {
        args.forEach((val, idx) => {
            msg = msg.replace(`{${idx}}`, val);
        });
    }
    return msg;
};
const { state, refresh } = useCart();
const notify = useNotification();

const loading = ref(true);
const submitting = ref(false);
const couponLoading = ref(false);
const shippingZones = ref([]);
const cartData = ref({
    subtotal: 0,
    total: 0,
    shipping_cost: 0,
    total_discount: 0,
    offer_discount: 0,
    coupon_discount: 0,
    items: []
});
const couponCode = ref('');
const isShippingExpanded = ref(true); // Default to open for better UX or false if user prefers

const toggleShipping = () => {
    isShippingExpanded.value = !isShippingExpanded.value;
};

const form = reactive({
    name: '',
    phone: '',
    email: '',
    address: '',
    shipping_zone_id: '',
    payment_method: 'COD'
});

const getZoneName = (zone) => {
    if (typeof zone.name === 'object') {
        return zone.name[window.currentLocale || 'ar'] || zone.name['en'] || '';
    }
    return zone.name;
};

const showProgressBar = computed(() => {
    const threshold = cartData.value.free_shipping_threshold || 0;
    if (threshold <= 0 || cartData.value.shipping_is_free) return false;
    
    return true;
});

const progressPercent = computed(() => {
    if (!cartData.value.free_shipping_threshold) return 0;
    return Math.min(100, (cartData.value.subtotal / cartData.value.free_shipping_threshold) * 100);
});

const remainingForFree = computed(() => {
    const remaining = Math.max(0, (cartData.value.free_shipping_threshold || 0) - cartData.value.subtotal);
    return remaining.toFixed(2);
});

const loadInitialData = async () => {
    try {
        loading.value = true;
        await refresh();
        
        const response = await fetch('/cart-data');
        cartData.value = await response.json();
        
        if (cartData.value.coupon_code) {
           couponCode.value = cartData.value.coupon_code;
        }

        const zonesResponse = await fetch('/api/shipping-zones');
        shippingZones.value = await zonesResponse.json();

        if (window.authUser) {
            form.name = window.authUser.name;
            form.email = window.authUser.email;
            form.phone = window.authUser.phone || '';
        }
        
        if (cartData.value.selected_zone_id) {
            form.shipping_zone_id = cartData.value.selected_zone_id;
        }

    } catch (error) {
        console.error('Failed to load checkout data:', error);
    } finally {
        loading.value = false;
    }
};

const updateZone = async () => {
    try {
        const response = await fetch('/cart/update-zone', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ zone_id: form.shipping_zone_id })
        });
        cartData.value = await response.json();
    } catch (error) {
        console.error('Failed to update zone:', error);
    }
};

const changeQty = async (item, delta) => {
    if (item.is_free) return;
    const newQty = item.quantity + delta;
    if (newQty < 1) return;

    try {
        const response = await fetch(`/cart/${item.id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ quantity: newQty })
        });
        
        if (!response.ok) {
            const err = await response.json();
            notify.error(err.message || 'Error updating cart');
            return;
        }

        cartData.value = await response.json();
        state.items = cartData.value.items;
        state.count = state.items.reduce((acc, i) => acc + i.quantity, 0);

    } catch (error) {
        console.error('Failed to update quantity:', error);
    }
};

const removeItem = async (item) => {
    try {
        const response = await fetch(`/cart/${item.id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        });
        
        if (!response.ok) {
            const err = await response.json();
            notify.error(err.message || 'Error removing item');
            return;
        }

        cartData.value = await response.json();
        state.items = cartData.value.items;
        state.count = state.items.reduce((acc, i) => acc + i.quantity, 0);
        notify.success(t('Item removed'));

    } catch (error) {
        console.error('Failed to remove item:', error);
    }
};

const applyCoupon = async () => {
    if (!couponCode.value) return;
    try {
        couponLoading.value = true;
        const response = await fetch('/cart/coupon', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ code: couponCode.value })
        });
        
        const data = await response.json();
        if (!response.ok) {
            notify.error(data.message || 'Invalid coupon');
            return;
        }
        
        cartData.value = data;
        // Keep the code in the input field
        notify.success(t('Coupon applied!'));
    } catch (error) {
        notify.error(t('Failed to apply coupon'));
    } finally {
        couponLoading.value = false;
    }
};

const removeCoupon = async () => {
    try {
        const response = await fetch('/cart/coupon', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        });
        
        cartData.value = await response.json();
        couponCode.value = ''; // Clear the input field
        notify.success(t('Coupon removed'));
    } catch (error) {
        notify.error(t('Failed to remove coupon'));
    }
};

const placeOrder = async () => {
    if (!form.name || !form.phone || !form.address || !form.shipping_zone_id) {
        notify.error(t('Please fill all required fields'));
        return;
    }

    try {
        submitting.value = true;
        const response = await fetch('/checkout', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify(form)
        });

        const data = await response.json();
        if (!response.ok) {
            notify.error(data.message || t('Failed to place order'));
            return;
        }

        if (data.redirect_url) {
            window.location.href = data.redirect_url;
        } else {
            window.location.href = '/my-account';
        }

    } catch (error) {
        notify.error(t('Something went wrong. Please try again.'));
    } finally {
        submitting.value = false;
    }
};

const addToCart = async (product) => {
    try {
        loading.value = true;
        const response = await fetch('/cart', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ product_id: product.id, quantity: 1 })
        });
        
        if (!response.ok) {
            notify.error(t('Failed to add item'));
            loading.value = false;
            return;
        }

        notify.success(t('Added to cart!'));
        // Reload cart data to reflect changes
        await loadInitialData(); // Using existing function to refresh everything

    } catch (error) {
        notify.error(t('Error adding item'));
        loading.value = false;
    }
};

onMounted(loadInitialData);
</script>

<style scoped>
.list-enter-active, .list-leave-active {
  transition: all 0.5s ease;
}
.list-enter-from, .list-leave-to {
  opacity: 0;
  transform: translateX(30px);
}

.no-scrollbar::-webkit-scrollbar {
    display: none;
}
.no-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}

@keyframes flash {
    0% { background-color: rgba(34, 197, 94, 0.2); }
    100% { background-color: rgba(240, 253, 244, 1); }
}
.animate-flash {
    animation: flash 1s ease-out;
}
</style>
