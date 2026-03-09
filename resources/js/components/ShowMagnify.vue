<template>
  <div class="p-4">
    <button @click="showModal = true" class="btn btn-lg btn-warning shadow-sm">
      <i class="bi bi-search"></i> Відкрити цифрову лупу
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
