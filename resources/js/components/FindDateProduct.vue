<template>
    <div v-if="!showResult" class="row my-1">
        <div v-if="!fi" class="my-1">
            <button type="button" @click="fi = !fi" class="btn btn-outline-primary"><i class="bi bi-upc-scan"></i> Пошук по ШК </button>
        </div>
        <!-- /.my-1 -->
        <InputBarcode @valid="validBarcode" v-if="fi" />
    </div>
    <!-- /.row my-1 -->
    <div v-if="showResult" class="rom my-1">
        <div  class="mb-1">
            <button type="button" @click="backToFind" class="btn btn-outline-danger btn-sm">
                <i class="bi bi-x-octagon-fill"></i> Шукати ще
                </button>
        </div>
    <div class="mb-2">
        <span class="display-4">
            {{ products[0].name }}
        </span>
        <!-- /.display-1 --></div>
    <!-- /.mb-2 -->
        <ul class="list-group">
            <li v-for="item in products" :key="item.id" class="list-group-item">
                <span class=" display-1">{{ item.end }}</span> ({{ item.daysToExp }} днів)
            </li>
            <!-- /.list-group-item -->
        </ul>
        <!-- /.list-group -->
    </div>
    <!-- /.rom my-1 -->
</template>

<script>
    import InputBarcode from "./InputBarcode.vue";
    export default {
        name: "FindDateProduct",
        components: {
        InputBarcode
    },
        data() {
            return {
                products: [],
                fi: false,
                barcode: "",
                showResult: false,
            }
        },
        methods:{
            validBarcode(b) {
                this.barcode = b;
                this.showResult = true;
                this.getData();
            },
            getData() {
                axios.post(route('date.search.barcode'), {barcode: this.barcode}).then(res => {
                    this.products = res.data.data;
                })
            },
            backToFind(){
                this.products = [];
                this.showResult = false;
                this.fi = false;
            }
        }
    }
</script>
