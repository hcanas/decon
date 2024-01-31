<script setup>
import { onMounted, ref } from 'vue';

const model = defineModel({
    type: [Number, String],
    required: true,
});

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        class="border-gray-300 focus:border-primary-light focus:ring-0 rounded-md shadow-sm"
        v-model="model"
        ref="input"
    />
</template>
