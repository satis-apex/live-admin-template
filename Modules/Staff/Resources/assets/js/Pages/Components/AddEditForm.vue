<template>
    <el-dialog
        v-model="FormVisible"
        :before-close="closeForm"
        :title="FormType + ' Staff'"
    >
        <template #default>
            <el-form
                @submit.prevent
                :model="formData"
                :rules="rules"
                ref="formRef"
                :label-position="mobileView ? 'top' : 'right'"
                :scroll-to-error="true"
                status-icon
            >
                <el-form-item
                    label="First Name"
                    :label-width="formLabelWidth"
                    prop="firstName"
                >
                    <el-input
                        v-model="formData.firstName"
                        placeholder="First Name"
                        autocomplete="off"
                    />
                </el-form-item>
                <el-form-item
                    label="Middle Name"
                    :label-width="formLabelWidth"
                    prop="middleName"
                >
                    <el-input
                        v-model="formData.middleName"
                        placeholder="Middle Name"
                        autocomplete="off"
                    />
                </el-form-item>
                <el-form-item
                    label="Last Name"
                    :label-width="formLabelWidth"
                    prop="lastName"
                >
                    <el-input
                        v-model="formData.lastName"
                        placeholder="Last Name"
                        autocomplete="off"
                    />
                </el-form-item>
                <el-form-item
                    label="Email"
                    :label-width="formLabelWidth"
                    prop="email"
                >
                    <el-input
                        v-model="formData.email"
                        placeholder="Email"
                        autocomplete="off"
                    />
                </el-form-item>
                <el-form-item
                    label="Phone"
                    :label-width="formLabelWidth"
                    prop="phone"
                >
                    <el-input
                        v-model.number="formData.phone"
                        placeholder="Phone Number"
                        autocomplete="off"
                    />
                </el-form-item>
                <el-form-item
                    label="Address"
                    :label-width="formLabelWidth"
                    prop="address"
                >
                    <el-input
                        v-model="formData.address"
                        placeholder="Address"
                        autocomplete="off"
                    />
                </el-form-item>
                <el-form-item
                    label="Gender"
                    :label-width="formLabelWidth"
                    prop="gender"
                >
                    <el-radio-group v-model="formData.gender">
                        <el-radio label="Male" />
                        <el-radio label="Female" />
                        <el-radio label="Other" />
                    </el-radio-group>
                </el-form-item>
                <el-form-item
                    label="Joined Date"
                    :label-width="formLabelWidth"
                    prop="joinedDate"
                >
                    <el-date-picker
                        v-model="formData.joinedDate"
                        type="date"
                        placeholder="Pick a day"
                        format="YYYY/MM/DD"
                        value-format="YYYY-MM-DD"
                    />
                </el-form-item>
                <el-form-item
                    v-if="FormType.toLowerCase() == 'add'"
                    label="Create Account"
                    :label-width="formLabelWidth"
                    prop="accountCreate"
                >
                    <el-switch
                        @change="accountCreateCheck"
                        v-model="formData.accountCreate"
                    />
                </el-form-item>
                <el-form-item
                    v-if="
                        FormType.toLowerCase() == 'add' &&
                        formData.accountCreate
                    "
                    label="Role"
                    :label-width="formLabelWidth"
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
                <el-form-item
                    label="Profile Picture"
                    :label-width="formLabelWidth"
                    prop="userImage"
                >
                    <SingleFileUploader
                        ref="refImageUpload"
                        :acceptExtension="'.jpg, .jpeg'"
                        :acceptSize="2048"
                        :listType="'picture-card'"
                        @uplodable="uplodableImage"
                        @clearUplodable="uplodableImage"
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
                    @click="submitForm(formRef)"
                    >{{ FormType == "Add" ? "Add" : "Update" }}</el-button
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
import { reactive, markRaw, onMounted } from "@vue/runtime-core";
import { Edit, Search } from "@element-plus/icons-vue";
import { useForm } from "@inertiajs/inertia-vue3";
import SingleFileUploader from "@/Components/SingleFileUploader.vue";
//composable import
import { useInertiaPropsUtility } from "@/Composables/inertiaPropsUtility";
import { useAppUtility } from "@/Composables/appUtiility";
//variable declaration
const FormVisible = $ref(false);
const formLabelWidth = "140px";
let { iPropsValue } = useInertiaPropsUtility();
const { mediaCheck } = useAppUtility();
let refImageUpload = $ref(null);
let formRef = $ref();
let FormType = $ref("Add");
let editFormData = $ref(); //default edit form data
const props = defineProps({
    parentFormInput: Object,
    roles: Array,
});
const roles = props.roles;
const mobileView = $ref(mediaCheck("md"));

const formData = useForm({
    _method: "POST",
    firstName: "",
    middleName: "",
    lastName: "",
    phone: "",
    gender: "",
    email: "",
    address: "",
    joinedDate: "",
    accountCreate: false,
    role: "",
    userImage: "",
});
const rules = reactive({
    firstName: [
        {
            required: true,
            message: "Please input First Name",
            trigger: "blur",
        },
    ],
    lastName: [
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
    ],
    role: [
        {
            required: false,
            message: "Please assign Role",
            trigger: "change",
        },
    ],
});
const accountCreateCheck = () => {
    if (formData.accountCreate) {
        rules.role[0].required = true;
    }
};
const uplodableImage = (file) => {
    formData.userImage = file;
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
const resetForm = (formEl) => {
    if (!formEl) return;
    refImageUpload.clearUploadFile();
    uplodableImage();
    formEl.resetFields();
    formData.reset();
};
const closeForm = () => {
    FormVisible = false;
    resetForm(formRef);
    formData.reset();
};
const create = async function () {
    ElMessageBox.confirm("You are trying to Add. Continue?", "Warning", {
        type: "warning",
        icon: markRaw(Edit),
        callback: (action) => {
            if (action == "confirm") {
                formData.post(route("staff.store"), {
                    preserveScroll: true,
                    onSuccess: () => {
                        closeForm();
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
                    formData.post(route("staff.update", editFormData.id), {
                        preserveScroll: true,
                        onSuccess: () => {
                            closeForm();
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
let populateFormData = function (data) {
    formData.firstName = data.first_name;
    formData.middleName = data.middle_name;
    formData.lastName = data.last_name;
    formData.phone = data.phone;
    formData.gender = data.gender;
    formData.email = data.email;
    formData.address = data.address;
    formData.joinedDate = data.joined_date;
};
onMounted(() => {
    window.addEventListener("resize", () => {
        mobileView = mediaCheck("md");
    });
});
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
