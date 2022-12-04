<script setup>
import BreezeApplicationLogo from "@/Components/ApplicationLogo.vue";
import { Link } from "@inertiajs/inertia-vue3";
</script>

<template>
    <div
        class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-tr from-emerald-300 via-sky-300 to-blue-400"
    >
        <div>
            <Link href="/">
                <BreezeApplicationLogo class="h-16 fill-current text-black" />
            </Link>
        </div>

        <div
            class="w-full sm:max-w-md mt-6 px-6 py-4 bg-emerald-800/20 backdrop-blur-lg shadow-lg overflow-hidden sm:rounded-lg"
        >
            <slot />
        </div>
    </div>
</template>
