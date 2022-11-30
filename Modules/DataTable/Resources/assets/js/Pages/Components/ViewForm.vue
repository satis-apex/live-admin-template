<template>
    <el-dialog
        v-model="FormVisible"
        :before-close="closeForm"
        title="View DataTable"
    >
        <template #default> {{ viewData }}</template>
    </el-dialog>
</template>
<script setup>
const FormVisible = $ref(false);
let viewData = $ref();
const closeForm = () => {
    FormVisible = false;
};
const showForm = function (data) {
    FormVisible = true;
    viewData = data;
};
defineExpose({
    showForm,
});
</script>
