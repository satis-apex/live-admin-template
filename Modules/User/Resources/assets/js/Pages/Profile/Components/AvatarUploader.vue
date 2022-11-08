<template>
    <el-dialog
        v-model="avatarUploadVisible"
        title="Update Picture"
        width="30%"
        :before-close="closeForm"
    >
        <el-upload
            class="avatar-uploader flex justify-center"
            action="#"
            :show-file-list="false"
            :on-change="avatarUpload"
            :auto-upload="false"
            accept="image/*"
        >
            <img
                v-if="avatarFilePreview"
                :src="avatarFilePreview"
                class="avatar"
            />
            <el-icon
                v-else
                class="avatar-uploader-icon"
                v-loading="uploadingAvatar"
                ><Plus
            /></el-icon>
        </el-upload>
        <template #footer>
            <span class="dialog-footer">
                <el-button @click="closeForm">Cancel</el-button>
                <el-button
                    type="primary"
                    :disabled="!uploadable"
                    :loading="formData.processing"
                    @click="avatarUploadConfirm"
                    >Update</el-button
                >
            </span>
        </template>
    </el-dialog>
</template>
<script setup>
import { reactive, markRaw, watch } from "@vue/runtime-core";
import { Edit, Plus } from "@element-plus/icons-vue";
import { useForm } from "@inertiajs/inertia-vue3";
import { Inertia } from "@inertiajs/inertia";

const avatarUploadVisible = $ref(false);
const uploadingAvatar = $ref(false);
const avatarFilePreview = $ref(null);
const uploadable = $ref(false);

const closeForm = function () {
    uploadable = false;
    avatarUploadVisible = false;
    avatarFilePreview = null;
};
const showForm = function () {
    avatarUploadVisible = true;
};
const formData = useForm({
    avatar: null,
});
const avatarUpload = function (file, response) {
    if (beforeAvatarUpload(file.raw)) {
        avatarFilePreview = URL.createObjectURL(file.raw);
        formData.avatar = file.raw;
        uploadable = true;
        return;
    }
};
const beforeAvatarUpload = function (uploadFile) {
    const file = uploadFile;
    const isJPG = file.type === "image/jpeg";
    const isLt2M = file.size / 1024 / 1024 < 2;

    if (!isJPG) {
        ElMessage.error("Avatar picture must be JPG format!");
        return false;
    }
    if (!isLt2M) {
        ElMessage.error("Avatar picture size can not exceed 2MB!");
        return false;
    }
    return true;
};

const avatarUploadConfirm = function () {
    ElMessageBox.confirm(
        "You are trying to Upload New Image, Old image will be deleted. Continue?",
        "Warning",
        {
            type: "warning",
            icon: markRaw(Edit),
            callback: (action) => {
                if (action == "confirm") {
                    formData.post(route("updateAvatar"), {
                        onSuccess: () => {
                            closeForm();
                        },
                    });
                }
            },
        }
    );
};

defineExpose({
    showForm,
});
</script>
<style scoped>
.avatar-uploader .avatar {
    width: 178px;
    height: 178px;
    display: block;
}
</style>

<style>
.avatar-uploader .el-upload {
    border: 1px dashed var(--el-border-color);
    border-radius: 6px;
    cursor: pointer;
    position: relative;
    overflow: hidden;
    transition: var(--el-transition-duration-fast);
    background: var(--el-color-primary-light-9);
}

.avatar-uploader .el-upload:hover {
    border-color: var(--el-color-primary);
}

.el-icon.avatar-uploader-icon {
    font-size: 28px;
    color: #8c939d;
    width: 178px;
    height: 178px;
    text-align: center;
}
</style>
