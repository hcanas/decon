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
    <div class="flex items-center space-x-3">
        <TextInput type="search" v-model="keyword" class="text-sm w-96" placeholder="Search here..." autofocus />
        <slot />
    </div>
</template>
