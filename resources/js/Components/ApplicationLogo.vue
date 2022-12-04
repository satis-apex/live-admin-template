<template>
    <template v-if="!showMnemonic && iPropsValue('app_info', 'brandLogo')">
        <img :src="iPropsValue('app_info', 'brandLogo')" alt="Brand Logo" />
    </template>
    <template
        v-else-if="!showMnemonic && !iPropsValue('app_info', 'brandLogo')"
    >
        Brand Logo
    </template>
    <template v-else-if="showMnemonic && iPropsValue('app_info', 'favIcon')">
        <img :src="iPropsValue('app_info', 'favIcon')" alt="Brand Logo" />
    </template>
    <template v-else-if="showMnemonic && !iPropsValue('app_info', 'favIcon')">
        B
    </template>
</template>
<script setup>
import { useInertiaPropsUtility } from "@/Composables/inertiaPropsUtility";
const { iPropsValue } = useInertiaPropsUtility();
let props = defineProps({
    mnemonic: {
        type: Boolean,
        default: false,
    },
});
const showMnemonic = props.mnemonic;
</script>
