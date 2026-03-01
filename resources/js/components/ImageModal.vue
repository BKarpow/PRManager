<template>
    <div class="image-modal-component">
        <!-- Маленьке фото -->
        <img
            :src="imageSrc"
            :alt="altText"
            class="thumbnail img-thumbnail"
            :class="thumbnailClass"
            @click="openModal"
        >

        <!-- Модальне вікно -->
        <div v-if="isModalOpen" class="modal fade show d-block" tabindex="-1" role="dialog" @click="closeModal">
            <div class="modal-dialog modal-dialog-centered modal-xl" role="document" @click.stop>
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ title }}</h5>
                        <button type="button" class="btn-close" @click="closeModal"></button>
                    </div>
                    <div class="modal-body text-center">
                        <img
                            :src="imageSrc"
                            :alt="altText"
                            class="img-fluid modal-image"
                        >
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="closeModal">
                            Закрити
                        </button>
                        <button v-if="downloadable" type="button" class="btn btn-primary" @click="downloadImage">
                            <i class="fas fa-download me-1"></i> Завантажити
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Затемнений фон -->
        <div v-if="isModalOpen" class="modal-backdrop fade show"></div>
    </div>
</template>

<script>
export default {
    name: 'ImageModal',
    props: {
        // Обов'язкові пропси
        imageSrc: {
            type: String,
            required: true
        },
        altText: {
            type: String,
            default: 'Image'
        },

        // Додаткові пропси
        title: {
            type: String,
            default: 'Перегляд зображення'
        },
        thumbnailClass: {
            type: String,
            default: ''
        },
        thumbnailWidth: {
            type: String,
            default: '100px'
        },
        downloadable: {
            type: Boolean,
            default: false
        },
        downloadName: {
            type: String,
            default: 'image'
        }
    },
    data() {
        return {
            isModalOpen: false
        };
    },
    methods: {
        openModal() {
            this.isModalOpen = true;
            // Блокуємо прокрутку body
            document.body.style.overflow = 'hidden';
            this.$emit('opened');
        },

        closeModal() {
            this.isModalOpen = false;
            // Відновлюємо прокрутку body
            document.body.style.overflow = '';
            this.$emit('closed');
        },

        downloadImage() {
            const link = document.createElement('a');
            link.href = this.imageSrc;
            link.download = `${this.downloadName}.jpg`;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            this.$emit('downloaded');
        },

        // Закриття по ESC
        handleKeydown(event) {
            if (event.key === 'Escape' && this.isModalOpen) {
                this.closeModal();
            }
        }
    },
    mounted() {
        // Додаємо обробник клавіші ESC
        document.addEventListener('keydown', this.handleKeydown);
    },
    beforeUnmount() {
        // Видаляємо обробник при видаленні компонента
        document.removeEventListener('keydown', this.handleKeydown);
        // Відновлюємо прокрутку на випадок якщо модалка була відкрита
        document.body.style.overflow = '';
    }
};
</script>

<style scoped>
.thumbnail {
    cursor: pointer;
    transition: all 0.3s ease;
    max-width: v-bind(thumbnailWidth);
    height: auto;
}

.thumbnail:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.modal {
    background-color: rgba(0, 0, 0, 0.5);
}

.modal-image {
    max-height: 70vh;
    max-width: 100%;
    object-fit: contain;
}

.modal-backdrop {
    z-index: 1040;
}

.modal {
    z-index: 1050;
}

/* Анімації */
.modal.fade .modal-dialog {
    transform: scale(0.8);
    transition: transform 0.3s ease-out;
}

.modal.fade.show .modal-dialog {
    transform: scale(1);
}
</style>
