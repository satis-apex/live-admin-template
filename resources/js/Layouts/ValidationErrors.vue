<template>
    <el-alert
        title="Whoops! Something went wrong."
        class="!mb-3 !-mt-6"
        type="error"
        v-if="hasErrors"
    >
        <ul class="mt-3 list-disc list-inside text-sm">
            <li v-for="(error, key) in errors" :key="key">{{ error }}</li>
        </ul>
    </el-alert>
</template>
<script setup>
import { useStringUtility } from "@/Composables/stringUtility";
import { computed, watch } from "@vue/runtime-core";
const { readableWord } = useStringUtility();
const props = defineProps({
    errors: Object,
});
const hasErrors = computed(() => {
    return props.errors ? Object.keys(props.errors).length > 0 : false;
});
</script>
