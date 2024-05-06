<script setup>
import { ref, watch } from "vue";
import { Combobox, ComboboxInput, ComboboxOption, ComboboxOptions } from "@headlessui/vue";
import { debounce } from "lodash";

const model = defineModel({
    type: String,
    required: true,
});

const props = defineProps({
    src: String,
    disabled: Boolean,
});

const keyword = ref('');
const selection = ref([]);

watch(keyword, () => {
    selection.value = [];
    model.value = keyword.value;
});

watch(
    () => keyword.value,
    debounce(() => {
        if (keyword.value !== '') {
            window.axios.get(props.src + '?keyword=' + keyword.value)
                .then(response => selection.value = response.data)
                .catch(() => selection.value = []);
        }
    }, 300),
);
</script>

<template>
    <div class="relative">
        <Combobox v-model="model">
            <ComboboxInput
                class="w-full dark:bg-neutral-900 border-neutral-300 dark:border-neutral-950 focus:border-primary-300 dark:focus:border-primary focus:ring-0 rounded-md z-0"
                :class="{ 'bg-neutral-100 cursor-not-allowed': disabled }"
                @change="keyword = $event.target.value"
                :disabled="disabled"
                autofocus
            />
            <ComboboxOptions class="absolute mt-1 py-1 max-h-60 w-full rounded-lg bg-white dark:bg-neutral-900 shadow-lg z-10">
                <ComboboxOption
                    v-for="(item, key) in selection"
                    as="template"
                    :key="key"
                    :value="item"
                    v-slot="{ active }"
                >
                    <li class="relative text-sm px-3 py-1 cursor-pointer text-neutral-800 dark:text-white hover:text-white hover:bg-primary" :class="{ 'text-white bg-primary': active }">
                        {{ item }}
                    </li>
                </ComboboxOption>
            </ComboboxOptions>
        </Combobox>
    </div>
</template>
