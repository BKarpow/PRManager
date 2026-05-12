import 'bootstrap';

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// import Pusher from 'pusher-js';
// window.Pusher = Pusher;

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: import.meta.env.VITE_PUSHER_APP_KEY,
//     cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'mt1',
//     wsHost: import.meta.env.VITE_PUSHER_HOST ?? `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
//     wsPort: import.meta.env.VITE_PUSHER_PORT ?? 80,
//     wssPort: import.meta.env.VITE_PUSHER_PORT ?? 443,
//     forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https',
//     enabledTransports: ['ws', 'wss'],
// });

window.getDaysDifferenceFromToday = function (inputDate) {
    // Розбиваємо вхідну дату на частини
    const parts = inputDate.split('.');

    // Перевіряємо коректність формату
    if (parts.length !== 3) {
        throw new Error('Невірний формат дати. Використовуйте дд.мм.рррр');
    }

    const day = parseInt(parts[0], 10);
    const month = parseInt(parts[1], 10) - 1; // Місяці в JS з 0 до 11
    const year = parseInt(parts[2], 10);

    // Перевіряємо коректність чисел
    if (isNaN(day) || isNaN(month) || isNaN(year)) {
        throw new Error('Дата містить некоректні числа');
    }

    // Створюємо об'єкт Date для вхідної дати
    const inputDateObj = new Date(year, month, day);

    // Перевіряємо коректність створеної дати
    if (inputDateObj.getDate() !== day ||
        inputDateObj.getMonth() !== month ||
        inputDateObj.getFullYear() !== year) {
        throw new Error('Невірна дата');
    }

    // Поточна дата (без часу)
    const today = new Date();
    today.setHours(0, 0, 0, 0);

    // Вхідна дата (без часу)
    inputDateObj.setHours(0, 0, 0, 0);

    // Різниця в мілісекундах
    const differenceMs = inputDateObj - today;

    // Конвертуємо в дні
    const differenceDays = Math.round(differenceMs / (1000 * 60 * 60 * 24));

    return differenceDays;
}

window.hexHash = (str) => {
    let hash = 0;
    for (let i = 0; i < str.length; i++) {
        hash = ((hash << 5) - hash) + str.charCodeAt(i);
        hash |= 0;
    }
    return (hash >>> 0).toString(16); // Конвертуємо в беззнакове і в HEX
};

window.beep =  (duration = 400, frequency = 1750, volume = 0.3) => {
    // Створюємо контекст аудіо
    const audioCtx = new (window.AudioContext || window.webkitAudioContext)();

    // Створюємо осцилятор (генератор звуку)
    const oscillator = audioCtx.createOscillator();
    // Створюємо вузол гучності (щоб уникнути клацання в кінці)
    const gainNode = audioCtx.createGain();

    oscillator.connect(gainNode);
    gainNode.connect(audioCtx.destination);

    // Налаштування
    oscillator.type = 'sine'; // Тип хвилі: 'sine', 'square', 'sawtooth', 'triangle'
    oscillator.frequency.value = frequency; // Частота в Герцах (800-1000 - типовий сканер)

    // Плавне затухання гучності, щоб звук не "обривався" різко
    gainNode.gain.setValueAtTime(volume, audioCtx.currentTime);
    gainNode.gain.exponentialRampToValueAtTime(0.00001, audioCtx.currentTime + duration / 1000);

    oscillator.start(audioCtx.currentTime);
    oscillator.stop(audioCtx.currentTime + duration / 1000);
};
