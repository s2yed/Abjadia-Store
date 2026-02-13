import { reactive } from 'vue';

export const notifications = reactive([]);

export const useNotification = () => {
    const add = (message, type = 'success', duration = 3000) => {
        const id = Date.now();
        notifications.push({ id, message, type });

        if (duration > 0) {
            setTimeout(() => {
                remove(id);
            }, duration);
        }
    };

    const remove = (id) => {
        const index = notifications.findIndex(n => n.id === id);
        if (index !== -1) {
            notifications.splice(index, 1);
        }
    };

    return {
        notifications,
        success: (msg, duration) => add(msg, 'success', duration),
        error: (msg, duration) => add(msg, 'error', duration),
        info: (msg, duration) => add(msg, 'info', duration),
        remove
    };
};
