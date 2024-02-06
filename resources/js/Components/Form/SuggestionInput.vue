<script setup>
import { ref, watch } from "vue";
import { Combobox, ComboboxInput, ComboboxOption, ComboboxOptions } from "@headlessui/vue";
import { debounce } from "lodash";

const model = defineModel({
    type: String,
    required: true,
});

const props = defineProps({
    src: {
        type: String,
        required: true,
    },
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
                class="w-full border-gray-300 focus:border-primary-200 focus:ring-0 rounded-md shadow-sm z-0"
                @change="keyword = $event.target.value"
                autofocus
            />
            <ComboboxOptions class="absolute mt-1 py-1 max-h-60 w-full border rounded-lg bg-white shadow-lg z-10">
                <ComboboxOption
                    v-for="(item, key) in selection"
                    :key="key"
                    :value="item"
                    class="px-3 py-1 cursor-pointer hover:text-primary hover:bg-primary-200"
                >
                    {{ item }}
                </ComboboxOption>
            </ComboboxOptions>
        </Combobox>
    </div>
</template>
