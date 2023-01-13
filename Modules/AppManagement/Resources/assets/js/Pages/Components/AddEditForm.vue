<template>
    <el-form
        @submit.prevent
        :model="formData"
        :rules="rules"
        label-position="top"
        ref="formRef"
    >
        <el-row :gutter="20">
            <el-col :xs="24" :sm="24">
                <el-card class="box-card h-full">
                    <el-row :gutter="20" class="mb-6">
                        <el-col :xs="24" :sm="12" :md="12">
                            <el-form-item
                                label="Organization / Company Name"
                                prop="title"
                            >
                                <el-input v-model="formData.title" />
                            </el-form-item>
                        </el-col>
                        <el-col :xs="24" :sm="12" :md="12">
                            <el-form-item label="Address" prop="address">
                                <el-input v-model="formData.address" />
                            </el-form-item>
                        </el-col>
                        <el-col :xs="24" :sm="12" :md="12">
                            <el-form-item label="Email" prop="email">
                                <el-input v-model="formData.email" />
                            </el-form-item>
                        </el-col>
                        <el-col :xs="24" :sm="12" :md="12">
                            <el-form-item label="Contact" prop="contact">
                                <el-input
                                    type="number"
                                    v-model="formData.contact"
                                />
                            </el-form-item>
                        </el-col>
                        <el-col :xs="24" :sm="12" :md="24">
                            <el-form-item label="Brand Color" prop="color">
                                <div
                                    class="grow grid grid-cols-1 sm:grid-cols-2"
                                >
                                    <div class="demo-color-block">
                                        <span class="mr-3">Primary</span>
                                        <el-color-picker
                                            v-model="formData.primaryColor"
                                        />
                                    </div>
                                    <div class="demo-color-block">
                                        <span class="mr-3">Primary light</span>
                                        <el-color-picker
                                            v-model="formData.primaryLightColor"
                                        />
                                    </div>
                                    <div class="demo-color-block">
                                        <span class="mr-3">Primary Dark</span>
                                        <el-color-picker
                                            v-model="formData.primaryDarkColor"
                                        />
                                    </div>
                                    <div class="demo-color-block">
                                        <span class="mr-3">Complementary</span>
                                        <el-color-picker
                                            v-model="
                                                formData.complementaryColor
                                            "
                                        />
                                    </div>
                                </div>
                            </el-form-item>
                        </el-col>
                    </el-row>
                    <el-divider class="!mt-0 !mb-4" />
                    <el-row :gutter="20" class="mb-6">
                        <el-col :sm="24" :md="8">
                            <el-form-item
                                label="Application Logo"
                                class="!mb-0"
                                prop="title"
                            >
                                <SingleFileUploader
                                    ref="refLogoUpload"
                                    :acceptExtension="'.jpg, .jpeg, .svg, .png'"
                                    :acceptSize="2048"
                                    :listType="'picture'"
                                    @uplodable="uplodableLogo"
                                    @clearUplodable="uplodableLogo"
                                /> </el-form-item
                        ></el-col>
                        <el-col :sm="24" :md="8"
                            ><el-form-item
                                label="Fav Icon (Light Themed)"
                                prop="title"
                            >
                                <SingleFileUploader
                                    ref="refFavLightUpload"
                                    :acceptExtension="'.png, .svg'"
                                    :acceptSize="150"
                                    :listType="'picture'"
                                    @uplodable="uplodableFavIconLight"
                                    @clearUplodable="uplodableFavIconLight"
                                /> </el-form-item
                        ></el-col>
                        <el-col :sm="24" :md="8">
                            <el-form-item
                                label="Fav Icon (Dark Themed)"
                                prop="title"
                            >
                                <SingleFileUploader
                                    ref="refFavDarkUpload"
                                    :acceptExtension="'.png, .svg'"
                                    :acceptSize="150"
                                    :listType="'picture'"
                                    @uplodable="uplodableFavIconDark"
                                    @clearUplodable="uplodableFavIconDark"
                                />
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
import { Edit } from "@element-plus/icons-vue";
import { useForm } from "@inertiajs/inertia-vue3";
import { genFileId } from "element-plus";
import SingleFileUploader from "@/Components/SingleFileUploader.vue";
//composable import
import { useInertiaPropsUtility } from "@/Composables/inertiaPropsUtility";
import { useObjectUtility } from "@/Composables/objectUtility";
//variable declaration
const formLabelWidth = "140px";
let { iPropsValue } = useInertiaPropsUtility();
let { getObjectRow } = useObjectUtility();
let formRef = $ref();
let FormType = $ref("Add");
let editFormData = $ref(); //default edit form data
const refLogoUpload = $ref(null);
const refFavLightUpload = $ref(null);
const refFavDarkUpload = $ref(null);
const defaultData = $ref(iPropsValue("appInfo"));
watch(
    () => iPropsValue("appInfo"),
    () => {
        defaultData = iPropsValue("appInfo");
    }
);

const uplodableLogo = (file) => {
    formData.logo = file;
};
const uplodableFavIconLight = (file) => {
    formData.favLight = file;
};
const uplodableFavIconDark = (file) => {
    formData.favDark = file;
};
const formData = useForm({
    _method: "PATCH",
    title: "",
    year: "",
    email: "",
    address: "",
    contact: "",
    logo: "",
    favLight: "",
    favDark: "",
    primaryColor: "",
    primaryLightColor: "",
    primaryDarkColor: "",
    complementaryColor: "",
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
    refLogoUpload.clearUploadFile();
    refFavLightUpload.clearUploadFile();
    refFavDarkUpload.clearUploadFile();
    formEl.resetFields();
    formData.reset();
    setTimeout(() => {
        populateFormData(defaultData);
    }, 300);
};
const closeForm = () => {
    resetForm(formRef);
};

const update = function () {
    ElMessageBox.confirm(
        "You are trying to update default application Information. Continue?",
        "Warning",
        {
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
        }
    );
};
let populateFormData = function (data) {
    formData.title = data.title;
    formData.email = data.email;
    formData.contact = data.contact;
    formData.address = data.address;
    formData.primaryColor = data.primary_color;
    formData.primaryLightColor = data.primary_light_color;
    formData.primaryDarkColor = data.primary_dark_color;
    formData.complementaryColor = data.complementary_color;
    const logo = getObjectRow(data.media, "collection_name", "logo");
    const fav_light = getObjectRow(
        data.media,
        "collection_name",
        "fav-icon-light"
    );
    const fav_dark = getObjectRow(
        data.media,
        "collection_name",
        "fav-icon-dark"
    );
    if (logo.length > 0) {
        refLogoUpload.fileList = [
            {
                name: logo[0].file_name,
                url: logo[0].original_url,
            },
        ];
    }
    if (fav_light.length > 0) {
        refFavLightUpload.fileList = [
            {
                name: fav_light[0].file_name,
                url: fav_light[0].original_url,
            },
        ];
    }
    if (fav_dark.length > 0) {
        refFavDarkUpload.fileList = [
            {
                name: fav_dark[0].file_name,
                url: fav_dark[0].original_url,
            },
        ];
    }
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
.demo-color-block {
    margin-right: 10px;
}
</style>
