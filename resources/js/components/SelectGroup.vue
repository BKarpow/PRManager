<template>

    <Select
        v-if="loadGroups"
        :items="groups"
        :value="defaultGroup"
        label="Оберіть ваш відділ"
        @select:change="selectGroup"
    />

    <input type="hidden" name="group_id" :value="selectGroupId" />
</template>

<script>
import Select from "./Select.vue";
export default {
    props:{
        defaultGroup:{
            type: Number,
            requree: true
        },
    },
    components: {
        Select,
    },
    data() {
        return {
            loadGroups: false,
            groups: {},
            selectGroupId: 0,
            userInfo: {},
        };
    },
    methods: {

        selectGroup(s) {
            console.debug("Select group:", s);
            this.selectGroupId = Number(s);
            this.$emit('select:group', this.selectGroupId);

        },
        getGroups() {
            this.loadGroups = false;
            axios.get("/group/info").then((resp) => {
                if (resp.status == 200) {
                    this.loadGroups = true;
                    this.groups = resp.data.data;
                }
            });
        },

    },
    mounted() {

        this.getGroups();
    },
};
</script>
