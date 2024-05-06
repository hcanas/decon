<script setup>
import { onMounted, ref } from 'vue';

const model = defineModel({
    type: String,
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
        class="dark:bg-neutral-900 border-neutral-300 dark:border-neutral-950 focus:border-primary-300 dark:focus:border-primary focus:ring-0 rounded-md"
        v-model="model"
        ref="input"
    />
</template>
