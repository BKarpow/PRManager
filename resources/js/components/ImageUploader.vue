<template>
    <div class="simple-image-cropper">
        <!-- Завантаження файлу -->
        <div v-if="!imageSrc" class="upload-section">
            <div class="upload-area" @click="triggerFileInput">
                <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                <p class="mb-1">Натисніть для завантаження фото</p>
                <small class="text-muted">PNG, JPG до 5MB</small>
            </div>
            <input
                type="file"
                ref="fileInput"
                @change="handleFileSelect"
                accept="image/*"
                class="d-none"
            />
        </div>

        <!-- Область обрізки -->
        <div v-else class="cropping-section">
            <!-- Контейнер для обрізки -->
            <div class="crop-container" ref="cropContainer">
                <img
                    ref="imageElement"
                    :src="imageSrc"
                    alt="Image to crop"
                    class="crop-image"
                    :style="imageStyle"
                    @load="onImageLoad"
                />

                <!-- Область виділення -->
                <div
                    class="crop-area"
                    ref="cropArea"
                    :style="cropAreaStyle"
                    @mousedown="startDrag"
                    @touchstart="startDrag"
                >
                    <div class="crop-handles">
                        <div
                            class="handle handle-tl"
                            @mousedown="startResize('tl')"
                            @touchstart="startResize('tl')"
                        ></div>
                        <div
                            class="handle handle-tr"
                            @mousedown="startResize('tr')"
                            @touchstart="startResize('tr')"
                        ></div>
                        <div
                            class="handle handle-bl"
                            @mousedown="startResize('bl')"
                            @touchstart="startResize('bl')"
                        ></div>
                        <div
                            class="handle handle-br"
                            @mousedown="startResize('br')"
                            @touchstart="startResize('br')"
                        ></div>
                    </div>
                </div>
            </div>

            <!-- Елементи керування -->
            <div class="controls mt-3">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <label class="form-label">Розмір обрізки:</label>
                        <select
                            v-model="cropRatio"
                            class="form-select"
                            @change="resetCropArea"
                        >
                            <option value="free">Вільний</option>
                            <option value="1:1">Квадрат (1:1)</option>
                            <option value="4:3">Прямокутник (4:3)</option>
                            <option value="16:9">Широкий (16:9)</option>
                        </select>
                    </div>
                    <div class="col-md-6 text-end">
                        <button
                            type="button"
                            class="btn btn-secondary me-2"
                            @click="cancelCrop"
                        >
                            Скасувати
                        </button>
                        <button
                            type="button"
                            class="btn btn-primary"
                            @click="cropImage"
                            :disabled="uploading"
                        >
                            <span
                                v-if="uploading"
                                class="spinner-border spinner-border-sm me-1"
                            ></span>
                            {{ uploading ? "Завантаження..." : "Зберегти" }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Попередній перегляд -->
            <div class="preview-section mt-3">
                <h6>Попередній перегляд:</h6>
                <div class="preview-container">
                    <canvas ref="previewCanvas" class="preview-canvas"></canvas>
                </div>
            </div>
        </div>

        <!-- Повідомлення -->
        <div v-if="message" class="alert mt-3" :class="messageClass">
            {{ message }}
        </div>

        <!-- Завантажені зображення -->
        <div v-if="uploadedImages.length > 0" class="uploaded-images mt-4">
            <h6>Завантажені фото:</h6>
            <div class="image-grid">
                <div
                    v-for="(image, index) in uploadedImages"
                    :key="index"
                    class="image-item"
                >
                    <img
                        :src="image.url"
                        :alt="image.name"
                        class="img-thumbnail"
                    />
                    <button
                        class="btn btn-sm btn-danger delete-btn"
                        @click="deleteImage(index)"
                    >
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "SimpleImageCropper",
    props: {
        productId: {
            type: [Number, String],
            required: true,
        },
    },
    data() {
        return {
            imageSrc: "",
            uploading: false,
            message: "",
            messageClass: "",
            uploadedImages: [],

            // Налаштування обрізки
            cropRatio: "4:3",
            cropArea: {
                x: 50,
                y: 50,
                width: 200,
                height: 150,
            },
            imageSize: { width: 0, height: 0 },
            containerSize: { width: 0, height: 0 },

            // Drag & resize
            isDragging: false,
            isResizing: false,
            resizeDirection: "",
            startX: 0,
            startY: 0,
            startCrop: {},
            uploadImagePath: '',
        };
    },
    computed: {
        imageStyle() {
            return {
                width: "100%",
                height: "auto",
                maxHeight: "400px",
            };
        },
        cropAreaStyle() {
            return {
                left: this.cropArea.x + "px",
                top: this.cropArea.y + "px",
                width: this.cropArea.width + "px",
                height: this.cropArea.height + "px",
            };
        },
    },
    mounted() {
        this.setupEventListeners();
    },
    beforeUnmount() {
        this.removeEventListeners();
    },
    methods: {
        triggerFileInput() {
            this.$refs.fileInput.click();
        },

        handleFileSelect(event) {
            const file = event.target.files[0];
            if (!file) return;

            if (!file.type.match("image.*")) {
                this.showMessage(
                    "Будь ласка, виберіть файл зображення",
                    "danger"
                );
                return;
            }

            if (file.size > 5 * 1024 * 1024) {
                this.showMessage(
                    "Розмір файлу не повинен перевищувати 5MB",
                    "danger"
                );
                return;
            }

            const reader = new FileReader();
            reader.onload = (e) => {
                this.imageSrc = e.target.result;
            };
            reader.readAsDataURL(file);
        },

        onImageLoad() {
            const img = this.$refs.imageElement;
            this.imageSize = {
                width: img.naturalWidth,
                height: img.naturalHeight,
            };

            // Ініціалізація області обрізки
            this.resetCropArea();
            this.updatePreview();
        },

        resetCropArea() {
            const container = this.$refs.cropContainer;
            if (!container) return;

            const containerRect = container.getBoundingClientRect();
            this.containerSize = {
                width: containerRect.width,
                height: containerRect.height,
            };

            let width, height;

            switch (this.cropRatio) {
                case "1:1":
                    width = height =
                        Math.min(containerRect.width, containerRect.height) *
                        0.6;
                    break;
                case "4:3":
                    width = containerRect.width * 0.6;
                    height = (width * 3) / 4;
                    break;
                case "16:9":
                    width = containerRect.width * 0.6;
                    height = (width * 9) / 16;
                    break;
                default: // free
                    width = containerRect.width * 0.6;
                    height = containerRect.height * 0.6;
            }

            this.cropArea = {
                x: (containerRect.width - width) / 2,
                y: (containerRect.height - height) / 2,
                width: width,
                height: height,
            };

            this.updatePreview();
        },

        startDrag(event) {
            event.preventDefault();
            this.isDragging = true;
            const clientX = event.type.includes("touch")
                ? event.touches[0].clientX
                : event.clientX;
            const clientY = event.type.includes("touch")
                ? event.touches[0].clientY
                : event.clientY;

            this.startX = clientX;
            this.startY = clientY;
            this.startCrop = { ...this.cropArea };

            document.addEventListener("mousemove", this.onDrag);
            document.addEventListener("touchmove", this.onDrag);
            document.addEventListener("mouseup", this.stopDrag);
            document.addEventListener("touchend", this.stopDrag);
        },

        onDrag(event) {
            if (!this.isDragging) return;

            event.preventDefault();
            const clientX = event.type.includes("touch")
                ? event.touches[0].clientX
                : event.clientX;
            const clientY = event.type.includes("touch")
                ? event.touches[0].clientY
                : event.clientY;

            const deltaX = clientX - this.startX;
            const deltaY = clientY - this.startY;

            this.cropArea.x = Math.max(
                0,
                Math.min(
                    this.containerSize.width - this.cropArea.width,
                    this.startCrop.x + deltaX
                )
            );

            this.cropArea.y = Math.max(
                0,
                Math.min(
                    this.containerSize.height - this.cropArea.height,
                    this.startCrop.y + deltaY
                )
            );

            this.updatePreview();
        },

        stopDrag() {
            this.isDragging = false;
            document.removeEventListener("mousemove", this.onDrag);
            document.removeEventListener("touchmove", this.onDrag);
            document.removeEventListener("mouseup", this.stopDrag);
            document.removeEventListener("touchend", this.stopDrag);
        },

        startResize(direction) {
            this.isResizing = true;
            this.resizeDirection = direction;
            this.startCrop = { ...this.cropArea };

            document.addEventListener("mousemove", this.onResize);
            document.addEventListener("touchmove", this.onResize);
            document.addEventListener("mouseup", this.stopResize);
            document.addEventListener("touchend", this.stopResize);
        },

        onResize(event) {
            if (!this.isResizing) return;

            event.preventDefault();
            const clientX = event.type.includes("touch")
                ? event.touches[0].clientX
                : event.clientX;
            const clientY = event.type.includes("touch")
                ? event.touches[0].clientY
                : event.clientY;

            const deltaX = clientX - this.startX;
            const deltaY = clientY - this.startY;

            let newCrop = { ...this.startCrop };

            switch (this.resizeDirection) {
                case "br":
                    newCrop.width = Math.max(50, newCrop.width + deltaX);
                    newCrop.height = this.calculateHeight(newCrop.width);
                    break;
                case "tr":
                    newCrop.width = Math.max(50, newCrop.width + deltaX);
                    newCrop.height = this.calculateHeight(newCrop.width);
                    newCrop.y =
                        this.startCrop.y -
                        (newCrop.height - this.startCrop.height);
                    break;
                case "bl":
                    newCrop.width = Math.max(50, newCrop.width - deltaX);
                    newCrop.height = this.calculateHeight(newCrop.width);
                    newCrop.x =
                        this.startCrop.x +
                        (this.startCrop.width - newCrop.width);
                    break;
                case "tl":
                    newCrop.width = Math.max(50, newCrop.width - deltaX);
                    newCrop.height = this.calculateHeight(newCrop.width);
                    newCrop.x =
                        this.startCrop.x +
                        (this.startCrop.width - newCrop.width);
                    newCrop.y =
                        this.startCrop.y -
                        (newCrop.height - this.startCrop.height);
                    break;
            }

            // Обмеження в межах контейнера
            newCrop.x = Math.max(
                0,
                Math.min(this.containerSize.width - newCrop.width, newCrop.x)
            );
            newCrop.y = Math.max(
                0,
                Math.min(this.containerSize.height - newCrop.height, newCrop.y)
            );

            this.cropArea = newCrop;
            this.updatePreview();
        },

        calculateHeight(width) {
            if (this.cropRatio === "free") return width;

            const ratios = {
                "1:1": width,
                "4:3": (width * 3) / 4,
                "16:9": (width * 9) / 16,
            };

            return ratios[this.cropRatio] || width;
        },

        stopResize() {
            this.isResizing = false;
            document.removeEventListener("mousemove", this.onResize);
            document.removeEventListener("touchmove", this.onResize);
            document.removeEventListener("mouseup", this.stopResize);
            document.removeEventListener("touchend", this.stopResize);
        },

        updatePreview() {
            const canvas = this.$refs.previewCanvas;
            const ctx = canvas.getContext("2d");
            const img = this.$refs.imageElement;

            if (!img.complete) return;

            // Розрахунок співвідношення між відображенням і оригіналом
            const displayRatioX = img.naturalWidth / img.offsetWidth;
            const displayRatioY = img.naturalHeight / img.offsetHeight;

            // Координати в оригінальних розмірах
            const sourceX = this.cropArea.x * displayRatioX;
            const sourceY = this.cropArea.y * displayRatioY;
            const sourceWidth = this.cropArea.width * displayRatioX;
            const sourceHeight = this.cropArea.height * displayRatioY;

            // Налаштування canvas
            canvas.width = 200;
            canvas.height = 150;

            // Очищення та малювання
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.drawImage(
                img,
                sourceX,
                sourceY,
                sourceWidth,
                sourceHeight, // source
                0,
                0,
                canvas.width,
                canvas.height // destination
            );
        },

        cropImage() {
            this.uploading = true;

            const canvas = document.createElement("canvas");
            const ctx = canvas.getContext("2d");
            const img = this.$refs.imageElement;

            // Розрахунок співвідношення
            const displayRatioX = img.naturalWidth / img.offsetWidth;
            const displayRatioY = img.naturalHeight / img.offsetHeight;

            const sourceX = this.cropArea.x * displayRatioX;
            const sourceY = this.cropArea.y * displayRatioY;
            const sourceWidth = this.cropArea.width * displayRatioX;
            const sourceHeight = this.cropArea.height * displayRatioY;

            // Створення обрізаного зображення
            canvas.width = sourceWidth;
            canvas.height = sourceHeight;

            ctx.drawImage(
                img,
                sourceX,
                sourceY,
                sourceWidth,
                sourceHeight,
                0,
                0,
                sourceWidth,
                sourceHeight
            );

            // Конвертація в blob та завантаження
            canvas.toBlob(
                async (blob) => {
                    try {
                        const formData = new FormData();
                        formData.append(
                            "image",
                            blob,
                            `product_${this.$props.productId}.jpg`
                        );
                        formData.append("product_id", this.productId);

                        // Відправка на сервер
                        axios.post(
                            "/product/upload/image",
                            formData,
                            {
                                headers: {
                                    "Content-Type": "multipart/form-data",
                                    "X-Requested-With": "XMLHttpRequest",
                                },
                            }
                        ).then(resp => {
                            if (resp.data.success) {
                                this.uploadImagePath = resp.data.url;
                            }
                            this.$emit('upload', this.uploadImagePath);
                            console.debug('Resp uploaded', resp.data);
                        });

                        const newImage = {
                            id: this.$props.productId,
                            url: URL.createObjectURL(blob),
                            name: `product_${this.$props.productId}.jpg`,
                        };
                        console.debug('newImage', newImage);

                        this.uploadedImages.push(newImage);
                        this.showMessage(
                            "Фото успішно завантажено!",
                            "success"
                        );
                        this.cancelCrop();
                        this.$emit("image-uploaded", newImage);
                    } catch (error) {
                        this.showMessage("Помилка завантаження", "danger");
                    } finally {
                        this.uploading = false;
                    }
                },
                "image/jpeg",
                0.8
            );
        },

        cancelCrop() {
            this.imageSrc = "";
            if (this.$refs.fileInput) {
                this.$refs.fileInput.value = "";
            }
            this.message = "";
        },

        deleteImage(index) {
            if (confirm("Видалити це фото?")) {
                this.uploadedImages.splice(index, 1);
                this.showMessage("Фото видалено", "success");
            }
        },

        showMessage(text, type) {
            this.message = text;
            this.messageClass = `alert-${type}`;
            setTimeout(() => {
                this.message = "";
            }, 3000);
        },

        setupEventListeners() {
            window.addEventListener("resize", this.resetCropArea);
        },

        removeEventListeners() {
            window.removeEventListener("resize", this.resetCropArea);
        },
    },
};
</script>

<style scoped>
.simple-image-cropper {
    max-width: 100%;
}

.upload-area {
    border: 2px dashed #dee2e6;
    border-radius: 8px;
    padding: 3rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.upload-area:hover {
    border-color: #007bff;
    background-color: #f8f9fa;
}

.crop-container {
    position: relative;
    background: #f8f9fa;
    border-radius: 8px;
    padding: 10px;
    max-height: 500px;
    overflow: hidden;
}

.crop-image {
    display: block;
    max-width: 100%;
    user-select: none;
}

.crop-area {
    position: absolute;
    border: 2px solid #007bff;
    background: rgba(0, 123, 255, 0.1);
    cursor: move;
}

.crop-handles {
    position: absolute;
    width: 100%;
    height: 100%;
}

.handle {
    position: absolute;
    width: 12px;
    height: 12px;
    background: #007bff;
    border: 2px solid white;
    border-radius: 2px;
}

.handle-tl {
    top: -6px;
    left: -6px;
    cursor: nw-resize;
}
.handle-tr {
    top: -6px;
    right: -6px;
    cursor: ne-resize;
}
.handle-bl {
    bottom: -6px;
    left: -6px;
    cursor: sw-resize;
}
.handle-br {
    bottom: -6px;
    right: -6px;
    cursor: se-resize;
}

.controls {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 8px;
}

.preview-container {
    border: 1px solid #dee2e6;
    border-radius: 4px;
    padding: 10px;
    background: white;
    display: inline-block;
}

.preview-canvas {
    display: block;
    border-radius: 4px;
}

.image-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.image-item {
    position: relative;
    display: inline-block;
}

.image-item img {
    width: 100px;
    height: 100px;
    object-fit: cover;
}

.delete-btn {
    position: absolute;
    top: 5px;
    right: 5px;
    width: 24px;
    height: 24px;
    padding: 0;
    border-radius: 50%;
    font-size: 12px;
}

@media (max-width: 768px) {
    .upload-area {
        padding: 2rem 1rem;
    }

    .crop-container {
        max-height: 300px;
    }
}
</style>
