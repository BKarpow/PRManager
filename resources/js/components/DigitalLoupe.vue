<template>
  <div class="container-fluid py-4 text-center">
    <h3 class="mb-3">Цифрова Лупа</h3>

    <div class="position-relative d-inline-block bg-dark rounded overflow-hidden mb-3"
         style="width: 100%; max-width: 500px; aspect-ratio: 3/4;">
      <video
        ref="video"
        autoplay
        playsinline
        class="w-100 h-100"
        style="object-fit: cover;">
      </video>
    </div>

    <div class="d-flex justify-content-center gap-2 mb-4">
        <button
        type="button"
        :class="['btn', torchActive ? 'btn-primary' : 'btn-outline-primary']"
        class="rounded-circle p-3 shadow-sm"
        style="width: 60px; height: 60px;"
        @click="toggleTorch()"
        ><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-lightbulb-fill" viewBox="0 0 16 16">
  <path d="M2 6a6 6 0 1 1 10.174 4.31c-.203.196-.359.4-.453.619l-.762 1.769A.5.5 0 0 1 10.5 13h-5a.5.5 0 0 1-.46-.302l-.761-1.77a2 2 0 0 0-.453-.618A5.98 5.98 0 0 1 2 6m3 8.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1l-.224.447a1 1 0 0 1-.894.553H6.618a1 1 0 0 1-.894-.553L5.5 15a.5.5 0 0 1-.5-.5"/>
</svg></button>
      <button
        v-for="zoomLevel in [1, 3, 5, 10]"
        :key="zoomLevel"
        type="button"
        @click="setZoom(zoomLevel)"
        :class="['btn', currentZoom === zoomLevel ? 'btn-primary' : 'btn-outline-primary']"
        class="rounded-circle p-3 shadow-sm"
        style="width: 60px; height: 60px;"
      >
        {{ zoomLevel }}x
      </button>
    </div>

    <div v-if="error" class="alert alert-danger mt-3">
      {{ error }}
    </div>
  </div>
</template>

<script>
const lcKey = "ZoomCurr";
export default {
  data() {
    return {
      stream: null,
      track: null,
      currentZoom: 1,
      error: null,
      hasTorch: false,
    torchActive: false

    };
  },
  mounted() {
    this.initCamera();
    const cz = window.localStorage.getItem(lcKey);
    if (cz !== null) {
        this.setZoom(cz);
    }
  },
  beforeUnmount() {
    this.stopCamera();
  },
  methods: {
    async initCamera() {
  try {
    this.stream = await navigator.mediaDevices.getUserMedia({
      video: { facingMode: "environment" }
    });
    this.track = this.stream.getVideoTracks()[0];
    const caps = this.track.getCapabilities();

    // Перевіряємо чи є ліхтарик
    this.hasTorch = !!caps.torch;

    this.$refs.video.srcObject = this.stream;
  } catch (err) {
    this.error = "Камера недоступна";
  }
},

async toggleTorch() {
  this.torchActive = !this.torchActive;
  try {
    await this.track.applyConstraints({
      advanced: [{ torch: this.torchActive }]
    });
  } catch (e) {
    console.error("Ліхтарик не підтримується");
  }
},

    async setZoom(level) {
        window.localStorage.setItem(lcKey, level);
      if (!this.track) return;

      const capabilities = this.track.getCapabilities();

      // Перевіряємо межі зуму, які дозволяє камера (наприклад, від 1 до 8)
      const min = capabilities.zoom?.min || 1;
      const max = capabilities.zoom?.max || 1;

      // Обмежуємо значення, щоб не вийти за межі можливостей сенсора
      const finalZoom = Math.min(Math.max(level, min), max);

      try {
        await this.track.applyConstraints({
          advanced: [{ zoom: finalZoom }]
        });
        this.currentZoom = level;
      } catch (err) {
        console.error("Не вдалося встановити зум:", err);
      }
    },

    stopCamera() {
      if (this.track) {
        this.track.stop();
      }
    }
  }
};
</script>

<style scoped>
/* Додаткові стилі для мобільного вигляду */
video {
  transform: scaleX(1); /* Для задньої камери не потрібно дзеркалити */
}
</style>
