<script setup>
import TextInput from "@/Components/Form/TextInput.vue";
import {ref, watch} from "vue";
import {debounce, omit} from "lodash";
import {router} from "@inertiajs/vue3";

const keyword = ref(route().params['keyword'] ?? '');

watch(
    () => keyword.value,
    debounce(val => {
        const query = {
            ...(omit(route().params, ['keyword'])),
            page: 1,
        };

        if (Boolean(val)) query['keyword'] = val;

        router.get(route(route().current(), query));
    }, 300),
);
</script>

<template>
    <div class="flex flex-col md:flex-row md:items-center md:space-x-3 space-y-1 md:space-y-0 px-3 md:px-0">
        <TextInput type="search" v-model="keyword" class="text-sm w-full md:w-96" placeholder="Search here..." />
        <slot />
    </div>
</template>
