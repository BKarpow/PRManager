<script setup>
import { ref, onMounted } from 'vue';

const installEvent = ref(null);
const showBanner = ref(false);

onMounted(() => {
  // 1. Слухаємо подію браузера "перед встановленням"
  window.addEventListener('beforeinstallprompt', (e) => {
    // Запобігаємо негайній появі системного вікна
    e.preventDefault();
    // Зберігаємо подію, щоб викликати її пізніше
    installEvent.value = e;
    // Показуємо наш власний UI (банер або кнопку)
    showBanner.value = true;
  });

  // Ховаємо банер, якщо додаток вже встановлено
  window.addEventListener('appinstalled', () => {
    showBanner.value = false;
    installEvent.value = null;
    console.log('PWA було успішно встановлено!');
  });
});

const installApp = async () => {
  if (!installEvent.value) return;
    const otherpwa = Bool(window.sessionStorage.getItem('pwaother'));
  // Показуємо системний діалог встановлення
  if (!otherpwa)
  {
    installEvent.value.prompt();
  }


  // Очікуємо вибору користувача
  const { outcome } = await installEvent.value.userChoice;

  if (outcome === 'accepted') {
    console.log('Користувач погодився на встановлення');
  } else {
    console.log('Користувач відхилив встановлення');
    window.sessionStorage.setItem('pwaother', true);
  }

  // Очищуємо подію, вона одноразова
  installEvent.value = null;
  showBanner.value = false;
};

const dismissBanner = () => {
  showBanner.value = false;
};
</script>

<template>
  <Transition name="fade">
    <div v-if="showBanner" class="pwa-install-banner shadow-lg border-top">
      <div class="container d-flex align-items-center justify-content-between p-3">
        <div class="d-flex align-items-center">
          <div class="bg-primary text-white rounded p-2 me-3">
             <i class="bi bi-download"></i> <span class="fw-bold">APP</span>
          </div>
          <div>
            <h6 class="mb-0">Встановити наш додаток?</h6>
            <small class="text-muted">Швидкий доступ та робота офлайн</small>
          </div>
        </div>
        <div class="btn-group">
          <button @click="installApp" class="btn btn-success btn-sm px-4">Встановити</button>
          <button @click="dismissBanner" class="btn btn-outline-secondary btn-sm">Пізніше</button>
        </div>
      </div>
    </div>
  </Transition>
</template>

<style scoped>
.pwa-install-banner {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  background: white;
  z-index: 1050; /* Вище за навігацію */
}

/* Анімація появи */
.fade-enter-active, .fade-leave-active {
  transition: transform 0.4s ease, opacity 0.4s ease;
}
.fade-enter-from, .fade-leave-to {
  transform: translateY(100%);
  opacity: 0;
}
</style>
