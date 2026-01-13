import { createI18n } from 'vue-i18n';
import en from './locales/en.json';
import ar from './locales/ar.json';

// Get locale from document lang attribute or default to 'en'
const locale = document.documentElement.lang || 'ar';

const i18n = createI18n({
    legacy: false, // Use Composition API mode
    locale: locale,
    fallbackLocale: 'en',
    messages: {
        en,
        ar
    }
});

export default i18n;
