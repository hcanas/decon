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
        class="dark:bg-neutral-900 border-neutral-300 dark:border-neutral-950 focus:border-primary-300 dark:focus:border-primary focus:ring-0 rounded-md"
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
