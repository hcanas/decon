<script setup>
import { ref } from 'vue';

const model = defineModel({
    type: [Number, String],
    required: true,
});

const input = ref(null);
</script>

<template>
    <input
        class="border-gray-300 focus:border-primary-200 focus:ring-0 rounded-md shadow-sm"
        v-model="model"
        type="number"
        ref="input"
    />
</template>

<style scoped>
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
    -webkit-appearance: none;
}

input[type='number'] {
    -moz-appearance:textfield;
}
</style>
