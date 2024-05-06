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

        dispatchEvent(new StorageEvent('storage', {
            key: 'theme',
            newValue: 'dark',
        }));
    } else {
        localStorage.theme = 'light';
        document.documentElement.classList.remove('dark');

        dispatchEvent(new StorageEvent('storage', {
            key: 'theme',
            newValue: 'light',
        }));
    }
});
</script>

<template>
    <SwitchGroup>
        <div class="flex items-baseline space-x-1">
            <SwitchLabel>
                <FontAwesomeIcon :icon="faSun" class="w-4 h-4 text-neutral-800 dark:text-white" />
            </SwitchLabel>
            <Switch
                v-model="enabled"
                :class="enabled ? 'bg-neutral-900' : 'bg-neutral-300'"
                class="relative inline-flex h-4 w-10 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus-visible:ring-2 focus-visible:ring-white/75"
            >
                <span
                    aria-hidden="true"
                    :class="enabled ? 'translate-x-6' : 'translate-x-0'"
                    class="pointer-events-none inline-block h-3 w-3 transform rounded-full bg-white shadow-lg ring-0 transition duration-200 ease-in-out"
                />
            </Switch>
            <SwitchLabel>
                <FontAwesomeIcon :icon="faMoon" class="w-4 h-4 text-neutral-800 dark:text-white" />
            </SwitchLabel>
        </div>
    </SwitchGroup>
</template>
