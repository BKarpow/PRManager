<template>
    <div class="container" :class="{ 'product-exists': dateExists }">
        <div class="row" v-if="loadGroupList">
            <div class="col-md-6">
                <SelectGroup
                    :default-group="groupId"
                    @select:group="selectGroupCh"
                />
            </div>
            <!-- /.col-md-6 -->
        </div>
        <!-- /.row -->
        <div class="p-1">
            <InputBarcode @valid="validBarcode" @invalid="invalidBarcode" />

            <div class="d-flex justify-content-center" v-if="searchImage">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>

            <ImageUploader
                v-if="isBarcodeValid && !isExistsImage"
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
        <div class="m-1 py-1" v-if="imageUrl != ''">
            <h4>Фото продукту</h4>
            <img
                class="rounded img-thumbnail"
                :src="imageUrl"
                alt="Фото товару"
            />
        </div>
        <!-- /.mt-2 -->
        <div v-if="isBarcodeValid">
            <div class="mt-2 mb-2">
                <input
                    type="text"
                    placeholder="Ім'я продукту"
                    title="Ім'я продукту"
                    name="name"
                    v-model="name"
                    maxlength="250"
                    class="form-control big"
                />
            </div>
            <!-- /.form-group -->
            <div class="mb-2">
                <!--
<div class="form-floating">
  <textarea name="comment" v-model="comment" class="form-control" placeholder="Опис продукту" id="floatingTextarea">{{ comment }}</textarea>
  <label for="floatingTextarea">Опис продукту</label>
</div> -->
            </div>
            <!-- /.form-group -->
            <div class="mb-2">
                <InputDate
                    name-input="start"
                    label="Виготовлено: "
                    :value="getCurrentDateFormatted"
                    @valid="initStartDate"
                />
            </div>
            <!-- /.mb-2 -->
            <div class="form-check">
                <label class="form-check-label">
                    Вирахувати кінцеву дату
                    <input
                        class="form-check-input"
                        type="checkbox"
                        value=""
                        v-model="isCountDays"
                    />
                </label>
            </div>
            <!-- /.mb-2 -->

            <div class="mb-2">
                <div v-if="isCountDays" class="mb-1 green-bg">
                    <label
                        >Кількість днів після дати виготовлення:
                        <input
                            type="tel"
                            class="form-control"
                            v-model="countDays"
                            placeholder="Кількість днів після дати виготовлення"
                        />
                    </label>
                </div>
                <div class="green-bg" v-if="!isCountDays">
                    <InputDate @valid="initEndDate" name-input="end" />
                </div>
                <!-- /.green-bg -->
                <InputDate
                    v-if="isCountDays"
                    name-input="end"
                    @valid="initEndDate"
                    :value="countDaysDateComputed"
                />
                <div class="mt-1">Поточна дата: {{ getCurrentDate() }}</div>
                <div class="mt-1">
                    <span
                        class="big"
                        :class="{ red: isExpiredDate, green: !isExpiredDate }"
                    >
                        Залишилося днів: {{ calcDiffDate }}
                        <span v-if="isExpiredDate">ПРОТЕРМІНОВАНО !!!!</span>
                    </span>
                </div>
            </div>
            <!-- /.mb-2 -->
            <div class="mb-2">
                <label>
                    Кількість
                    <input
                        type="tel"
                        name="count"
                        placeholder="Кількість товару"
                        pattern="^[\d]+$"
                        class="form-control big"
                        v-model="count"
                    />
                </label>
            </div>
            <!-- /.mb-2 -->

            <input
                type="hidden"
                name="isExistsProduct"
                v-model="isExistsProduct"
            />
            <input type="hidden" name="group" v-model="groupId" />
            <input type="hidden" name="newProduct" v-model="isNewProduct" />
            <input
                type="hidden"
                name="product_id"
                v-if="isExistsProduct"
                v-model="idExistsProduct"
            />
            <input type="hidden" name="isExistsImage" v-model="isImageUpload" />
            <input
                type="hidden"
                name="isEditProductInfo"
                v-model="isEditProductInfo"
            />
            <input
                type="hidden"
                name="imageName"
                v-if="isImageUpload"
                v-model="imageUrl"
            />

            <div class="mb-2">
                <span
                    class="d-block my-1"
                    style="font-size: 1.5rem; font-weight: bold"
                    v-if="isEditProductInfo"
                >
                    Продукт {{ name }} буде змінено!!!
                </span>
                <div class="alert alert-warning" v-if="name.length < 3">
                    <strong>Ім'я продукту має манше шіж 3 символи.</strong>
                </div>
                <!-- /.alert alert-warning -->
                <button
                    class="btn btn-success btn-lg"
                    type="submit"
                    v-if="name.length >= 3"
                >
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
import InputDate from "./InputDate.vue";
import ImageUploader from "./ImageUploader.vue";
import SelectGroup from "./SelectGroup.vue";
import axios from "axios";

export default {
    name: "CreateNewProduct",
    components: {
        InputBarcode,
        ImageUploader,
        InputDate,
        SelectGroup,
    },
    data() {
        return {
            name: "",
            comment: "",
            hashProductInfo: null,
            barcode: "",
            isExistsProduct: false,
            idExistsProduct: null,
            isBarcodeValid: false,
            isImageUpload: false,
            isExistsImage: false,
            searchImage: false,
            imageUrl: "",
            expDate: "",
            count: null,
            isCountDays: false,
            countDays: null,
            startDate: "",
            diffDateDays: 0,
            expiredDate: "",
            userInfo: {},
            loadGroupList: false,
            groupId: 0,
            dateExists: false,
            isNewProduct: false
        };
    },
    computed: {
        nameButton() {
            return this.isExistsProduct
                ? "Додати термін"
                : "Додати термін та створити продукт";
        },
        getCurrentDateFormatted() {
            // 1. Створюємо об'єкт Date для поточної дати та часу
            const today = new Date();

            // 2. Отримуємо компоненти дати
            const day = today.getDate();
            const month = today.getMonth() + 1; // getMonth() повертає місяці від 0 (січень) до 11 (грудень)
            const year = today.getFullYear().toString().slice(-2);

            // 3. Форматуємо день і місяць, додаючи нуль попереду, якщо число менше 10.
            // Використовуємо метод padStart(2, '0')
            const formattedDay = String(day).padStart(2, "0");
            const formattedMonth = String(month).padStart(2, "0");

            // 4. Збираємо рядок у потрібному форматі дд.мм.рррр
            return (this.startDate = `${formattedDay}.${formattedMonth}.${year}`);
        },
        countDaysDateComputed() {
            const d1 = this.addDaysToDate(
                this.getCurrentDate(),
                this.countDays
            );
            this.diffDateDays = this.dateDifferenceInDays(this.startDate, d1);
            return d1;
        },
        eDate() {
            return this.isCountDays
                ? this.countDaysDateComputed
                : this.expiredDate;
        },
        calcDiffDate() {
            return this.dateDifferenceInDays(
                this.getCurrentDate(),
                String(this.eDate)
            );
        },
        isExpiredDate() {
            return this.calcDiffDate < 0;
        },
        isEditProductInfo() {
            return (
                this.hashProductInfo !=
                hexHash(String(this.name) + String(this.comment))
            );
        },
    },
    methods: {
        getCurrentDate() {
            const now = new Date();

            const day = String(now.getDate()).padStart(2, "0");
            const month = String(now.getMonth() + 1).padStart(2, "0"); // +1 бо місяці з 0
            const year = now.getFullYear().toString().slice(-2);

            return `${day}.${month}.${year}`;
        },

        getExistsImage() {
            this.isExistsImage = false;
            this.searchImage = true;
            axios
                .post("/product/image/exists", { barcode: this.barcode })
                .then((resp) => {
                    this.searchImage = false;
                    this.imageUrl = "";
                    if (resp.status == 200) {
                        this.isExistsImage = resp.data.existsImage;
                        console.debug("Exists image", resp.data);
                        console.debug("Exists image 1", this.isExistsImage);
                        this.imageUrl = resp.data.urlImage;
                    }
                })
                .catch((err) => {
                    console.error("Error get exists image");
                });
        },
        validBarcode(b) {
            this.barcode = b;
            this.getExistsImage();
            console.debug("barcode", b);
            this.isBarcodeValid = true;
            this.getApiNameProduct();
        },
        getApiNameProduct() {
            axios
                .post("/product/getname", { barcode: this.barcode })
                .then((resp) => {
                    console.debug("response get name", resp);
                    if (resp.status == 200) {
                    if (resp.data.status_code == 205) {
                         //Невідомий товар
                        this.isNewProduct = true;
                        return;
                    } 
                        this.isExistsProduct = resp.data.isDB;
                        this.name = resp.data.json_response.name;
                        if (resp.data.isDB) {
                            this.idExistsProduct = resp.data.json_response.id;
                            this.comment = resp.data.json_response.comment;
                        }
                        this.hashProductInfo = hexHash(
                            String(this.name) + String(this.comment)
                        );
                    }
                });
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
        addDaysToDate(dateString, daysToAdd) {
            // Розбиваємо строку на частини
            console.debug("Start date: ", dateString);
            const [day, month, year] = String(this.startDate).split(".");

            // Створюємо об'єкт Date
            const date = new Date(
                2000 + Number(year),
                Number(month) - 1,
                Number(day)
            );

            // Додаємо дні
            date.setDate(date.getDate() + Number(this.countDays));

            // Форматуємо назад у формат дд.мм.рррр
            const newDay = String(date.getDate()).padStart(2, "0");
            const newMonth = String(date.getMonth() + 1).padStart(2, "0");
            const newYear = date.getFullYear().toString().slice(-2);

            return `${newDay}.${newMonth}.${newYear}`;
        },
        dateDifferenceInDays(date1, date2) {
            const parseDate = (dateStr) => {
                let [day, month, year] = dateStr.split(".").map(Number);
                year = String(year).length == 2 ? 2000 + year : year;
                return new Date(2000 + year, month - 1, day);
            };

            const d1 = parseDate(date1);
            const d2 = parseDate(date2);

            const timeDiff = d2.getTime() - d1.getTime();
            return Math.ceil(timeDiff / (1000 * 60 * 60 * 24));
        },
        initStartDate(d) {
            this.startDate = d;
        },
        checkExistsDateProduct() {
            if (!this.isExistsProduct) return;
            this.dateExists = false;
            axios
                .post("/date/info/exists", {
                    product_id: this.idExistsProduct,
                    group_id: this.groupId,
                    end: this.expiredDate,
                })
                .then((resp) => {
                    if (resp.status == 200) {
                        this.dateExists = resp.data.exists;
                        if (this.dateExists)
                            Swal.fire({
                                title: "Такий термін вже додано!",
                                icon: "info",
                                html: `
    Даний термін вже є, при продовженні він буде доданий повторно.
    <a href="/date/show/${resp.data.dateProductId}" autofocus>Переглянути термін</a>,

  `,
                                showCloseButton: true,
                                focusConfirm: false,
                                confirmButtonText: `
    <i class="fa fa-thumbs-up"></i> Зрозуміло!
  `,
                                confirmButtonAriaLabel: "Добре, я зрозумів!",
                                cancelButtonText: `
    <i class="fa fa-thumbs-down"></i>
  `,
                                cancelButtonAriaLabel: "Thumbs down",
                            });
                    }
                });
        },
        initEndDate(e) {
            this.expiredDate = e;
            // this.calcDiffDate();
            this.checkExistsDateProduct();
        },
        getInfo() {
            this.loadGroupList = false;
            axios.get("/options/info").then((resp) => {
                if (resp.status == 200) {
                    this.userInfo = resp.data.data;
                    this.loadGroupList = true;
                    this.groupId = Number(this.userInfo.defaultGroup);
                }
            });
        },
        selectGroupCh(e) {
            this.groupId = e;
        },
    },
    mounted() {
        this.getInfo();
    },
};
</script>

<style lang="css" scoped>
.big {
    font-size: 2.5rem;
    font-weight: bold;
}
.red {
    color: red;
}
.green {
    color: green;
}
.green-bg {
    margin: 0.3rem;
    padding: 0.7rem;
    background: yellowgreen;
    color: black;
    border-radius: 8px;
}
.product-exists {
    background: yellow;
    color: inherit;
    transition: all 1s ease-out;
}
</style>
