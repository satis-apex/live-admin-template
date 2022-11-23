<template>
    <el-form
        @submit.prevent
        :model="formData"
        :rules="rules"
        label-position="top"
        ref="formRef"
    >
        <el-row :gutter="20">
            <el-col :xs="24" :sm="8">
                <el-card class="box-card h-full">
                    <el-form-item label="Application Logo" prop="title">
                        <SingleFileUploader
                            :acceptExtension="'.jpg, .jpeg, .svg'"
                            :acceptSize="2048"
                            :listType="'picture'"
                            @uplodable="uplodableLogo"
                            @clearUplodable="uplodableLogo"
                        />
                    </el-form-item>
                    <el-divider class="!mt-0" />
                    <el-form-item
                        label="Application Nemonic / Fav Icon"
                        prop="title"
                    >
                        <SingleFileUploader
                            :acceptExtension="'.png, .svg'"
                            :acceptSize="150"
                            :listType="'picture'"
                            @clearUplodable="uplodableFavIcon"
                        />
                    </el-form-item>
                </el-card>
            </el-col>
            <el-col :xs="24" :sm="16">
                <el-card class="box-card h-full">
                    <el-row :gutter="20">
                        <el-col :sm="24" :md="12">
                            <el-form-item
                                label="Organization / Company Name"
                                prop="title"
                            >
                                <el-input v-model="formData.title" />
                            </el-form-item>
                        </el-col>
                        <el-col :sm="24" :md="12">
                            <el-form-item label="Address" prop="address">
                                <el-input v-model="formData.address" />
                            </el-form-item>
                        </el-col>
                        <el-col :sm="24" :md="12">
                            <el-form-item label="Email" prop="email">
                                <el-input v-model="formData.email" />
                            </el-form-item>
                        </el-col>
                        <el-col :sm="24" :md="12">
                            <el-form-item label="Contact" prop="contact">
                                <el-input v-model="formData.contact" />
                            </el-form-item>
                        </el-col>
                        <el-col :sm="24" :md="12">
                            <el-form-item
                                label="Brand Color"
                                prop="primaryColor"
                            >
                                <div class="demo-color-block">
                                    <span class="mr-3">Primary Color</span>
                                    <el-color-picker
                                        show-alpha
                                        v-model="formData.primaryColor"
                                    />
                                </div>
                            </el-form-item>
                        </el-col>
                    </el-row>
                    <span class="card-footer">
                        <el-button
                            type="primary"
                            :loading="formData.processing"
                            @click="submitForm(formRef)"
                            >Update</el-button
                        >
                        <el-button @click="closeForm()">Cancel</el-button>
                    </span>
                </el-card>
            </el-col>
        </el-row>
    </el-form>
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
import { reactive, markRaw, watch, onMounted } from "@vue/runtime-core";
import { Edit, Search } from "@element-plus/icons-vue";
import { useForm } from "@inertiajs/inertia-vue3";
import { genFileId } from "element-plus";
import SingleFileUploader from "@/Components/SingleFileUploader.vue";
//composable import
import { useInertiaPropsUtility } from "@/Composables/inertiaPropsUtility";
//variable declaration
const formLabelWidth = "140px";
let { iPropsValue } = useInertiaPropsUtility();
let formRef = $ref();
let FormType = $ref("Add");
let editFormData = $ref(); //default edit form data
const props = defineProps({
    parentDefaultData: Object,
});
const defaultData = $ref(props.parentDefaultData);
watch(
    () => props.parentDefaultData,
    () => {
        defaultData = props.parentDefaultData;
    }
);
const uplodableLogo = (file) => {
    console.log(file);
};
const uplodableFavIcon = (file) => {
    console.log(file);
};
const formData = useForm({
    _method: "PATCH",
    title: "",
    year: "",
    email: "",
    address: "",
    logo: "",
    fav: "",
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
const submitForm = async (formEl) => {
    if (!formEl) return;
    await formEl.validate((valid, fields) => {
        if (valid) {
            update();
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
    formEl.resetFields();
    formData.reset();
    populateFormData(defaultData);
};
const closeForm = () => {
    resetForm(formRef);
};

const update = function () {
    ElMessageBox.confirm("You are trying to edit. Continue?", "Warning", {
        type: "warning",
        icon: markRaw(Edit),
        callback: (action) => {
            if (action == "confirm") {
                try {
                    formData.post(route("appSetting.update"), {
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
let populateFormData = function (data) {
    formData.title = data.title;
    formData.email = data.email;
    formData.contact = data.contact;
    formData.address = data.address;
};

const logoFilePreview = $ref(null);
const favFilePreview = $ref(null);

const showForm = function () {
    avatarUploadVisible = true;
};

const logoUpload = function (file, response) {
    if (beforeLogoUpload(file.raw)) {
        logoFilePreview = URL.createObjectURL(file.raw);
        formData.logo = file.raw;
        return;
    }
    refLogoUpload.clearFiles();
};
const beforeLogoUpload = function (uploadFile) {
    const file = uploadFile;
    const isJPG = file.type === "image/jpeg";
    const isLt2M = file.size / 1024 / 1024 < 2;

    if (!isJPG) {
        ElMessage.error("Logo must be JPG format!");
        return false;
    }
    if (!isLt2M) {
        ElMessage.error("Logo size can not exceed 2MB!");
        return false;
    }
    return true;
};

const favUpload = function (file, response) {
    if (beforeFavUpload(file.raw)) {
        favFilePreview = URL.createObjectURL(file.raw);
        formData.fav = file.raw;
        return;
    }
    refFavUpload.clearFiles();
};
const beforeFavUpload = function (uploadFile) {
    const file = uploadFile;
    const isJPG = file.type === "image/png";
    const isLt2M = file.size / 1024 / 1024 < 2;

    if (!isJPG) {
        ElMessage.error("Fav Icon must be JPG format!");
        return false;
    }
    if (!isLt2M) {
        ElMessage.error("Fav Icon size can not exceed 2MB!");
        return false;
    }
    return true;
};
const refLogoUpload = $ref();
const refFavUpload = $ref();

const handleLogoExceed = (files) => {
    refLogoUpload.clearFiles();
    const file = files[0];
    file.uid = genFileId();
    refLogoUpload.handleStart(file);
};
const handleFavExceed = (files) => {
    refFavUpload.clearFiles();
    const file = files[0];
    file.uid = genFileId();
    refFavUpload.handleStart(file);
};

onMounted(() => {
    populateFormData(defaultData);
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
<style scoped>
.card-footer {
    position: absolute;
    bottom: 20px;
}
</style>
