<template>
    <div class="container">
        <div class="p-1">
            <InputBarcode @valid="validBarcode" @invalid="invalidBarcode" />
            <ImageUploader
                v-if="isBarcodeValid"
                :product-id="barcode"
                @upload="imageUpload"
                @image-uploaded="imageIploaded"
            />
        </div>
        <!-- /.p-1 -->
        <input
            v-if="isBarcodeValid"
            type="hidden"
            name="barcode"
            :value="barcode"
        />
        <div>
            <input
                v-if="imageUrl != ''"
                type="hidden"
                name="imagesrc"
                :value="imageUrl"
            />
        </div>
        <!-- /.mt-2 -->
        <div v-if="isBarcodeValid">
            <div class="mb-2">
                <input
                    type="text"
                    placeholder="Ім'я продукту"
                    name="name"
                    v-model="name"
                    maxlength="250"
                    class="form-control"
                />
            </div>
            <!-- /.form-group -->
            <div class="mb-2">
                <input
                    type="text"
                    placeholder="Опис"
                    name="comment"
                    v-model="comment"
                    maxlength="250"
                    class="form-control"
                />
            </div>
            <!-- /.form-group -->
            <input type="hidden" name="isExistsProduct" v-model="isExistsProduct">
            <input type="hidden" name="idExistsProduct" v-if="isExistsProduct" v-model="idExistsProduct">
            <input type="hidden" name="isExistsImage" v-model="isImageUpload">
            <input type="hidden" name="imageName" v-if="isImageUpload" v-model="imageUrl">
            <div class="mb-2">
                <button class="btn btn-dark btn-lg" type="submit">
                    {{ nameButton }}
                </button>
            </div>
            <!-- /.form-group -->
        </div>
    </div>
    <!-- /.container -->
</template>

<script>
import InputBarcode from "./InputBarcode.vue";
import ImageUploader from "./ImageUploader.vue";

export default {
    name: "CreateNewProduct",
    components: {
        InputBarcode,
        ImageUploader,
    },
    data() {
        return {
            name: '',
            comment: '',
            barcode: "",
            isExistsProduct: false,
            idExistsProduct: null,
            isBarcodeValid: false,
            isImageUpload: false,
            imageUrl: "",
        };
    },
    computed: {
        nameButton() {
            return (this.isExistsProduct) ? "Змінити продукт" : "Додати продукт";
        }
    },
    methods: {
        validBarcode(b) {
            this.barcode = b;
            console.debug("barcode", b);
            this.isBarcodeValid = true;
            this.getApiNameProduct();
        },
        getApiNameProduct() {
            axios.post('/product/getname', {barcode: this.barcode}).then(resp => {
                console.debug("response get name", resp);
                if (resp.status == 200) {
                    this.isExistsProduct = resp.data.isDB;
                    this.name = resp.data.json_response.name;
                    if (resp.data.isDB) {
                        this.idExistsProduct = resp.data.json_response.id;
                        this.comment = resp.data.json_response.comment;
                    }
                }
            })
        },
        invalidBarcode() {
            this.isBarcodeValid = false;
        },
        imageIploaded(n) {
            console.debug("Test new image object 1", n);
            // this.isImageUpload = true;
        },

        imageUpload(n) {
            console.debug("Test new image object 2", n);
            this.isImageUpload = true;
            this.imageUrl = n;
        },
    },
};
</script>
