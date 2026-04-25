<template>
  <div class="password-wrapper mb-3">
    <label class="form-label">Пароль</label>
    <div class="input-group">
      <input
        :type="showPassword ? 'text' : 'password'"
        class="form-control"
        :name="name"
        v-model="password"
        placeholder="Введіть або згенеруйте пароль"
      />
      <button class="btn btn-outline-secondary" type="button" @click="toggleVisibility">
        <i :class="showPassword ? 'bi bi-eye-slash' : 'bi bi-eye'"></i>
      </button>
      <button class="btn btn-outline-primary" type="button" @click="generatePassword">
        Генерувати
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';

defineProps(['name']);
const password = ref('');
const showPassword = ref(false);

// Функція для перемикання видимості
const toggleVisibility = () => {
  showPassword.value = !showPassword.value;
};

// Функція генерації легкого для читання пароля
const generatePassword = () => {
  // Використовуємо символи, які легко відрізнити один від одного
  const charset = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789';
  let retVal = "";
  const length = 8;

  for (let i = 0; i < length; ++i) {
    retVal += charset.charAt(Math.floor(Math.random() * charset.length));
  }
  password.value = retVal;
  showPassword.value = true;
};
</script>
