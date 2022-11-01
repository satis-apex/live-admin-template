<template>
    <h1>{{ iPropsValue("app_info", "title") }}</h1>
</template>
<script setup>
import { useInertiaPropsUtility } from "@/Composables/inertiaPropsUtility";
import { onMounted } from "@vue/runtime-core";
let { iPropsValue } = useInertiaPropsUtility();
</script>
<script>
export default {
    layout: "admin",
    data() {
        return {};
    },
};
</script>
