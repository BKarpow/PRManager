<template>
    <Select
        v-if="loadShops"
        :items="shops"
        :value="userInfo.defaultShop"
        label="Оберіть ваш магазин"
        @select:change="selectShop"
    />
    <Select
        v-if="selectShopFlag"
        :items="groups"
        :value="userInfo.defaultGroup"
        label="Оберіть ваш відділ"
        @select:change="selectGroup"
    />
    <input type="hidden" name="shopid" :value="selectShopId" />
    <input type="hidden" name="groupid" :value="selectGroupId" />
</template>

<script>
import Select from "./Select.vue";
export default {
    components: {
        Select,
    },
    data() {
        return {
            loadShops: false,
            shops: {},
            loadGroups: false,
            groups: {},
            selectShopFlag: false,
            selectShopId: 0,
            selectGroupId: 0,
            userInfo: {},
        };
    },
    methods: {
        selectShop(s) {
            console.debug("Select shop:", s);

            this.selectShopId = Number(s);
            this.selectShopFlag = false;
            axios.get(`/api/groups?shopid=${this.selectShopId}`).then((resp) => {
                if (resp.status == 200) {
                    this.selectShopFlag = true;
                    this.groups = resp.data.data;
                }
            });

        },
        selectGroup(s) {
            console.debug("Select group:", s);
            this.selectGroupId = Number(s);

        },
        getShops() {
            this.loadShops = false;
            axios.get("/api/shops").then((resp) => {
                if (resp.status == 200) {
                    this.loadShops = true;
                    this.shops = resp.data.data;
                }
            });
        },
        getInfo() {
            this.loadShops = false;
            axios.get("/options/info").then((resp) => {
                if (resp.status == 200) {
                    this.loadShops = true;
                    this.userInfo = resp.data.data;
                }
            });
        },
    },
    mounted() {
        this.getInfo();
        this.getShops();
    },
};
</script>
