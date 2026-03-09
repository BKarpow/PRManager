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
        v-for="zoomLevel in [1, 3, 5, 10]"
        :key="zoomLevel"
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
      error: null

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
        // Запитуємо доступ до задньої камери
        this.stream = await navigator.mediaDevices.getUserMedia({
          video: {
            facingMode: { exact: "environment" },
            // Можна додати бажану роздільну здатність
            width: { ideal: 1280 },
            height: { ideal: 720 }
          }
        });

        this.$refs.video.srcObject = this.stream;

        // Отримуємо відео трек для керування налаштуваннями
        this.track = this.stream.getVideoTracks()[0];

        // Перевірка підтримки зуму
        const capabilities = this.track.getCapabilities();
        if (!capabilities.zoom) {
          this.error = "Ваш пристрій не підтримує апаратний зум у браузері.";
        }
      } catch (err) {
        this.error = "Помилка доступу до камери: " + err.message;
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
