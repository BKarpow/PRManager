<template>
    <div v-if="!isCreatedInvent" class="container">
        <div class="row">
            <div class="col-md-10">
                <div class="mb-2">
                    <h3>Оберіть інвентаризацію</h3>
                    <v-select
                    :options="listInventory"
                    :reduce="name => name.id"
                    label="name"
                    @option:selected="selectInvent"
                    v-model="inventId"
                    placeholder="Оберіть інвентаризацію"
                     />
                </div>
                <!-- /.mb-2 -->
                <h3>Або створіть нову</h3>
                <div class="my-2">

                    <label>Поточна дата {{ curdateformat }}
                    <input type="text" class="form-control" v-model="inventName" placeholder="Ім'я інвентаризації">
                    </label>
                </div>
                <div class="mb-2">
                    <button @click="ceateNewInvent" type="bytton" class="btn btn-success">Створити нову</button>
                </div>
            </div>
            <!-- /.col-md-10 -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container -->
    <div v-if="isCreatedInvent" class="container">
        <div class="row">
            <div class="col-md-6">
                <h3>№({{ inventId }}), {{ inventDate }} - {{ inventName }}
                    <button @click="removeSessionStorage" type="button" class="btn btn-outline-primary btn-sm">&times;</button></h3>
                    <div class="my-2">
                        <h5>Оберіть продукт</h5>
                        <v-select
                    :options="wproductList"
                    :reduce="name => name.id"
                    label="name"
                    v-model="selectProductId"
                    @option:selected="selectProduct"
                    placeholder="Оберіть продукт"
                     />
                    </div>
                    <!-- /.my-2 -->
                    <div class="mb-2">
                        <input type="tel" class="form-control" placeholder="По факту" v-model="value">
                    </div>
                    <!-- /.mb-2 -->
                    <button v-if="isInutValue" @click="storeProductItem" type="button" class="btn btn-success">Внести в інвенту</button>
            </div>
            <!-- /.col-md-6 -->
        </div>
        <!-- /.row -->
        <div v-if="itemsList.length > 0" class="row">
            <div class=" mt-2 table-responsive">
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th>№</th>
                            <th>Продукт</th>
                            <th>Факт</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="i in itemsList" :key="i.id">
                            <td>{{i.id}}</td>
                            <td>{{i.product}}</td>
                            <td>{{i.value}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- /.table-responsive -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container -->
</template>

<script>
import axios from 'axios';

export default {
    data(){
        return {
            isCreatedInvent: false,
            inventId: null,
            inventName: "",
            inventDate: "",
            listInventory: [],
            idSessionStorage: "inventory-one-1",
            wproductList: [],
            selectProductId: null,
            value: "",
            itemsList: [],
        }
    },
    computed:{
        curdateformat() {
            const d = new Date();
            const day = String(d.getDate()).padStart(2, "0");
            const m = String(d.getMonth() + 1).padStart(2, "0");
            return `${day}.${m}.${d.getFullYear()} р.`;
        },
        isInutValue() {
            return this.value > 0;
        }
    },
    methods:{
        ceateNewInvent() {
            axios.post(route('inventory.api.store'), {name: this.inventName}).then(re => {
                this.inventId = re.data.data.id;
                this.inventDate = re.data.data.created;
                this.isCreatedInvent = true;
            }).catch(err => {
                console.error(err);
            })
        },
        getListInventory(){
            axios.get(route('inventory.api.getLastInventory')).then(resp => {
                this.listInventory = resp.data.data;
            })
        },
        selectInvent(i) {
            console.debug("Incent select", i);
            window.sessionStorage.setItem(String(this.idSessionStorage), i.id);
            window.sessionStorage.setItem(String(this.idSessionStorage) + "name", i.name);
            window.sessionStorage.setItem(String(this.idSessionStorage) + "date", i.created);
            this.isCreatedInvent = true;
            this.inventId = i.id;
            this.inventName = i.name;
            this.inventDate = i.created;

        },
        selectProduct(i){
            console.debug("Product select", i);

        },
        storeProductItem(){
            axios.post(route('inventory.api.store.item'), {
                inventory_id: this.inventId,
                wproduct_id: this.selectProductId,
                value: this.value
            }).then(r => {
                console.debug('Response store item', r.data);
                Swal.fire("Додано", 'Новий запис додано', 'success');
                this.getItemsList();
                this.value = "";
                this.selectProductId = null;
            })
        },
        readSessionStorage(){
            let v = window.sessionStorage.getItem(String(this.idSessionStorage));
            if (v != null || v != undefined) {
                this.isCreatedInvent = true;
                this.inventId = v;
                this.getItemsList();
                this.inventName = window.sessionStorage.getItem(String(this.idSessionStorage) + "name");
                this.inventDate = window.sessionStorage.getItem(String(this.idSessionStorage) + "date");
            }
        },
        removeSessionStorage(){
            window.sessionStorage.removeItem(String(this.idSessionStorage));
            window.sessionStorage.removeItem(String(this.idSessionStorage) + "name");
            window.sessionStorage.removeItem(String(this.idSessionStorage) + "date");
            this.isCreatedInvent = false;
            this.inventDate = "";
            this.inventId = null;
            this.inventName = "";
        },
        getListWproduct(){
            axios.get(route('wproduct.getlist')).then(r => {
                this.wproductList = r.data.data;
            }).catch(e => console.error(e));
        },
        getItemsList(){
            axios.post(route('inventory.api.items'),{
                inventory_id: this.inventId,
            }).then(resp => {
                this.itemsList = resp.data.data;
            })
        }
    },
    mounted(){
        console.info("Test1", route('inventory.api.getLastInventory'));
        this.getListInventory();
        this.readSessionStorage();
        this.getListWproduct();
    }
}
</script>
