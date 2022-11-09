<template>
    <el-dialog
        v-model="FormVisible"
        :before-close="closeForm"
        title="Add {Module} from Excel"
    >
        <template #default>
            <ExcelUpload ref="refExcelUpload" :on-success="handleSuccess" />
            <el-table
                :data="tableData"
                border
                highlight-current-row
                style="width: 100%; margin-top: 20px"
                table-layout="auto"
                max-height="200px"
            >
                <el-table-column
                    v-for="item of tableHeader"
                    :key="item"
                    :prop="item"
                    :label="item"
                />
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
//variable declaration
const FormVisible = $ref(false);
const formLabelWidth = "140px";
let { iPropsValue } = useInertiaPropsUtility();
let formRef = $ref();
let FormType = $ref("Add");
let editFormData = $ref(); //default edit form data
let tableData = $ref([]);
let refExcelUpload = $ref(null);
let tableHeader = $ref([]);
const formData = useForm({
    massRecord: [],
});
const rules = reactive({
    name: [
        {
            required: true,
            message: "Please Input Name",
            trigger: "blur",
        },
    ],
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
                        formData.post(route("{routeName}.massStore"), {
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
    tableData = results;
    formData.massRecord = results;
    tableHeader = header;
};

defineExpose({
    showForm,
});
</script>
<style scoped>
li.icon {
    height: 50px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-right: 1px solid var(--el-border-color);
    border-bottom: 1px solid var(--el-border-color);
}
li.icon:hover {
    cursor: pointer;
    background: rgb(226, 226, 226);
}
ul.icon-picker {
    border-top: 1px solid var(--el-border-color);
    border-left: 1px solid var(--el-border-color);
}
</style>
