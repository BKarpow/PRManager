<template>
    <label> {{ label }}
    <select @change="ch" class="form-select form-select-lg mb-3" v-model="selected" :aria-label="label">
  <option value="0" disabled>{{ label }}</option>
  <option v-for="item in items" :key="item.id" :value="item.id">{{ item.name }}</option>
</select>
</label>
</template>

<script>
export default {
    props:{
        items: {
            type: Array,
            default: [],
        },
        label: {
            type: String,
            default: ""
        },
        value: {
            type: Number,
            default: 0
        }
    },
    data() {
        return {
            selected: 0
        }
    },
    methods:{
        ch(){
            console.debug("Change select", this.selected);
            this.$emit('select:change', this.selected);
        }
    },
    watch: {
        selected(n, o) {
            console.debug("Select change", n);

        }
    },
    mounted() {
        this.selected = this.value;
    }
}
</script>
