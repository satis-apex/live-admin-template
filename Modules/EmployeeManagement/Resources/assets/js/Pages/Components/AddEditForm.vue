<template>
    <el-dialog
        v-model="FormVisible"
        :before-close="closeForm"
        :title="FormType + ' Employee'"
    >
        <template #default>
            <el-form
                @submit.prevent
                :model="formData"
                :rules="rules"
                ref="refForm"
                label-position="top"
                :scroll-to-error="true"
                status-icon
            >
                <el-row :gutter="10">
                    <el-col :lg="8">
                        <el-form-item
                            label="First Name"
                            prop="first_name"
                            :error="formErrors.first_name"
                        >
                            <el-input
                                v-model="formData.first_name"
                                placeholder="First Name"
                                autocomplete="off"
                            />
                        </el-form-item>
                    </el-col>
                    <el-col :lg="8">
                        <el-form-item label="Middle Name" prop="middle_name">
                            <el-input
                                v-model="formData.middle_name"
                                placeholder="Middle Name"
                                autocomplete="off"
                            />
                        </el-form-item>
                    </el-col>
                    <el-col :lg="8">
                        <el-form-item
                            label="Last Name"
                            prop="last_name"
                            :error="formErrors.last_name"
                        >
                            <el-input
                                v-model="formData.last_name"
                                placeholder="Last Name"
                                autocomplete="off"
                            />
                        </el-form-item>
                    </el-col>
                </el-row>
                <el-row :gutter="10">
                    <el-col :lg="12">
                        <el-form-item
                            label="Email"
                            prop="email"
                            :error="formErrors.email"
                        >
                            <el-input
                                v-model="formData.email"
                                placeholder="Email"
                                autocomplete="off"
                            />
                        </el-form-item>
                    </el-col>

                    <el-col :lg="12">
                        <el-form-item
                            label="Gender"
                            prop="gender"
                            :error="formErrors.gender"
                        >
                            <el-radio-group v-model="formData.gender">
                                <el-radio label="Male" />
                                <el-radio label="Female" />
                                <el-radio label="Other" />
                            </el-radio-group>
                        </el-form-item>
                    </el-col>
                </el-row>
                <el-row :gutter="10">
                    <el-col :lg="12">
                        <el-form-item label="Address" prop="address">
                            <el-input
                                v-model="formData.address"
                                placeholder="Address"
                                autocomplete="off"
                            />
                        </el-form-item>
                    </el-col>
                    <el-col :lg="12">
                        <el-form-item label="Phone" prop="phone">
                            <el-input
                                maxlength="10"
                                v-model.number="formData.phone"
                                placeholder="Phone Number"
                                autocomplete="off"
                                show-word-limit
                                :formatter="
                                    (value) => value.replace(/[^\d]/g, '')
                                "
                                :parser="(value) => value.replace(/[^\d]/g, '')"
                            />
                        </el-form-item>
                    </el-col>
                </el-row>
                <el-row :gutter="10">
                    <el-col :lg="12">
                        <el-row>
                            <el-col :span="24">
                                <el-form-item
                                    label="Joined Date"
                                    prop="joined_date"
                                >
                                    <el-date-picker
                                        class="date-picker"
                                        v-model="formData.joined_date"
                                        type="date"
                                        placeholder="Pick a day"
                                        format="YYYY/MM/DD"
                                        value-format="YYYY-MM-DD"
                                        style="width: 100%"
                                    />
                                </el-form-item>
                            </el-col>
                            <el-col>
                                <el-form-item
                                    v-if="FormType.toLowerCase() == 'add'"
                                    label="Create Account"
                                    prop="account_create"
                                >
                                    <el-switch
                                        @change="checkAccountCreate"
                                        v-model="formData.account_create"
                                    />
                                </el-form-item>
                            </el-col>
                            <el-col>
                                <el-form-item
                                    v-if="
                                        formData.account_create || formData.role
                                    "
                                    label="Role"
                                    prop="role"
                                >
                                    <el-select
                                        v-model="formData.role"
                                        placeholder="Select Role"
                                    >
                                        <el-option
                                            v-for="(role, key) in roles"
                                            :key="key"
                                            :label="role.name"
                                            :value="role.name"
                                        />
                                    </el-select>
                                </el-form-item>
                            </el-col>
                        </el-row>
                    </el-col>

                    <el-col :lg="12">
                        <el-form-item label="Profile Picture" prop="user_image">
                            <SingleFileUploader
                                ref="refImageUpload"
                                :acceptExtension="'.jpg, .jpeg'"
                                :acceptSize="2048"
                                :listType="'picture-card'"
                                @uplodable="uplodableImage"
                                @clearUplodable="uplodableImage"
                            />
                        </el-form-item>
                    </el-col>
                </el-row>
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
import { reactive, markRaw, onMounted, watch } from "@vue/runtime-core";
import { Edit, Search } from "@element-plus/icons-vue";
import { useForm } from "@inertiajs/inertia-vue3";
import SingleFileUploader from "@/Components/SingleFileUploader.vue";
//composable import
import { useInertiaPropsUtility } from "@/Composables/inertiaPropsUtility";
import { useAppUtility } from "@/Composables/appUtiility";
//variable declaration
const { iPropsValue } = useInertiaPropsUtility();
const { isScreenMd } = useAppUtility();
const props = defineProps({
    parentFormInput: Object,
    roles: Array,
});
const FormVisible = $ref(false);
const formLabelWidth = "140px";
const refImageUpload = $ref(null);
const refForm = $ref();
const FormType = $ref("Add");
const editFormData = $ref(); //default edit form data
const roles = props.roles;
const existEmail = new Set();
const formData = useForm({
    _method: "POST",
    ...props.parentFormInput,
    account_create: false,
    role: "",
    account_id: "",
    user_image: "",
    previous_role: "",
    id: "",
});
const checkAccountCreate = () => {
    if (formData.account_create) {
        rules.role[0].required = true;
    }
};
const isUniqueEmail = (rule, value, callback) => {
    if (existEmail.has(value.toLowerCase())) {
        callback(new Error("The email has already been taken."));
    } else {
        callback();
    }
};
const rules = reactive({
    first_name: [
        {
            required: true,
            message: "Please input First Name",
            trigger: "blur",
        },
    ],
    last_name: [
        {
            required: true,
            message: "Please input Last Name",
            trigger: "blur",
        },
    ],
    gender: [
        {
            required: true,
            message: "Please select Gender",
            trigger: "change",
        },
    ],
    email: [
        {
            required: true,
            type: "email",
            message: "Please input valid Email",
            trigger: "blur",
        },
        { validator: isUniqueEmail, trigger: "blur" },
    ],
    role: [
        {
            required: false,
            message: "Please assign Role",
            trigger: "change",
        },
    ],
});
const formErrors = reactive({
    first_name: null,
    last_name: null,
    email: null,
    gender: null,
});

const loadServerValidationError = () => {
    Object.assign(formErrors, formData.errors);
    if (formData.errors.email) existEmail.add(formData.email);
};
const clearServerValidationError = () => {
    for (const key in formErrors) {
        formErrors[key] = null;
    }
};
const uplodableImage = (file) => {
    formData.user_image = file;
};
const resetForm = (formEl) => {
    if (!formEl) return;
    refImageUpload.clearUploadFile();
    uplodableImage();
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
    formData.account_id = data.account?.id;
    formData.role = formData.previous_role = data.account?.roles[0].name;
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
                formData.post(route("employee.store"), {
                    preserveScroll: true,
                    onSuccess: () => {
                        existEmail.add(formData.email.toLowerCase());
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
                try {
                    clearServerValidationError();
                    formData.post(route("employee.update", editFormData.id), {
                        preserveScroll: true,
                        onSuccess: () => {
                            if (
                                editFormData.email.toLowerCase() !==
                                formData.email.toLowerCase()
                            ) {
                                existEmail.delete(
                                    editFormData.email.toLowerCase()
                                );
                                existEmail.add(formData.email.toLowerCase());
                            }
                            closeForm();
                        },
                        onError: (errors) => {
                            loadServerValidationError();
                        },
                    });
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
<style lang="scss" scoped>
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
<style lang="scss">
.date-picker {
    .el-input__wrapper {
        width: 100%;
    }
}
</style>
