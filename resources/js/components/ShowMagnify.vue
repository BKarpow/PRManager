<template>
  <div class="p-4">
    <button type="button" @click="showModal = true" class="btn btn-lg btn-warning shadow-sm">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
  <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
</svg> Відкрити цифрову лупу
    </button>

    <div v-if="showModal" class="custom-modal-backdrop" @click.self="showModal = false">
      <div class="modal-dialog modal-fullscreen shadow-lg">
        <div class="modal-content">

          <div class="modal-header bg-dark text-white border-0">
            <h5 class="modal-title">Режим лупи</h5>
            <button type="button" class="btn-close btn-close-white" @click="showModal = false"></button>
          </div>

          <div class="modal-body p-0 bg-black d-flex align-items-center justify-content-center">
            <DigitalLoupe v-if="showModal" />
          </div>

          <div class="modal-footer bg-dark border-0 justify-content-center">
            <button class="btn btn-secondary w-100" @click="showModal = false">Закрити</button>
          </div>

        </div>
      </div>
    </div>
  </div>
</template>

<script>
import DigitalLoupe from './DigitalLoupe.vue';

export default {
  components: {
    DigitalLoupe
  },
  data() {
    return {
      showModal: false
    };
  },
  watch: {
    // Блокуємо скрол сторінки, коли модалка відкрита
    showModal(value) {
      if (value) {
        document.body.style.overflow = 'hidden';
      } else {
        document.body.style.overflow = 'auto';
      }
    }
  }
};
</script>

<style scoped>
.custom-modal-backdrop {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background: rgba(0, 0, 0, 0.85);
  z-index: 1050;
  display: flex;
  align-items: center;
  justify-content: center;
}

.modal-dialog {
  width: 100%;
  height: 100%;
  margin: 0;
}

.modal-content {
  height: 100%;
  border-radius: 0;
}
</style>
