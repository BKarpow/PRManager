<template>
  <div class="mb-3">
    <label for="phone" class="form-label">Номер телефону</label>
    <input
      type="tel"
      id="phone"
      class="form-control"
      :class="{ 'is-invalid': error, 'is-valid': isValid && !error }"
      placeholder="+38 (0XX) XXX-XX-XX"
      v-model="formattedPhone"
      @input="handleInput"
    />
    <input type="hidden" name="phone" v-model="phone" />
    <div v-if="error" class="invalid-feedback">
      {{ error }}
    </div>
    <small class="text-muted">Формат: +38 (067) 123-45-67</small>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue';

const props = defineProps(['modelValue']);
const emit = defineEmits(['update:modelValue']);

const formattedPhone = ref(props.modelValue || '+38 (0');
const error = ref('');
const phone = ref('');
const isValid = ref(false);

const handleInput = (e) => {
  let value = e.target.value.replace(/\D/g, ''); // Тільки цифри

  // Якщо користувач видалив префікс 380, повертаємо його
  if (!value.startsWith('380')) {
    value = '380' + value;
  }

  // Обмежуємо довжину (380 + 9 цифр)
  value = value.substring(0, 12);

  // Створюємо маску: +38 (0XX) XXX-XX-XX
  let mask = '+38 (0';
  if (value.length > 3) mask += value.substring(3, 5);
  if (value.length > 5) mask += ') ' + value.substring(5, 8);
  if (value.length > 8) mask += '-' + value.substring(8, 10);
  if (value.length > 10) mask += '-' + value.substring(10, 12);

  formattedPhone.value = mask;

  // Валідація довжини
  if (value.length === 12) {
    error.value = '';
    isValid.value = true;
    emit('update:modelValue', value); // Відправляємо чисті цифри (38067...)
    phone.value = value;
  } else {
    isValid.value = false;
    error.value = 'Номер занадто короткий';
  }
};
</script>
