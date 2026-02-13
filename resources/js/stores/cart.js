import { reactive, computed } from 'vue';

const state = reactive({
    count: 0,
    isOpen: false,
    items: [],
    subtotal: 0,
    currency: 'SAR'
});

export const useCart = () => {
    const increment = () => {
        state.count++;
    };

    const toggle = () => {
        state.isOpen = !state.isOpen;
    };

    const open = () => {
        state.isOpen = true;
    };

    const close = () => {
        state.isOpen = false;
    };

    const setCount = (val) => {
        state.count = val;
    };

    const refresh = async () => {
        try {
            const response = await fetch('/cart-data', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            const data = await response.json();
            state.items = data.items || [];
            state.count = state.items.reduce((acc, item) => acc + item.quantity, 0);
            state.subtotal = data.subtotal;
            state.currency = document.querySelector('meta[name="currency"]')?.getAttribute('content') || 'SAR';
        } catch (error) {
            console.error('Failed to refresh cart:', error);
        }
    };

    return {
        state,
        increment,
        toggle,
        open,
        close,
        setCount,
        refresh
    };
};
