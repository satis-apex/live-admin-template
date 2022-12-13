<template>
    <el-dialog
        v-model="formVisible"
        title="Change Password"
        width="40%"
        :before-close="handleClose"
        ><template #default>
            <el-form
                ref="formDataRef"
                :model="formData"
                :rules="rules"
                label-width="140px"
                :scroll-to-error="true"
                @submit.prevent
            >
                <el-form-item
                    label="Current Password"
                    prop="current_password"
                    :error="formErrors.current_password"
                >
                    <el-input
                        v-model="formData.current_password"
                        type="password"
                        autocomplete="off"
                        show-password
                    />
                </el-form-item>
                <el-form-item
                    label="Password"
                    prop="password"
                    :error="formErrors.password"
                >
                    <el-input
                        v-model="formData.password"
                        type="password"
                        autocomplete="off"
                        show-password
                    />
                </el-form-item>
                <el-form-item label="Confirm" prop="password_confirmation">
                    <el-input
                        v-model="formData.password_confirmation"
                        type="password"
                        autocomplete="off"
                        show-password
                    />
                </el-form-item>
            </el-form>
        </template>
        <template #footer>
            <span class="dialog-footer">
                <el-button @click="handleClose">Cancel</el-button>
                <el-button
                    type="primary"
                    :loading="formData.processing"
                    @click="submitForm(formDataRef)"
                    >Confirm</el-button
                >
            </span>
        </template>
    </el-dialog>
</template>
<script setup>
import { watch, reactive } from "@vue/runtime-core";
import { useForm } from "@inertiajs/inertia-vue3";
import { useInertiaPropsUtility } from "@/Composables/inertiaPropsUtility";
import { useStringUtility } from "@/Composables/stringUtility";
import ValidationErrors from "@/Layouts/ValidationErrors.vue";
let { iPropsValue } = useInertiaPropsUtility();
let { readableWord } = useStringUtility();
const props = defineProps({
    parentFormVisible: Boolean,
});
const emit = defineEmits(["closeForm"]);
const formData = useForm({
    current_password: "",
    password: "",
    password_confirmation: "",
});
let showError = $ref(formData.hasErrors);
let formVisible = $ref(props.parentFormVisible);
watch(
    () => props.parentFormVisible,
    () => {
        formVisible = props.parentFormVisible;
    }
);
const handleClose = function () {
    emit("closeForm");
    resetForm(formDataRef);
};
const formDataRef = $ref();

const validatePass = (rule, value, callback) => {
    if (value === "") {
        callback(new Error("Please input the password"));
    } else {
        if (formData !== "") {
            if (!formDataRef) return;
            formDataRef.validateField("password_confirmation", () => null);
        }
        callback();
    }
};
const validatePass2 = (rule, value, callback) => {
    if (value === "") {
        callback(new Error("Please input the password again"));
    } else if (value !== formData.password) {
        callback(new Error("Two inputs don't match!"));
    } else {
        callback();
    }
};

const rules = reactive({
    current_password: [
        {
            required: true,
            trigger: "blur",
            message: "Please enter current password.",
        },
    ],
    password: [
        { required: true, validator: validatePass, trigger: "blur" },
        {
            min: 8,
            message: "Password must be at-least 8 characters",
            trigger: "blur",
        },
    ],
    password_confirmation: [
        { required: true, validator: validatePass2, trigger: "blur" },
    ],
});
const formErrors = reactive({
    current_password: null,
    password: null,
});
const submitForm = (formEl) => {
    if (!formEl) return;
    formEl.validate((valid, fields) => {
        if (valid) {
            clearServerValidationError();
            formData.patch(route("changePassword"), {
                onFinish: () => {
                    if (formData.hasErrors) {
                        loadServerValidationError();
                    } else {
                        clearServerValidationError();
                        resetForm();
                    }
                },
            });
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
const loadServerValidationError = () => {
    formErrors.current_password =
        formData.errors.changePassword.current_password;
    formErrors.password = formData.errors.changePassword.password;
};
const clearServerValidationError = () => {
    formErrors.current_password = null;
    formErrors.password = null;
};
const resetForm = (formEl) => {
    if (!formEl) return;
    showError = false;
    formData.reset();
    formEl.resetFields();
};
</script>
