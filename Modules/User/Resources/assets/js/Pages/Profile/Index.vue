<template>
    <el-row :gutter="20">
        <el-col :xs="24" :sm="8">
            <el-card class="box-card">
                <div class="card-header relative">
                    <div class="block text-center">
                        <el-avatar
                            :size="130"
                            :src="iPropsValue('auth', 'user.avatar')"
                        >
                            <img
                                src="https://cube.elemecdn.com/e/fd/0fc7d20532fdaf769a25683617711png.png"
                            />
                        </el-avatar>
                    </div>
                    <el-button
                        class="button absolute top-0 right-0"
                        type="primary"
                        :icon="Edit"
                        circle
                        @click="showAvatarUploader()"
                    />
                </div>

                <div class="text-center font-semibold capitalize mt-3">
                    {{ iPropsValue("auth", "user.fullName") }}
                </div>
            </el-card>
        </el-col>
        <el-col :xs="24" :sm="16">
            <el-card class="box-card"
                ><el-form
                    :model="formData"
                    label-width="120px"
                    label-position="top"
                    ref="formRef"
                >
                    <el-row :gutter="20">
                        <el-col :sm="24" :md="12">
                            <el-form-item label="First Name" prop="firstName">
                                <el-input v-model="formData.firstName" />
                            </el-form-item>
                        </el-col>
                        <el-col :sm="24" :md="12">
                            <el-form-item label="Middle Name" prop="middleName">
                                <el-input v-model="formData.middleName" />
                            </el-form-item>
                        </el-col>
                        <el-col :sm="24" :md="12">
                            <el-form-item label="Last Name" prop="lastName">
                                <el-input v-model="formData.lastName" />
                            </el-form-item>
                        </el-col>
                    </el-row>
                    <el-form-item label="Contact" prop="contact">
                        <el-input v-model="formData.contact" />
                    </el-form-item>
                    <el-form-item label="Gender" prop="gender">
                        <el-radio-group v-model="formData.gender">
                            <el-radio label="Male" />
                            <el-radio label="Female" />
                        </el-radio-group>
                    </el-form-item>
                    <el-form-item>
                        <el-button type="primary" @click="submitForm(formRef)"
                            >Update</el-button
                        >
                        <el-button @click="resetForm(formRef)"
                            >Cancel</el-button
                        >
                    </el-form-item>
                </el-form>
            </el-card>
        </el-col>
    </el-row>

    <AvatarUploader ref="avatarUploader" />
</template>
<script setup>
import { Edit } from "@element-plus/icons-vue";
import { markRaw } from "vue";

import AvatarUploader from "./Components/AvatarUploader.vue";
import { useForm } from "@inertiajs/inertia-vue3";
import { useInertiaPropsUtility } from "@/Composables/inertiaPropsUtility";
import { onMounted } from "@vue/runtime-core";
const { iPropsValue } = useInertiaPropsUtility();
const avatarUploader = $ref();
const formRef = $ref();
const showAvatarUploader = function () {
    avatarUploader.showForm();
};

const formData = useForm({
    id: "",
    firstName: "",
    middleName: "",
    lastName: "",
    contact: "",
    gender: "",
});

const populateFormData = function (data) {
    formData.id = data.id;
    formData.firstName = data.first_name;
    formData.middleName = data.middle_name;
    formData.lastName = data.last_name;
    formData.contact = data.contact;
    formData.gender =
        data.gender.charAt(0).toUpperCase() + data.gender.slice(1);
};

const submitForm = (formEl) => {
    if (!formEl) return;
    ElMessageBox.confirm(
        "You are trying Update your Profile. Continue?",
        "Warning",
        {
            type: "warning",
            icon: markRaw(Edit),
            callback: (action) => {
                if (action == "confirm") {
                    formData.patch(route("userProfile.update", formData.id), {
                        preserveScroll: true,
                        onSuccess: () => {
                            resetForm();
                        },
                    });
                }
            },
        }
    );
};
const resetForm = (formEl) => {
    if (!formEl) return;
    formEl.resetFields();
    formData.reset();
    populateFormData(iPropsValue("userInfo"));
};

onMounted(() => {
    populateFormData(iPropsValue("userInfo"));
});
</script>
<script>
export default {
    layout: "admin",
    methods: {},
};
const circleUrl =
    "https://cube.elemecdn.com/3/7c/3ea6beec64369c2642b92c6726f1epng.png";
</script>
