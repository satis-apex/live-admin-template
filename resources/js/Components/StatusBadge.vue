<template>
    <div
        class="inline-flex items-center px-2 space-x-1 capitalize text-xs rounded-full"
        :class="`status-badge-${type}`"
    >
        <div class="status-badge w-2 h-2 block rounded-full"></div>
        <div><slot /></div>
    </div>
</template>

<script setup>
const props = defineProps({
    type: {
        type: String,
        default: "info",
    },
});
</script>

<style lang="scss" scoped>
.status-badge {
    background: currentColor;
}
.status-badge-success {
    color: var(--el-color-success);
}
.status-badge-danger {
    color: var(--el-color-danger);
}
.status-badge-primary {
    color: var(--el-color-primary);
}
.status-badge-info {
    color: var(--el-color-info);
}
.status-badge-warning {
    color: var(--el-color-warning);
}
</style>
