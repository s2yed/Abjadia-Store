<template>
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-800">
                {{ isEdit ? $t('edit_product') : $t('add_new_product') }}
            </h2>
            <router-link
                to="/dashboard/products"
                class="text-gray-600 hover:text-gray-900"
            >
                <span class="rtl:hidden">&larr;</span> {{ $t('back_to_list') }} <span class="hidden rtl:inline">&rarr;</span>
            </router-link>
        </div>

        <!-- Error Alert -->
        <div
            v-if="error"
            class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4"
        >
            {{ error }}
        </div>

        <form @submit.prevent="submitForm">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Basic Info -->
                <!-- Basic Info -->
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ $t('product_name_en') }}</label>
                            <input
                                v-model="form.name.en"
                                type="text"
                                required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-secondary-orange focus:ring-secondary-orange sm:text-sm border p-2"
                            />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 rtl:text-right" dir="auto">{{ $t('product_name_ar') }}</label>
                            <input
                                v-model="form.name.ar"
                                type="text"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-secondary-orange focus:ring-secondary-orange sm:text-sm border p-2 rtl:text-right"
                                dir="rtl"
                            />
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ $t('product_image') }}</label>
                        <div v-if="previewImage" class="mb-2">
                             <img :src="previewImage" class="h-32 w-32 object-cover rounded border" />
                        </div>
                        <input
                            type="file"
                            @change="handleImageUpload"
                            accept="image/*"
                            class="mt-1 block w-full text-sm text-gray-500
                                file:mr-4 rtl:file:mr-0 rtl:file:ml-4 file:py-2 file:px-4
                                file:rounded-md file:border-0
                                file:text-sm file:font-semibold
                                file:bg-blue-50 file:text-blue-700
                                hover:file:bg-blue-100"
                        />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700"
                            >{{ $t('type') }}</label
                        >
                        <select
                            v-model="form.type"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-secondary-orange focus:ring-secondary-orange sm:text-sm border p-2"
                        >
                            <option value="book">{{ $t('book') }}</option>
                            <option value="supply">{{ $t('supply') }}</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700"
                            >{{ $t('category') }}</label
                        >
                        <select
                            v-model="form.category_id"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-secondary-orange focus:ring-secondary-orange sm:text-sm border p-2"
                        >
                            <option :value="null">{{ $t('select_category') }}</option>
                            <option
                                v-for="cat in categories"
                                :key="cat.id"
                                :value="cat.id"
                            >
                                {{ typeof cat.name === 'object' ? cat.name[$i18n.locale] || cat.name.en : cat.name }}
                            </option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700"
                            >{{ $t('price_sar') }}</label
                        >
                        <input
                            v-model="form.price"
                            type="number"
                            step="0.01"
                            required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-secondary-orange focus:ring-secondary-orange sm:text-sm border p-2"
                        />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700"
                            >{{ $t('discount_price') }}</label
                        >
                        <input
                            v-model="form.discount_price"
                            type="number"
                            step="0.01"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-secondary-orange focus:ring-secondary-orange sm:text-sm border p-2"
                        />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700"
                            >{{ $t('stock_quantity') }}</label
                        >
                        <input
                            v-model="form.stock"
                            type="number"
                            required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-secondary-orange focus:ring-secondary-orange sm:text-sm border p-2"
                        />
                    </div>
                </div>

                <!-- Type Specific & Description -->
                <div class="space-y-4">
                    <!-- Book Fields -->
                    <div
                        v-if="form.type === 'book'"
                        class="bg-blue-50 p-4 rounded-md space-y-4"
                    >
                        <h3 class="font-medium text-blue-900">{{ $t('book_details') }}</h3>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700"
                                >{{ $t('isbn') }}</label
                            >
                            <input
                                v-model="form.isbn"
                                type="text"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-secondary-orange focus:ring-secondary-orange sm:text-sm border p-2"
                            />
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700"
                                >{{ $t('publisher') }}</label
                            >
                            <input
                                v-model="form.publisher"
                                type="text"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-secondary-orange focus:ring-secondary-orange sm:text-sm border p-2"
                            />
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700"
                                >{{ $t('pages') }}</label
                            >
                            <input
                                v-model="form.pages"
                                type="number"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-secondary-orange focus:ring-secondary-orange sm:text-sm border p-2"
                            />
                        </div>
                    </div>

                    <!-- Supply Fields -->
                    <div
                        v-if="form.type === 'supply'"
                        class="bg-green-50 p-4 rounded-md space-y-4"
                    >
                        <h3 class="font-medium text-green-900">
                            {{ $t('supply_details') }}
                        </h3>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700"
                                >{{ $t('brand') }}</label
                            >
                            <select
                                v-model="form.brand_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-secondary-orange focus:ring-secondary-orange sm:text-sm border p-2"
                            >
                                <option :value="null">{{ $t('select_brand') }}</option>
                                <option
                                    v-for="brand in brands"
                                    :key="brand.id"
                                    :value="brand.id"
                                >
                                    {{ brand.name }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ $t('description_en') }}</label>
                            <textarea
                                v-model="form.description.en"
                                rows="4"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-secondary-orange focus:ring-secondary-orange sm:text-sm border p-2"
                            ></textarea>
                        </div>
                        <div>
                             <label class="block text-sm font-medium text-gray-700 rtl:text-right" dir="auto">{{ $t('description_ar') }}</label>
                            <textarea
                                v-model="form.description.ar"
                                rows="4"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-secondary-orange focus:ring-secondary-orange sm:text-sm border p-2 rtl:text-right"
                                dir="rtl"
                            ></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex justify-end">
                <button
                    type="button"
                    @click="$router.push('/dashboard/products')"
                    class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 mr-4 rtl:mr-0 rtl:ml-4"
                >
                    {{ $t('cancel') }}
                </button>
                <button
                    type="submit"
                    :disabled="submitting"
                    class="bg-secondary-orange py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-orange disabled:opacity-50"
                >
                    {{
                        submitting
                            ? $t('saving')
                            : isEdit
                            ? $t('update')
                            : $t('save')
                    }}
                </button>
            </div>
        </form>
    </div>
</template>

<script>
import axios from "axios";
import { useRoute, useRouter } from "vue-router";
import { ref, onMounted, computed, reactive } from "vue";
import { useI18n } from 'vue-i18n';

export default {
    setup() {
        const { t } = useI18n();
        const route = useRoute();
        const router = useRouter();
        const isEdit = computed(() => !!route.params.id);
        const submitting = ref(false);
        const error = ref(null);

        const form = reactive({
            name: { en: "", ar: "" },
            slug: "",
            type: "book",
            category_id: null,
            price: "",
            discount_price: "",
            stock: 0,
            description: { en: "", ar: "" },
            // Book specifics
            isbn: "",
            publisher: "",
            pages: "",
            // Supply specifics
            brand_id: null,
            image: null,
        });

        const categories = ref([]);
        const brands = ref([]);
        const previewImage = ref(null);

        const handleImageUpload = (event) => {
            const file = event.target.files[0];
            if (file) {
                form.image = file;
                previewImage.value = URL.createObjectURL(file);
            }
        };

        const fetchOptions = async () => {
            try {
                const [catRes, brandRes] = await Promise.all([
                    axios.get("/api/categories"),
                    axios.get("/api/brands"),
                ]);
                categories.value = catRes.data;
                brands.value = brandRes.data;
            } catch (err) {
                console.error("Failed to load options", err);
            }
        };

        const fetchProduct = async () => {
            if (!isEdit.value) return;
            try {
                const { data } = await axios.get(
                    `/api/products/${route.params.id}`
                );
                
                // Handle translations
                data.name = data.name || { en: "", ar: "" };
                data.description = data.description || { en: "", ar: "" };
                
                Object.assign(form, data);
                if (data.image) {
                     // Check if full URL or relative path
                     previewImage.value = data.image.startsWith('http') ? data.image : `/storage/${data.image}`;
                }
            } catch (err) {
                error.value = t('failed_load');
            }
        };

        const submitForm = async () => {
            submitting.value = true;
            error.value = null;

            const formData = new FormData();
            
            // Handle Translatable Fields
            formData.append('name[en]', form.name.en);
            if (form.name.ar) formData.append('name[ar]', form.name.ar);
            
            if (form.description.en) formData.append('description[en]', form.description.en);
            if (form.description.ar) formData.append('description[ar]', form.description.ar);

            // Handle Regular Fields
            const regularFields = ['slug', 'type', 'category_id', 'price', 'discount_price', 'stock', 'isbn', 'publisher', 'pages', 'brand_id'];
            regularFields.forEach(field => {
                if (form[field] !== null && form[field] !== "") {
                     formData.append(field, form[field]);
                }
            });

            if (form.image instanceof File) {
                formData.append('image', form.image);
            }

            if (isEdit.value) {
                formData.append('_method', 'PUT'); // Method spoofing for Laravel multipart/form-data
            }

            try {
                if (isEdit.value) {
                    await axios.post(`/api/products/${route.params.id}`, formData, {
                        headers: { 'Content-Type': 'multipart/form-data' }
                    });
                } else {
                    await axios.post("/api/products", formData, {
                        headers: { 'Content-Type': 'multipart/form-data' }
                    });
                }
                router.push("/dashboard/products");
            } catch (err) {
                error.value =
                    err.response?.data?.message ||
                    t('failed_save');
                console.error(err);
            } finally {
                submitting.value = false;
            }
        };

        onMounted(() => {
            fetchOptions();
            fetchProduct();
        });

        return {
            form,
            isEdit,
            submitting,
            error,
            categories,
            brands,
            submitForm,
            previewImage,
            handleImageUpload
        };
    },
};
</script>

<style scoped>
.bg-secondary-orange {
    background-color: #e67e22;
}
</style>
