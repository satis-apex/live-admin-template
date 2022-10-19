<template>
    <el-dialog
        v-model="FormVisible"
        :before-close="closeForm"
        :title="'Revoke Menu Permission for ' + userRole"
    >
        <template #default>
            <el-form @submit.prevent :model="formData" ref="ruleFormRef">
                <el-form-item
                    label="Menu Name"
                    :label-width="formLabelWidth"
                    prop="name"
                >
                    <el-input v-model="menuName" disabled autocomplete="off" />
                </el-form-item>
                <el-form-item
                    label="Role Permission"
                    :label-width="formLabelWidth"
                >
                    <el-checkbox-group v-model="formData.permission">
                        <el-checkbox
                            v-for="permission in permissions"
                            :key="permission"
                            :label="permission"
                            :disabled="permission == 'access' ? true : false"
                            >{{ permission }}</el-checkbox
                        >
                    </el-checkbox-group>
                </el-form-item>
            </el-form>
        </template>
        <template #footer>
            <span class="dialog-footer">
                <el-button @click="closeForm()">Cancel</el-button>
                <el-button
                    type="primary"
                    :loading="formData.processing"
                    @click="submitForm(ruleFormRef)"
                    >Update</el-button
                >
            </span>
        </template>
    </el-dialog>
</template>
<script setup>
import { useForm } from "@inertiajs/inertia-vue3";
import { watch, markRaw } from "vue";
import { Edit } from "@element-plus/icons-vue";
import { useInertiaPropsUtility } from "@/Composables/inertiaPropsUtility";
let { iPropsValue } = useInertiaPropsUtility();
const FormVisible = $ref(false);
const menuName = $ref("");
const menuId = $ref(0);
const formLabelWidth = "140px";
const permissions = ["access", "create", "edit", "delete"];
const formData = useForm({
    role: "",
    permission: [],
});
const props = defineProps({
    parentTabName: String,
});

let roleMenus = $ref(iPropsValue("roleMenus"));
watch(
    () => iPropsValue("roleMenus"),
    () => {
        roleMenus = iPropsValue("roleMenus");
    },
    { deep: true }
);

const userRole = $ref(props.parentTabName);
let ruleFormRef = $ref();

watch(
    () => props.parentTabName,
    () => {
        userRole = props.parentTabName;
    }
);
const resetForm = (formEl) => {
    if (!formEl) return;
    formEl.resetFields();
    formData.reset();
};
const closeForm = () => {
    FormVisible = false;
    resetForm(ruleFormRef);
    formData.reset();
};
const showForm = function (data) {
    FormVisible = true;
    formData.reset();
    formData.permission = menuPermission(data.id);
    formData.role = userRole;
    menuName = data.name;
    menuId = data.id;
};

const menuPermission = function (menuId) {
    if (
        Object.keys(roleMenus).length != 0 &&
        roleMenus[userRole].hasOwnProperty(menuId)
    ) {
        return roleMenus[userRole][menuId];
    }
    return null;
};
const submitForm = async (formEl) => {
    if (!formEl) return;
    ElMessageBox.confirm(
        "You are trying change menu permission. Continue?",
        "Warning",
        {
            type: "warning",
            icon: markRaw(Edit),
            callback: (action) => {
                if (action == "confirm") {
                    formData.patch("/menu-link-permission/" + menuId, {
                        preserveScroll: true,
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
