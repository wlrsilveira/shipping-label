import Pusher from 'pusher-js';

let pusherInstance = null;

export function usePusher() {
    if (!pusherInstance && import.meta.env.VITE_PUSHER_APP_KEY) {
        pusherInstance = new Pusher(import.meta.env.VITE_PUSHER_APP_KEY, {
            cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER || 'mt1',
            encrypted: true,
            authEndpoint: '/broadcasting/auth',
            auth: {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                },
            },
        });
    }

    return pusherInstance;
}

export function disconnectPusher() {
    if (pusherInstance) {
        pusherInstance.disconnect();
        pusherInstance = null;
    }
}

