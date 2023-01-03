<template>
    <el-dialog
        v-model="FormVisible"
        :before-close="closeForm"
        :title="FormType + ' {Controller}'"
    >
        <template #default>
            <el-form
                @submit.prevent
                :model="formData"
                :rules="rules"
                ref="refForm"
                :label-position="isScreenMd ? 'top' : 'right'"
                :scroll-to-error="true"
                status-icon
            >
                <el-form-item
                    label="Name"
                    :label-width="formLabelWidth"
                    prop="name"
                    :error="formErrors.name"
                >
                    <el-input
                        v-model="formData.name"
                        :formatter="
                            (value) =>
                                value.replace(
                                    /(^\w{1})|(\s+\w{1})/g,
                                    (letter) => letter.toUpperCase()
                                )
                        "
                        autocomplete="off"
                    />
                </el-form-item>
            </el-form>
        </template>
        <template #footer>
            <span class="dialog-footer">
                <el-button @click="closeForm()">Cancel</el-button>
                <el-button
                    type="primary"
                    :loading="formData.processing"
                    @click="submitForm(refForm)"
                    >{{ FormType == "Add" ? "Add" : "Update" }}</el-button
                >
            </span>
        </template>
    </el-dialog>
</template>
<script setup>
//library import
import { reactive, markRaw } from "@vue/runtime-core";
import { Edit, Search } from "@element-plus/icons-vue";
import { useForm } from "@inertiajs/inertia-vue3";
//composable import
import { useInertiaPropsUtility } from "@/Composables/inertiaPropsUtility";
import { useAppUtility } from "@/Composables/appUtiility";
//variable declaration
const { iPropsValue } = useInertiaPropsUtility();
const { isScreenMd } = useAppUtility();
const FormType = $ref("Add");
const refForm = $ref();
const FormVisible = $ref(false);
const formLabelWidth = "140px";
const editFormData = $ref(); //default edit form data
const props = defineProps({
    parentFormInput: Object,
});
const formData = useForm({
    _method: "POST",
    ...props.parentFormInput,
    id: "",
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
const formErrors = reactive({
    name: null,
});
const loadServerValidationError = () => {
    Object.assign(formErrors, formData.errors);
};
const clearServerValidationError = () => {
    for (const key in formErrors) {
        formErrors[key] = null;
    }
};
const resetForm = (formEl) => {
    if (!formEl) return;
    formEl.resetFields();
    formData.reset();
};
const closeForm = () => {
    FormVisible = false;
    resetForm(refForm);
    formData.reset();
};
const showForm = function (formType, data = "") {
    FormVisible = true;
    formData.reset();
    FormType = formType;
    if (formType === "Add") {
    }
    if (formType === "Edit") {
        editFormData = data;
        populateFormData(data);
    }
};
const populateFormData = function (data) {
    Object.assign(formData, data);
};
const submitForm = async (formEl) => {
    if (!formEl) return;
    await formEl.validate((valid, fields) => {
        if (valid) {
            if (FormType == "Add") {
                formData._method = "POST";
                create();
            } else {
                formData._method = "PATCH";
                update();
            }
        } else {
            ElNotification({
                title: "Warning",
                message:
                    "Form validation Error, please check before submitting!",
                type: "warning",
            });
            console.log("error submit!", fields);
        }
    });
};
const create = async function () {
    ElMessageBox.confirm("You are trying to Add. Continue?", "Warning", {
        type: "warning",
        icon: markRaw(Edit),
        callback: (action) => {
            if (action == "confirm") {
                clearServerValidationError();
                formData.post(route("{routeName}.store"), {
                    preserveScroll: true,
                    onSuccess: () => {
                        closeForm();
                    },
                    onError: (errors) => {
                        loadServerValidationError();
                    },
                });
            }
        },
    });
};
const update = function () {
    ElMessageBox.confirm("You are trying to edit. Continue?", "Warning", {
        type: "warning",
        icon: markRaw(Edit),
        callback: (action) => {
            if (action == "confirm") {
                clearServerValidationError();
                try {
                    formData.post(
                        route("{routeName}.update", editFormData.id),
                        {
                            preserveScroll: true,
                            onSuccess: () => {
                                closeForm();
                            },
                            onError: (errors) => {
                                loadServerValidationError();
                            },
                        }
                    );
                } catch (error) {
                    ElNotification({
                        title: "Error",
                        message: "Request Form Error.",
                        type: "error",
                    });
                    console.log(error);
                }
            }
        },
    });
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
