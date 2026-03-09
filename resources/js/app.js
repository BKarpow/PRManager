/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';
import { createApp } from 'vue';
import 'cropperjs/dist/cropper.min.js';
import VCalendar from 'v-calendar';
import 'v-calendar/dist/style.css';


/**
 * Next, we will create a fresh Vue application instance. You may then begin
 * registering components with the application instance so they are ready
 * to use in your application's views. An example is included for you.
 */

const app = createApp({});

import ExampleComponent from './components/ExampleComponent.vue';
import ImageUploader from './components/ImageUploader.vue';
import InputBarcode from './components/InputBarcode.vue';
import CreateNewProduct from './components/CreateNewProduct.vue';
import ImageModal from './components/ImageModal.vue';
import InputDate from './components/InputDate.vue';
import CreateNewDateProduct from './components/CreateNewDateProduct.vue';
import SelectShopAndGroup from './components/SelectShopAndGroup.vue';
import PhoneInput from './components/PhoneInput.vue';
import DeleteButton from './components/DeleteButton.vue';
import DigitalLoupe from './components/DigitalLoupe.vue';
app.component('delete-btn', DeleteButton);
app.component('input-date', InputDate);
app.component('example-component', ExampleComponent);
app.component('input-barcode', InputBarcode);
app.component('image-upload', ImageUploader);
app.component('create-product', CreateNewProduct);
app.component('create-date', CreateNewDateProduct);
app.component('image-modal', ImageModal);
app.component('select-shop', SelectShopAndGroup );
app.component('phone-input', PhoneInput );
app.component('zoom', DigitalLoupe );

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// Object.entries(import.meta.glob('./**/*.vue', { eager: true })).forEach(([path, definition]) => {
//     app.component(path.split('/').pop().replace(/\.\w+$/, ''), definition.default);
// });

/**
 * Finally, we will attach the application instance to a HTML element with
 * an "id" attribute of "app". This element is included with the "auth"
 * scaffolding. Otherwise, you will need to add an element yourself.
 */
app.use(VCalendar, {});
app.mount('#app');
