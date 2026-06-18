<!-- components/ExpiryDateScanner.vue -->

<template>
  <div class="expiry-scanner">
    <h2>Сканер терміну придатності</h2>

    <!-- Область завантаження -->
    <div
      class="upload-area"
      :class="{ 'drag-over': isDragOver }"
      @dragover.prevent="isDragOver = true"
      @dragleave.prevent="isDragOver = false"
      @drop.prevent="handleDrop"
      @click="triggerFileInput"
    >
      <input
        ref="fileInput"
        type="file"
        accept="image/jpeg,image/png,image/jpg"
        style="display: none"
        @change="handleFileSelect"
      />

      <div v-if="!imagePreview" class="upload-placeholder">
        <svg class="upload-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
        <p>Натисніть або перетягніть зображення</p>
        <small>JPEG, PNG (макс. 5MB)</small>
      </div>

      <div v-else class="image-preview">
        <img :src="imagePreview" alt="Preview" />
        <button class="remove-btn" @click.stop="clearImage">✕</button>
      </div>
    </div>

    <!-- Прогрес розпізнавання -->
    <div v-if="isProcessing" class="progress-section">
      <div class="progress-bar">
        <div class="progress-fill" :style="{ width: `${progress}%` }"></div>
      </div>
      <p class="progress-text">{{ progressMessage }}</p>
    </div>

    <!-- Результати -->
    <div v-if="result" class="results-section" :class="{ error: !result.success }">
      <h3>Результат розпізнавання</h3>

      <div v-if="result.success" class="result-content">
        <div class="result-item">
          <span class="label">📅 Термін придатності:</span>
          <span class="value expiry">{{ result.expiryDate || 'Не знайдено' }}</span>
        </div>

        <div class="result-item">
          <span class="label">🏭 Дата виробництва:</span>
          <span class="value">{{ result.productionDate || 'Не знайдено' }}</span>
        </div>

        <div class="result-item">
          <span class="label">📊 Впевненість:</span>
          <span class="value">{{ (result.confidence * 100).toFixed(1) }}%</span>
        </div>

        <div class="extracted-text">
          <details>
            <summary>Розпізнаний текст</summary>
            <pre>{{ result.fullText }}</pre>
          </details>
        </div>
      </div>

      <div v-else class="error-message">
        <p>⚠️ {{ result.error }}</p>
        <details v-if="result.fullText">
          <summary>Розпізнаний текст</summary>
          <pre>{{ result.fullText }}</pre>
        </details>
      </div>
    </div>

    <!-- Кнопка сканування -->
    <button
      v-if="imagePreview && !isProcessing"
      class="scan-btn"
      @click="processImage"
    >
      Сканувати дату
    </button>
  </div>
</template>

<script>
export default {
  name: 'ExpiryDateScanner',

  data() {
    return {
      imageFile: null,
      imagePreview: null,
      isDragOver: false,
      isProcessing: false,
      progress: 0,
      progressMessage: 'Підготовка...',
      result: null,
    };
  },

  methods: {
    triggerFileInput() {
      this.$refs.fileInput.click();
    },

    handleFileSelect(event) {
      const file = event.target.files[0];
      if (file) {
        this.loadImage(file);
      }
    },

    handleDrop(event) {
      this.isDragOver = false;
      const file = event.dataTransfer.files[0];
      if (file && file.type.startsWith('image/')) {
        this.loadImage(file);
      } else {
        alert('Будь ласка, перетягніть зображення');
      }
    },

    loadImage(file) {
      if (file.size > 5 * 1024 * 1024) {
        alert('Зображення занадто велике. Максимум 5MB');
        return;
      }

      this.imageFile = file;
      this.result = null;

      const reader = new FileReader();
      reader.onload = (e) => {
        this.imagePreview = e.target.result;
      };
      reader.readAsDataURL(file);
    },

    clearImage() {
      this.imageFile = null;
      this.imagePreview = null;
      this.result = null;
      this.progress = 0;
    },

    async processImage() {
      if (!this.imageFile) return;

      this.isProcessing = true;
      this.result = null;
      this.progress = 0;

      let worker = null;

      try {
        // Попередня обробка зображення для покращення OCR
        const processedImage = await this.preprocessImage(this.imageFile);

        const TesseractModule = await import('tesseract.js');
        const { createWorker } = TesseractModule;

        // Спроба 1: Стандартне розпізнавання з англійською мовою
        worker = await createWorker('eng', 1, {
          logger: (progress) => {
            this.handleProgress(progress);
          }
        });

        // Встановлюємо параметри для кращого розпізнавання тексту
        await worker.setParameters({
          tessedit_pageseg_mode: 6, // Окремий блок тексту
          tessedit_char_whitelist: 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789/:.-',
        });

        const { data: data1 } = await worker.recognize(processedImage);

        // Спроба 2: Інший режим розпізнавання
        await worker.setParameters({
          tessedit_pageseg_mode: 11, // Отримання всього тексту
        });

        const { data: data2 } = await worker.recognize(processedImage);

        await worker.terminate();

        // Об'єднуємо результати
        const combinedText = data1.text + '\n' + data2.text;

        this.progress = 100;

        // Аналіз результатів з пріоритетом на EXP та PROD
        const result = this.extractDateFromTextAdvanced(combinedText);

        this.result = {
          success: result.expiryDate !== null,
          expiryDate: result.expiryDate,
          productionDate: result.productionDate,
          confidence: result.confidence,
          fullText: combinedText,
          error: result.expiryDate ? null : 'Не вдалося знайти дату на зображенні'
        };

      } catch (error) {
        console.error('OCR помилка:', error);
        this.result = {
          success: false,
          expiryDate: null,
          productionDate: null,
          confidence: 0,
          fullText: null,
          error: 'Помилка розпізнавання: ' + (error.message || 'Невідома помилка')
        };
      } finally {
        if (worker) {
          await worker.terminate();
        }
        this.isProcessing = false;
      }
    },

    // Попередня обробка зображення в Canvas
    async preprocessImage(file) {
      return new Promise((resolve, reject) => {
        const img = new Image();
        const url = URL.createObjectURL(file);

        img.onload = () => {
          // Створюємо canvas для обробки
          const canvas = document.createElement('canvas');
          let width = img.width;
          let height = img.height;

          // Збільшуємо зображення якщо воно маленьке
          if (width < 800 || height < 600) {
            width = width * 2;
            height = height * 2;
          }

          canvas.width = width;
          canvas.height = height;

          const ctx = canvas.getContext('2d');

          // Малюємо зображення з новим розміром
          ctx.drawImage(img, 0, 0, width, height);

          // Покращуємо контраст
          const imageData = ctx.getImageData(0, 0, width, height);
          const data = imageData.data;

          // Збільшення контрасту (бінаризація)
          for (let i = 0; i < data.length; i += 4) {
            const brightness = (data[i] + data[i+1] + data[i+2]) / 3;
            const threshold = 120;
            const value = brightness > threshold ? 255 : 0;
            data[i] = value;     // R
            data[i+1] = value;   // G
            data[i+2] = value;   // B
          }

          ctx.putImageData(imageData, 0, 0);

          // Конвертуємо canvas назад в файл
          canvas.toBlob((blob) => {
            URL.revokeObjectURL(url);
            resolve(blob);
          }, 'image/png');
        };

        img.onerror = reject;
        img.src = url;
      });
    },

    handleProgress(progress) {
      let normalizedProgress = 0;

      if (progress.status === 'loading tesseract core') {
        normalizedProgress = Math.round(progress.progress * 20);
        this.progressMessage = 'Завантаження двигуна...';
      } else if (progress.status === 'loading language traineddata') {
        normalizedProgress = 20 + Math.round(progress.progress * 40);
        this.progressMessage = 'Завантаження мовних даних...';
      } else if (progress.status === 'recognizing text') {
        normalizedProgress = 60 + Math.round(progress.progress * 39);
        this.progressMessage = 'Розпізнавання тексту...';
      }

      this.progress = Math.min(normalizedProgress, 99);
    },

    extractDateFromTextAdvanced(text) {
      // Розбиваємо текст на рядки
      const lines = text.toUpperCase().split(/\r?\n/);

      let expiryDate = null;
      let productionDate = null;
      let confidence = 0;

      // Патерни для пошуку PROD та EXP
      const prodPatterns = [
        /PROD\s*:?\s*(\d{2}[\/\-\.]\d{2}[\/\-\.]\d{2,4})/i,
        /PRODUCTION\s*:?\s*(\d{2}[\/\-\.]\d{2}[\/\-\.]\d{2,4})/i,
        /MFG\s*:?\s*(\d{2}[\/\-\.]\d{2}[\/\-\.]\d{2,4})/i,
        /DATE\s*:?\s*(\d{2}[\/\-\.]\d{2}[\/\-\.]\d{2,4})/i,
      ];

      const expiryPatterns = [
        /EXP\s*:?\s*(\d{2}[\/\-\.]\d{2}[\/\-\.]\d{2,4})/i,
        /EXPIRY\s*:?\s*(\d{2}[\/\-\.]\d{2}[\/\-\.]\d{2,4})/i,
        /EXPIRE\s*:?\s*(\d{2}[\/\-\.]\d{2}[\/\-\.]\d{2,4})/i,
        /BEST BEFORE\s*:?\s*(\d{2}[\/\-\.]\d{2}[\/\-\.]\d{2,4})/i,
        /USE BY\s*:?\s*(\d{2}[\/\-\.]\d{2}[\/\-\.]\d{2,4})/i,
        /ДО\s*:?\s*(\d{2}[\/\-\.]\d{2}[\/\-\.]\d{2,4})/i,
        /ТЕРМІН\s*:?\s*(\d{2}[\/\-\.]\d{2}[\/\-\.]\d{2,4})/i,
      ];

      // Шукаємо дати в кожному рядку
      for (const line of lines) {
        // Перевіряємо чи це рядок з EXP
        for (const pattern of expiryPatterns) {
          const match = line.match(pattern);
          if (match && match[1]) {
            const formattedDate = this.parseDateString(match[1]);
            if (formattedDate) {
              expiryDate = formattedDate;
              confidence = 0.95;
              break;
            }
          }
        }

        // Перевіряємо чи це рядок з PROD
        for (const pattern of prodPatterns) {
          const match = line.match(pattern);
          if (match && match[1]) {
            const formattedDate = this.parseDateString(match[1]);
            if (formattedDate) {
              productionDate = formattedDate;
              break;
            }
          }
        }
      }

      // Якщо не знайшли через ключові слова, шукаємо будь-яку дату
      if (!expiryDate) {
        const allDates = this.findAllDates(text);
        if (allDates.length > 0) {
          // Беремо найпізнішу дату як термін придатності
          const now = new Date();
          const futureDates = allDates.filter(d => new Date(d) > now);
          if (futureDates.length > 0) {
            expiryDate = futureDates[0];
            confidence = 0.7;
          } else if (allDates.length > 1) {
            // Якщо є дві дати, друга може бути терміном придатності
            expiryDate = allDates[allDates.length - 1];
            productionDate = allDates[0];
            confidence = 0.7;
          } else {
            expiryDate = allDates[0];
            confidence = 0.6;
          }
        }
      }

      return {
        expiryDate,
        productionDate,
        confidence
      };
    },

    parseDateString(dateStr) {
      // Підтримувані роздільники: /, -, .
      const parts = dateStr.split(/[\/\-\.]/);
      if (parts.length !== 3) return null;

      let day, month, year;

      // Визначаємо порядок (спочатку пробуємо як MM/DD/YYYY)
      let y = parseInt(parts[2]);
      let m = parseInt(parts[0]);
      let d = parseInt(parts[1]);

      // Якщо місяць > 12, то це швидше за все DD/MM/YYYY
      if (m > 12) {
        m = parseInt(parts[1]);
        d = parseInt(parts[0]);
      }

      // Нормалізація року
      if (y < 100) y += 2000;

      // Валідація
      if (y < 2000 || y > 2100) return null;
      if (m < 1 || m > 12) return null;
      if (d < 1 || d > 31) return null;

      const date = new Date(y, m - 1, d);
      if (date.getFullYear() === y && date.getMonth() === m - 1 && date.getDate() === d) {
        return `${y}-${String(m).padStart(2, '0')}-${String(d).padStart(2, '0')}`;
      }

      return null;
    },

    findAllDates(text) {
      const dateRegex = /\b(\d{1,2})[\/\-\.](\d{1,2})[\/\-\.](\d{2,4})\b/g;
      const dates = [];
      let match;

      while ((match = dateRegex.exec(text)) !== null) {
        const formatted = this.parseDateString(match[0]);
        if (formatted && !dates.includes(formatted)) {
          dates.push(formatted);
        }
      }

      return dates;
    }
  }
};
</script>

<style scoped>
.expiry-scanner {
  max-width: 600px;
  margin: 0 auto;
  padding: 20px;
  font-family: system-ui, -apple-system, sans-serif;
}

h2 {
  text-align: center;
  color: #333;
  margin-bottom: 24px;
}

.upload-area {
  border: 2px dashed #ccc;
  border-radius: 12px;
  padding: 40px;
  text-align: center;
  cursor: pointer;
  transition: all 0.3s;
  background: #fafafa;
}

.upload-area.drag-over {
  border-color: #4CAF50;
  background: #f0f9f0;
}

.upload-placeholder .upload-icon {
  width: 48px;
  height: 48px;
  margin: 0 auto 12px;
  color: #999;
}

.upload-placeholder p {
  margin: 8px 0;
  color: #666;
}

.image-preview {
  position: relative;
  display: inline-block;
  max-width: 100%;
}

.image-preview img {
  max-width: 100%;
  max-height: 300px;
  border-radius: 8px;
}

.remove-btn {
  position: absolute;
  top: -10px;
  right: -10px;
  background: #ff4444;
  color: white;
  border: none;
  border-radius: 50%;
  width: 28px;
  height: 28px;
  cursor: pointer;
  font-size: 16px;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.2s;
}

.remove-btn:hover {
  background: #cc0000;
  transform: scale(1.1);
}

.progress-section {
  margin-top: 24px;
}

.progress-bar {
  background: #e0e0e0;
  border-radius: 10px;
  overflow: hidden;
  height: 8px;
}

.progress-fill {
  background: #4CAF50;
  height: 100%;
  transition: width 0.3s;
  border-radius: 10px;
}

.progress-text {
  text-align: center;
  font-size: 14px;
  color: #666;
  margin-top: 8px;
}

.results-section {
  margin-top: 24px;
  padding: 16px;
  border-radius: 8px;
  background: #f5f5f5;
}

.results-section.error {
  background: #ffebee;
  border-left: 4px solid #ff4444;
}

.results-section h3 {
  margin: 0 0 16px 0;
  font-size: 18px;
}

.result-item {
  display: flex;
  justify-content: space-between;
  padding: 8px 0;
  border-bottom: 1px solid #ddd;
}

.result-item .label {
  font-weight: 600;
  color: #555;
}

.result-item .value {
  color: #333;
  font-weight: 500;
}

.result-item .value.expiry {
  color: #4CAF50;
  font-weight: 700;
}

.extracted-text {
  margin-top: 16px;
}

.extracted-text details {
  cursor: pointer;
}

.extracted-text summary {
  color: #666;
  font-size: 14px;
}

.extracted-text pre {
  background: white;
  padding: 12px;
  border-radius: 6px;
  overflow-x: auto;
  font-size: 12px;
  font-family: monospace;
  margin-top: 8px;
  white-space: pre-wrap;
  word-wrap: break-word;
  max-height: 200px;
  overflow-y: auto;
}

.error-message {
  color: #ff4444;
  text-align: center;
  padding: 12px;
}

.error-message details {
  text-align: left;
  margin-top: 12px;
}

.error-message summary {
  color: #666;
  cursor: pointer;
}

.scan-btn {
  width: 100%;
  margin-top: 16px;
  padding: 12px;
  background: #4CAF50;
  color: white;
  border: none;
  border-radius: 8px;
  font-size: 16px;
  font-weight: 600;
  cursor: pointer;
  transition: background 0.3s;
}

.scan-btn:hover {
  background: #45a049;
}

.scan-btn:active {
  transform: scale(0.98);
}
</style>
