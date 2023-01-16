<template>
    <el-dialog
        v-model="FormVisible"
        :before-close="closeForm"
        title="Add Announcement from Excel"
    >
        <template #default>
            <div class="flex justify-center">
                <p
                    @click="emit('exportTemplate')"
                    class="cursor-pointer hover:underline mb-4 text-primary"
                >
                    <fa icon="file-excel" /> Download Sample Excel Template
                </p>
            </div>
            <ExcelUpload ref="refExcelUpload" :on-success="handleSuccess" />
            <el-table
                :data="tableData"
                border
                highlight-current-row
                style="width: 100%; margin-top: 20px"
                table-layout="auto"
                max-height="200px"
                empty-text="matching data not available"
            >
                <template :key="key" v-for="(item, key) of tableHeader">
                    <el-table-column :prop="item" :label="item" />
                </template>
            </el-table>
        </template>
        <template #footer>
            <span class="dialog-footer">
                <el-button @click="closeForm()">Cancel</el-button>
                <el-button
                    type="primary"
                    :loading="formData.processing"
                    @click="create"
                    >Add</el-button
                >
            </span>
        </template>
    </el-dialog>
</template>
<style scoped>
.el-button--text {
    margin-right: 15px;
}
.el-select {
    width: 300px;
}
.el-input {
    width: 300px;
}
.dialog-footer button:first-child {
    margin-right: 10px;
}
</style>
<script setup>
//library import
import ExcelUpload from "@/Components/UploadExcel.vue";
import { reactive, markRaw } from "@vue/runtime-core";
import { Edit, Search, UploadFilled } from "@element-plus/icons-vue";
import { useForm } from "@inertiajs/inertia-vue3";
//composable import
import { useInertiaPropsUtility } from "@/Composables/inertiaPropsUtility";
import { useObjectUtility } from "@/Composables/objectUtility";
//variable declaration
const FormVisible = $ref(false);
const formLabelWidth = "140px";
let { iPropsValue } = useInertiaPropsUtility();
const { filterObject, resetObjectKey } = useObjectUtility();
let formRef = $ref();
let FormType = $ref("Add");
let editFormData = $ref(); //default edit form data
let tableData = $ref([]);
let refExcelUpload = $ref(null);
let tableHeader = $ref([]);
const props = defineProps({
    parentFormInput: Object,
});
const formData = useForm({
    massRecord: [],
});
const resetForm = (formEl) => {
    tableData = [];
    tableHeader = [];
    refExcelUpload.clearFiles();
    formData.reset();
};
const closeForm = () => {
    FormVisible = false;
    resetForm(formRef);
    formData.reset();
};
const create = async function () {
    if (tableData.length > 0) {
        ElMessageBox.confirm(
            "You are trying to Add Mass Record. Continue?",
            "Warning",
            {
                type: "warning",
                icon: markRaw(Edit),
                callback: (action) => {
                    if (action == "confirm") {
                        formData.post(route("announcement.massStore"), {
                            preserveScroll: true,
                            onSuccess: () => {
                                closeForm();
                            },
                        });
                    }
                },
            }
        );
        return;
    }
    ElMessage.error("Please Upload File first.");
    return;
};
const showForm = function () {
    FormVisible = true;
    formData.reset();
};
let populateFormData = function (data) {
    formData.name = data.name;
};
const handleSuccess = function ({ results, header }) {
    let keys = Object.keys(props.parentFormInput);
    tableData = resetObjectKey(filterObject(results, keys));
    formData.massRecord = tableData;
    tableHeader = keys;
};

const emit = defineEmits(["exportTemplate"]);
defineExpose({
    showForm,
});
</script>
