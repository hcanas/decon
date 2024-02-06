<script setup>
import { ref, watch } from 'vue';
import { Switch, SwitchLabel, SwitchGroup } from '@headlessui/vue';
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { faSun, faMoon } from "@fortawesome/free-solid-svg-icons";

const enabled = ref(localStorage.theme === 'dark');

watch(() => enabled.value, () => {
    if (enabled.value) {
        localStorage.theme = 'dark';
        document.documentElement.classList.add('dark');
    } else {
        localStorage.theme = 'light';
        document.documentElement.classList.remove('dark');
    }
});
</script>

<template>
    <SwitchGroup>
        <div class="flex items-baseline space-x-1">
            <SwitchLabel>
                <FontAwesomeIcon :icon="faSun" class="w-5 h-5 text-gray-300" />
            </SwitchLabel>
            <Switch
                v-model="enabled"
                :class="enabled ? 'bg-gray-900' : 'bg-gray-300'"
                class="relative inline-flex h-5 w-14 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus-visible:ring-2 focus-visible:ring-white/75"
            >
                <span
                    aria-hidden="true"
                    :class="enabled ? 'translate-x-9' : 'translate-x-0'"
                    class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow-lg ring-0 transition duration-200 ease-in-out"
                />
            </Switch>
            <SwitchLabel>
                <FontAwesomeIcon :icon="faMoon" class="w-5 h-5 text-gray-900" />
            </SwitchLabel>
        </div>
    </SwitchGroup>
</template>
