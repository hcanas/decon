<script setup>
import {
    Listbox,
    ListboxButton,
    ListboxOptions,
    ListboxOption,
} from '@headlessui/vue'
import {onMounted, ref, watch} from "vue";
import {find, omit} from "lodash";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {faChevronDown} from "@fortawesome/free-solid-svg-icons";
import {router} from "@inertiajs/vue3";

const props = defineProps({
    options: Object,
    field: String,
    includePage: {
        type: Boolean,
        default: true,
    },
});

const activeOption = ref({});

onMounted(() => {
    activeOption.value = find(props.options, x => x.value === route().params[props.field]) ?? props.options[0];

    watch(() => activeOption.value, option => {
        const query = omit(route().params, [props.field]);

        if (option.value && option.value !== activeOption.value[props.field]) query[props.field] = option.value;

        if (props.includePage) {
            query['page'] = 1;
        }

        router.get(route(route().current(), query));
    });
});
</script>

<template>
    <Listbox v-model="activeOption">
        <div class="relative">
            <ListboxButton class="w-full md:w-fit flex items-center justify-between space-x-2 px-4 py-2 bg-white dark:bg-neutral-900 border border-neutral-300 dark:border-neutral-950 hover:bg-neutral-100 dark:hover:bg-neutral-950 rounded outline-none transition ease-in-out">
                <span class="text-sm">{{ activeOption.label }}</span>
                <FontAwesomeIcon :icon="faChevronDown" class="text-sm" />
            </ListboxButton>

            <Transition
                enter-active-class="transition duration-200 ease-out"
                enter-from-class="translate-y-1 opacity-0"
                enter-to-class="translate-y-0 opacity-100"
                leave-active-class="transition duration-150 ease-in"
                leave-from-class="translate-y-0 opacity-100"
                leave-to-class="translate-y-1 opacity-0"
            >
                <ListboxOptions
                    class="w-full md:w-fit max-h-[24rem] absolute mt-1 z-10 overflow-y-auto rounded-md bg-white dark:bg-neutral-900 py-1 text-base shadow-lg ring-1 ring-black/5 focus:outline-none sm:text-sm"
                >
                    <ListboxOption
                        v-for="option in options"
                        :key="option.value"
                        :value="option"
                        class="w-max px-4 py-1 cursor-pointer text-neutral-800 dark:text-white hover:text-primary dark:hover:text-primary transition ease-in-out"
                        :class="{ 'text-primary': activeOption.value === option.value }"
                    >
                        {{ option.label }}
                    </ListboxOption>
                </ListboxOptions>
            </Transition>
        </div>
    </Listbox>
</template>

