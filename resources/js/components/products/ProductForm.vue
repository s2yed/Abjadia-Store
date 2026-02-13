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
                        <label class="block text-sm font-medium text-gray-700">{{ $t('type') }}</label>
                        <select
                            v-model="form.type"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-secondary-orange focus:ring-secondary-orange sm:text-sm border p-2"
                        >
                            <option value="book">{{ $t('book') }}</option>
                            <option value="supply">{{ $t('supply') }}</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ $t('category') }}</label>
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
                                {{ getTrans(cat.name) }}
                            </option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ $t('price_sar') }}</label>
                        <input
                            v-model="form.price"
                            type="number"
                            step="0.01"
                            required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-secondary-orange focus:ring-secondary-orange sm:text-sm border p-2"
                        />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ $t('discount_price') }}</label>
                        <input
                            v-model="form.discount_price"
                            type="number"
                            step="0.01"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-secondary-orange focus:ring-secondary-orange sm:text-sm border p-2"
                        />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ $t('stock_quantity') }}</label>
                        <input
                            v-model="form.stock"
                            type="number"
                            required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-secondary-orange focus:ring-secondary-orange sm:text-sm border p-2"
                        />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ $t('weight_kg') }}</label>
                        <input
                            v-model="form.weight"
                            type="number"
                            step="0.01"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-secondary-orange focus:ring-secondary-orange sm:text-sm border p-2"
                        />
                    </div>
                </div>

                <!-- Type Specific & Description -->
                <div class="space-y-4">
                    <div
                        v-if="form.type === 'book'"
                        class="bg-blue-50 p-4 rounded-md space-y-4"
                    >
                        <h3 class="font-medium text-blue-900">{{ $t('book_details') }}</h3>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ $t('isbn') }}</label>
                            <input
                                v-model="form.isbn"
                                type="text"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-secondary-orange focus:ring-secondary-orange sm:text-sm border p-2"
                            />
                        </div>

                        <!-- Authors Multi-select -->
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <label class="block text-sm font-medium text-gray-700">{{ $t('authors') }}</label>
                                <button type="button" @click.stop="openQuickAdd('author')" class="text-xs text-secondary-orange hover:underline">+ {{ $t('add_new') }}</button>
                            </div>
                            <select
                                v-model="form.authors"
                                multiple
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-secondary-orange focus:ring-secondary-orange sm:text-sm border p-2 h-32"
                            >
                                <option
                                    v-for="author in authors"
                                    :key="author.id"
                                    :value="author.id"
                                >
                                    {{ getTrans(author.name) }}
                                </option>
                            </select>
                            <p class="text-xs text-gray-500 mt-1">{{ $t('hold_ctrl_multiselect') }}</p>
                        </div>

                        <!-- Publisher Single-select -->
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <label class="block text-sm font-medium text-gray-700">{{ $t('publisher') }}</label>
                                <button type="button" @click.stop="openQuickAdd('publisher')" class="text-xs text-secondary-orange hover:underline">+ {{ $t('add_new') }}</button>
                            </div>
                            <select
                                v-model="form.publisher_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-secondary-orange focus:ring-secondary-orange sm:text-sm border p-2"
                            >
                                <option :value="null">{{ $t('select_publisher') }}</option>
                                <option
                                    v-for="publisher in publishers"
                                    :key="publisher.id"
                                    :value="publisher.id"
                                >
                                    {{ getTrans(publisher.name) }}
                                </option>
                            </select>
                        </div>

                        <!-- Translators Multi-select -->
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <label class="block text-sm font-medium text-gray-700">{{ $t('translators') }}</label>
                                <button type="button" @click.stop="openQuickAdd('translator')" class="text-xs text-secondary-orange hover:underline">+ {{ $t('add_new') }}</button>
                            </div>
                            <select
                                v-model="form.translators"
                                multiple
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-secondary-orange focus:ring-secondary-orange sm:text-sm border p-2 h-24"
                            >
                                <option
                                    v-for="translator in translators"
                                    :key="translator.id"
                                    :value="translator.id"
                                >
                                    {{ getTrans(translator.name) }}
                                </option>
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">{{ $t('pages') }}</label>
                                <input
                                    v-model="form.pages"
                                    type="number"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-secondary-orange focus:ring-secondary-orange sm:text-sm border p-2"
                                />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">{{ $t('publication_year') }}</label>
                                <input
                                    v-model="form.publication_year"
                                    type="number"
                                    placeholder="2024"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-secondary-orange focus:ring-secondary-orange sm:text-sm border p-2"
                                />
                            </div>
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
                            <label class="block text-sm font-medium text-gray-700">{{ $t('brand') }}</label>
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

            <!-- SEO Settings -->
            <div class="mt-8 border-t border-gray-200 pt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-secondary-orange" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.450 1.348l.41.411-2.454 2.454L7.541 5.405a1 1 0 00-1.414 0l-4 4a1 1 0 101.414 1.414L7.126 7.235l1.36 1.36a1 1 0 001.414 0l3.033-3.034.41.41a1 1 0 001.348-1.45l-1.93-1.93a1 1 0 001.36-1.414z" clip-rule="evenodd" />
                    </svg>
                    {{ $t('seo_settings') }}
                </h3>
                <p class="text-xs text-gray-500 mb-4">{{ $t('seo_help') }}</p>

                <div class="space-y-6">
                    <!-- SEO Title -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ $t('seo_title_en') }}</label>
                            <input
                                v-model="form.seo_title.en"
                                type="text"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-secondary-orange focus:ring-secondary-orange sm:text-sm border p-2"
                            />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 rtl:text-right" dir="auto">{{ $t('seo_title_ar') }}</label>
                            <input
                                v-model="form.seo_title.ar"
                                type="text"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-secondary-orange focus:ring-secondary-orange sm:text-sm border p-2 rtl:text-right"
                                dir="rtl"
                            />
                        </div>
                    </div>

                    <!-- SEO Description -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ $t('seo_description_en') }}</label>
                            <textarea
                                v-model="form.seo_description.en"
                                rows="2"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-secondary-orange focus:ring-secondary-orange sm:text-sm border p-2"
                            ></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 rtl:text-right" dir="auto">{{ $t('seo_description_ar') }}</label>
                            <textarea
                                v-model="form.seo_description.ar"
                                rows="2"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-secondary-orange focus:ring-secondary-orange sm:text-sm border p-2 rtl:text-right"
                                dir="rtl"
                            ></textarea>
                        </div>
                    </div>

                    <!-- SEO Keywords -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ $t('seo_keywords_en') }}</label>
                            <div class="mt-1 flex flex-wrap gap-2 p-2 border rounded-md border-gray-300 bg-white focus-within:ring-secondary-orange focus-within:border-secondary-orange shadow-sm min-h-[42px]">
                                <span v-for="(tag, index) in keywordsEn" :key="index" 
                                      class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ tag }}
                                    <button @click="removeTag('en', index)" type="button" class="ml-1.5 inline-flex flex-shrink-0 h-4 w-4 rounded-full items-center justify-center text-blue-400 hover:bg-blue-200 hover:text-blue-500 focus:outline-none focus:bg-blue-500 focus:text-white">
                                        <svg class="h-2 w-2" stroke="currentColor" fill="none" viewBox="0 0 8 8">
                                            <path stroke-linecap="round" stroke-width="1.5" d="M1 1l6 6m0-6L1 7" />
                                        </svg>
                                    </button>
                                </span>
                                <input
                                    v-model="keywordInputEn"
                                    type="text"
                                    :placeholder="keywordsEn.length === 0 ? 'keyword1, keyword2...' : ''"
                                    @keydown="handleKeydown($event, 'en')"
                                    @blur="addTag('en')"
                                    class="flex-1 min-w-[120px] outline-none border-none focus:ring-0 p-0 text-sm"
                                />
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 rtl:text-right" dir="auto">{{ $t('seo_keywords_ar') }}</label>
                            <div class="mt-1 flex flex-wrap gap-2 p-2 border rounded-md border-gray-300 bg-white focus-within:ring-secondary-orange focus-within:border-secondary-orange shadow-sm min-h-[42px] rtl:flex-row-reverse" dir="rtl">
                                <span v-for="(tag, index) in keywordsAr" :key="index" 
                                      class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    {{ tag }}
                                    <button @click="removeTag('ar', index)" type="button" class="mx-1.5 inline-flex flex-shrink-0 h-4 w-4 rounded-full items-center justify-center text-green-400 hover:bg-green-200 hover:text-green-500 focus:outline-none focus:bg-green-500 focus:text-white">
                                        <svg class="h-2 w-2" stroke="currentColor" fill="none" viewBox="0 0 8 8">
                                            <path stroke-linecap="round" stroke-width="1.5" d="M1 1l6 6m0-6L1 7" />
                                        </svg>
                                    </button>
                                </span>
                                <input
                                    v-model="keywordInputAr"
                                    type="text"
                                    :placeholder="keywordsAr.length === 0 ? 'كلمة1, كلمة2...' : ''"
                                    @keydown="handleKeydown($event, 'ar')"
                                    @blur="addTag('ar')"
                                    class="flex-1 min-w-[120px] outline-none border-none focus:ring-0 p-0 text-sm rtl:text-right"
                                />
                            </div>
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

        <!-- Quick Add Modal -->
        <div v-if="showQuickAddModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click.stop="showQuickAddModal = false"></div>
            
            <div class="bg-white rounded-lg shadow-xl max-w-lg w-full z-10 overflow-hidden rtl:text-right" :dir="locale === 'ar' ? 'rtl' : 'ltr'">
                <form @submit.prevent="submitQuickAdd">
                    <div class="px-4 pt-5 pb-4 sm:p-6 pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                            {{ t('add_new') }} {{ t(quickAddType) || quickAddType }}
                        </h3>
                        <div class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-left rtl:text-right">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ t('name_en') }}</label>
                                    <input v-model="quickAddForm.name.en" type="text" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 text-sm" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ t('name_ar') }}</label>
                                    <input v-model="quickAddForm.name.ar" type="text" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 text-sm" dir="rtl" />
                                </div>
                            </div>
                            <div class="text-left rtl:text-right">
                                <label class="block text-sm font-medium text-gray-700">{{ t('bio_en') }}</label>
                                <textarea v-model="quickAddForm.bio.en" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 text-sm"></textarea>
                            </div>
                            <div class="text-left rtl:text-right">
                                <label class="block text-sm font-medium text-gray-700">{{ t('bio_ar') }}</label>
                                <textarea v-model="quickAddForm.bio.ar" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 text-sm" dir="rtl"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 flex justify-end gap-3 rtl:flex-row-reverse">
                        <button type="button" @click.stop="showQuickAddModal = false" class="inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-orange">
                            {{ t('cancel') }}
                        </button>
                        <button type="submit" class="inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-secondary-orange text-sm font-medium text-white hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-orange">
                            {{ t('save') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import { useRoute, useRouter } from "vue-router";
import { ref, onMounted, computed, reactive } from "vue";
import { useI18n } from 'vue-i18n';

export default {
    setup() {
        const { t, locale } = useI18n();
        const route = useRoute();
        const router = useRouter();
        const isEdit = computed(() => !!route.params.id);
        const submitting = ref(false);
        const error = ref(null);

        const getTrans = (val) => {
            if (!val) return "";
            if (typeof val === 'object') {
                return val[locale.value] || val.en || "";
            }
            return val;
        };

        const form = reactive({
            name: { en: "", ar: "" },
            slug: "",
            type: "book",
            category_id: null,
            price: "",
            discount_price: "",
            stock: 0,
            weight: 0,
            description: { en: "", ar: "" },
            seo_title: { en: "", ar: "" },
            seo_description: { en: "", ar: "" },
            seo_keywords: { en: "", ar: "" },
            // Book specifics
            isbn: "",
            publisher_id: null,
            pages: "",
            authors: [],
            translators: [],
            publication_year: "",
            // Supply specifics
            brand_id: null,
            image: null,
        });

        const keywordInputEn = ref("");
        const keywordInputAr = ref("");

        const keywordsEn = computed({
            get: () => form.seo_keywords.en ? form.seo_keywords.en.split(',').map(s => s.trim()).filter(s => s) : [],
            set: (val) => form.seo_keywords.en = val.join(', ')
        });

        const keywordsAr = computed({
            get: () => form.seo_keywords.ar ? form.seo_keywords.ar.split(',').map(s => s.trim()).filter(s => s) : [],
            set: (val) => form.seo_keywords.ar = val.join(', ')
        });

        const addTag = (locale) => {
            const input = locale === 'en' ? keywordInputEn : keywordInputAr;
            const keywords = locale === 'en' ? keywordsEn : keywordsAr;
            
            let val = input.value.trim().replace(/,$/, '');
            if (val && !keywords.value.includes(val)) {
                keywords.value = [...keywords.value, val];
            }
            input.value = "";
        };

        const removeTag = (locale, index) => {
            const keywords = locale === 'en' ? keywordsEn : keywordsAr;
            const updated = [...keywords.value];
            updated.splice(index, 1);
            keywords.value = updated;
        };

        const handleKeydown = (e, locale) => {
            if (e.key === ',' || e.key === 'Enter') {
                e.preventDefault();
                addTag(locale);
            }
        };

        const categories = ref([]);
        const brands = ref([]);
        const authors = ref([]);
        const publishers = ref([]);
        const translators = ref([]);
        const previewImage = ref(null);

        const showQuickAddModal = ref(false);
        const quickAddType = ref(''); // 'author', 'publisher', 'translator'
        const quickAddForm = reactive({
            name: { en: '', ar: '' },
            bio: { en: '', ar: '' }
        });

        const handleImageUpload = (event) => {
            const file = event.target.files[0];
            if (file) {
                form.image = file;
                previewImage.value = URL.createObjectURL(file);
            }
        };

        const fetchOptions = async () => {
            try {
                const [catRes, brandRes, authorRes, publisherRes, translatorRes] = await Promise.all([
                    axios.get("/api/categories"),
                    axios.get("/api/brands"),
                    axios.get("/api/authors"),
                    axios.get("/api/publishers"),
                    axios.get("/api/translators"),
                ]);
                categories.value = catRes.data;
                brands.value = brandRes.data;
                authors.value = authorRes.data;
                publishers.value = publisherRes.data;
                translators.value = translatorRes.data;
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
                data.seo_title = data.seo_title || { en: "", ar: "" };
                data.seo_description = data.seo_description || { en: "", ar: "" };
                data.seo_keywords = data.seo_keywords || { en: "", ar: "" };
                
                Object.assign(form, data);
                
                // Map relationships to IDs for the form
                if (data.authors) {
                    form.authors = data.authors.map(a => a.id);
                }
                if (data.translators) {
                    form.translators = data.translators.map(t => t.id);
                }
                if (data.publisher) {
                    form.publisher_id = data.publisher.id;
                }

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

            // Handle SEO Translations
            ['seo_title', 'seo_description', 'seo_keywords'].forEach(field => {
                if (form[field].en) formData.append(`${field}[en]`, form[field].en);
                if (form[field].ar) formData.append(`${field}[ar]`, form[field].ar);
            });

            // Handle Regular Fields
            const regularFields = ['slug', 'type', 'category_id', 'price', 'weight', 'discount_price', 'stock', 'isbn', 'publisher_id', 'pages', 'brand_id', 'publication_year'];
            regularFields.forEach(field => {
                if (form[field] !== null && form[field] !== undefined && form[field] !== "") {
                     formData.append(field, form[field]);
                } else if (isEdit.value && (form[field] === "" || form[field] === null)) {
                    // Send empty string for optional fields to facilitate clearing them in DB if needed
                    // (Note: Backend validation 'nullable' handles this)
                    formData.append(field, "");
                }
            });

            // Handle Many-to-Many
            form.authors.forEach(id => formData.append('authors[]', id));
            form.translators.forEach(id => formData.append('translators[]', id));

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

        const openQuickAdd = (type) => {
            quickAddType.value = type;
            quickAddForm.name = { en: '', ar: '' };
            quickAddForm.bio = { en: '', ar: '' };
            showQuickAddModal.value = true;
        };

        const submitQuickAdd = async () => {
            try {
                const endpoint = `/api/${quickAddType.value}s`;
                const { data } = await axios.post(endpoint, quickAddForm);
                
                // Refresh options
                if (quickAddType.value === 'author') {
                    authors.value.push(data);
                    form.authors.push(data.id);
                } else if (quickAddType.value === 'publisher') {
                    publishers.value.push(data);
                    form.publisher_id = data.id;
                } else if (quickAddType.value === 'translator') {
                    translators.value.push(data);
                    form.translators.push(data.id);
                }
                
                showQuickAddModal.value = false;
            } catch (err) {
                console.error("Failed to quick add", err);
                alert("Failed to add new item. Please check the form.");
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
            authors,
            publishers,
            translators,
            showQuickAddModal,
            quickAddType,
            quickAddForm,
            openQuickAdd,
            submitQuickAdd,
            submitForm,
            previewImage,
            handleImageUpload,
            keywordsEn,
            keywordsAr,
            keywordInputEn,
            keywordInputAr,
            removeTag,
            handleKeydown,
            addTag,
            getTrans,
            t,
            locale,
            router,
            route
        };
    },
};
</script>

<style scoped>
.bg-secondary-orange {
    background-color: #e67e22;
}
</style>
