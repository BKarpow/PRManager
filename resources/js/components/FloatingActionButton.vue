<template>
  <div class="fab-container">
    <a
      @click="$emit('click')"
      :href="url"
      class="btn btn-mb btn-fab shadow-lg"
      type="button"
      title="Додати новий термін"
    >
       <i class="bi bi-node-plus-fill"></i>
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
  z-index: 1049; /* Вище за більшість елементів Bootstrap */
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
  background-color: var(--main-bg-color);
}

.btn-fab:active {
  transform: scale(0.95);
}
.btn-mb {
    background: var(--main-bg-color);
    color: white;
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
