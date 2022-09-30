<template>
    <el-dialog v-model="FormVisible" :title="FormType + ' Menu'">
        <template #default>
            <el-form :model="formData" :rules="rules" ref="ruleFormRef">
                <el-form-item label="Menu Type" :label-width="formLabelWidth">
                    <el-radio-group
                        v-model="formData.type"
                        @change="changedMenuType"
                    >
                        <el-radio-button
                            label="parent"
                            :disabled="
                                FormType == 'Edit' && formData.type != 'parent'
                                    ? true
                                    : false
                            "
                        />
                        <el-radio-button
                            label="child"
                            :disabled="
                                FormType == 'Edit' && formData.type != 'child'
                                    ? true
                                    : false
                            "
                        />
                        <el-radio-button
                            label="parent-single"
                            :disabled="
                                FormType == 'Edit' &&
                                formData.type != 'parent-single'
                                    ? true
                                    : false
                            "
                        />
                    </el-radio-group>
                </el-form-item>
                <el-form-item
                    label="Menu Name"
                    :label-width="formLabelWidth"
                    prop="name"
                >
                    <el-input v-model="formData.name" autocomplete="off" />
                </el-form-item>
                <el-form-item
                    label="Route Name"
                    v-if="
                        formData.type == 'parent-single' ||
                        formData.type == 'child'
                    "
                    :label-width="formLabelWidth"
                    prop="link"
                >
                    <el-select
                        v-model="formData.link"
                        placeholder="Please select Menu Link"
                    >
                        <template
                            v-for="(menuRoute, key) in menuRoutes"
                            :key="key"
                        >
                            <el-option :label="menuRoute" :value="menuRoute" />
                        </template>
                    </el-select>
                </el-form-item>
                <el-form-item
                    label="Menu Icon"
                    v-if="
                        formData.type == 'parent-single' ||
                        formData.type == 'parent'
                    "
                    :label-width="formLabelWidth"
                    prop="icon"
                >
                    <el-input v-model="formData.icon" autocomplete="off">
                        <template #prepend>
                            <el-button
                                :icon="Search"
                                @click="iconDialogVisible = true"
                            />
                        </template>
                        <template #append>
                            <el-icon v-if="formData.icon !== null"
                                ><fa :icon="formData.icon"
                            /></el-icon>
                        </template>
                    </el-input>
                </el-form-item>
                <el-form-item label="Role Access" :label-width="formLabelWidth">
                    <el-checkbox-group v-model="formData.access">
                        <el-checkbox
                            v-for="role in roles"
                            :key="role"
                            :label="role"
                            :disabled="role == 'su_admin' ? true : false"
                            >{{ role }}</el-checkbox
                        >
                    </el-checkbox-group>
                </el-form-item>

                <el-form-item
                    label="Parent"
                    v-show="linkToParent"
                    :label-width="formLabelWidth"
                    prop="parentId"
                >
                    <el-select
                        v-model="formData.parentId"
                        placeholder="Please select a zone"
                    >
                        <template
                            v-for="(parentLink, key) in parentLinks"
                            :key="key"
                        >
                            <el-option
                                :label="parentLink.name"
                                :value="parentLink.id"
                            />
                        </template>
                    </el-select>
                </el-form-item>
            </el-form>
            <el-dialog
                v-model="iconDialogVisible"
                width="30%"
                title="Icons"
                append-to-body
            >
                <ul class="grid icon-picker grid-cols-8 overflow-auto">
                    <li
                        class="icon"
                        v-for="(icon, key) in iconList"
                        :key="key"
                        @click="selectIcon(icon)"
                    >
                        <fa :icon="icon" />
                    </li>
                </ul>
            </el-dialog>
        </template>
        <template #footer>
            <span class="dialog-footer">
                <el-button @click="closeForm()">Cancel</el-button>
                <el-button
                    type="primary"
                    :disabled="formData.processing"
                    @click="submitForm(ruleFormRef)"
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
import { reactive, ref, markRaw, watch } from "vue";
import { Plus, Edit, Search } from "@element-plus/icons-vue";
import { Inertia } from "@inertiajs/inertia";
import { useForm } from "@inertiajs/inertia-vue3";
import { ElNotification } from "element-plus";
//composable import
import { useInertiaPropsUtility } from "@/Composables/inertiaPropsUtility";
//variable declaration
let { iPropsValue } = useInertiaPropsUtility();
let dialogTableVisible = $ref(false);
let FormVisible = $ref(false);
let iconDialogVisible = $ref(false);
let ruleFormRef = $ref();
const formLabelWidth = "140px";
let linkToParent = $ref(false);
let FormType = $ref("Add");
let menuRoutes = $ref(iPropsValue("menuRoutes"));
let parentLinks = $ref(iPropsValue("parentLinks"));
let roles = iPropsValue("userRoles");
watch(
    () => iPropsValue("menuRoutes"),
    () => {
        menuRoutes = iPropsValue("menuRoutes");
    }
);
watch(
    () => iPropsValue("parentLinks"),
    () => {
        parentLinks = iPropsValue("parentLinks");
    }
);

const formData = useForm({
    name: "",
    type: "parent",
    parentId: null,
    link: "#",
    icon: null,
    access: ["su_admin"],
});
const rules = reactive({
    name: [
        {
            required: true,
            message: "Please input Menu name",
            trigger: "blur",
        },
    ],

    icon: [
        {
            required: true,
            message: "Please Assign Menu Icon",
            trigger: "blur",
        },
    ],
    link: [
        {
            required: false,
            message: "Please Assign Menu link",
            trigger: "change",
        },
    ],
    parentId: [
        {
            required: false,
            message: "Please Assign Parent Menu link",
            trigger: "change",
        },
    ],
});
const iconList = [
    "house",
    "magnifying-glass",
    "user",
    "download",
    "phone",
    "envelope",
    "location-dot",
    "comment",
    "calendar-days",
    "file",
    "bell",
    "cart-shopping",
    "filter",
    "clipboard",
    "pen",
    "circle-user",
    "gift",
    "gear",
    "inbox",
    "print",
    "database",
    "chart-simple",
    "shirt",
    "table",
    "file-excel",
    "trash",
    "key",
    "shield",
    "school",
    "file-word",
    "trophy",
    "wrench",
    "users-gear",
    "terminal",
    "address-book",
    "box-open",
    "calculator",
    "chart-line",
    "wallet",
    "utensils",
    "university",
    "sitemap",
];

let changedMenuType = function () {
    formData.reset("icon", "link", "parentId");
    switch (formData.type) {
        case "child":
            linkToParent =
                rules.link[0].required =
                rules.parentId[0].required =
                    true;
            rules.icon[0].required = false;
            formData.link = "";
            break;

        case "parent":
            linkToParent =
                rules.parentId[0].required =
                rules.link[0].required =
                    false;
            rules.icon[0].required = true;
            formData.link = "#";
            break;

        case "parent-single":
            linkToParent = rules.parentId[0].required = false;
            rules.icon[0].required = rules.link[0].required = true;
            formData.link = "";
            break;

        default:
            break;
    }
    setTimeout(() => {
        ruleFormRef.clearValidate();
    }, 100);
};
let selectIcon = function (iconName) {
    formData.icon = iconName;
    iconDialogVisible = false;
};

const submitForm = async (formEl) => {
    if (!formEl) return;
    await formEl.validate((valid, fields) => {
        if (valid) {
            if (FormType == "Add") {
                insertMenu();
            } else {
                updateMenu();
            }
        } else {
            console.log("error submit!", fields);
        }
    });
};
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
const insertMenu = async function () {
    formData.post("/menu-link", {
        preserveScroll: true,
        onSuccess: () => {
            closeForm();
            ElNotification({
                title: "Success",
                message: "Menu Added Successfully",
                type: "success",
            });
        },
    });
};
const updateMenu = function () {
    ElMessageBox.confirm("You are trying to edit menu. Continue?", "Warning", {
        type: "warning",
        icon: markRaw(Edit),
        callback: (action) => {
            if (action == "confirm") {
                formData.patch("/menu-link/" + formData.id, {
                    preserveScroll: true,
                    onSuccess: () => {
                        closeForm();
                        ElNotification({
                            title: "Success",
                            message: "Menu Updated Successfully",
                            type: "success",
                        });
                    },
                });
            }
        },
    });
};
const showForm = function (formType, data = "") {
    FormVisible = true;
    linkToParent = false;
    formData.reset();
    FormType = formType;
    if (formType === "Add") {
    }
    if (formType === "Edit") {
        populateFormData(data);

        // formData.defaults("name", data.name);
    }
};
let populateFormData = function (data) {
    formData.name = data.name;
    formData.id = data.id;
    formData.type = data.type;
    formData.link = data.link;
    if (data.type == "child") {
        formData.parentId = data.parent_id;
        linkToParent = true;
    } else {
        formData.icon = data.icon;
    }
    formData.access = data.access?.split(",");
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
