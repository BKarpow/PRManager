<template>
  <div class="fab-container">
    <a
      @click="$emit('click')"
      :href="url"
      class="btn btn-primary btn-fab shadow-lg"
      type="button"
      title="Додати продукт"
    >
      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2"/>
</svg>
    </a>
  </div>
</template>

<script setup>
defineEmits(['click']);
defineProps(['url']);
</script>

<style scoped>
.fab-container {
  position: fixed;
  bottom: 2rem;
  right: 2rem;
  z-index: 1050; /* Вище за більшість елементів Bootstrap */
}

.btn-fab {
  width: 64px;
  height: 64px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 2rem;
  transition: transform 0.2s ease-in-out, background-color 0.2s;
}

.btn-fab:hover {
  transform: scale(1.1) rotate(90deg);
  background-color: #0b5ed7; /* Трохи темніший синій */
}

.btn-fab:active {
  transform: scale(0.95);
}

/* Адаптація для мобільних пристроїв */
@media (max-width: 576px) {
  .fab-container {
    bottom: 1.5rem;
    right: 1.5rem;
  }
  .btn-fab {
    width: 56px;
    height: 56px;
    font-size: 1.75rem;
  }
}
</style>
