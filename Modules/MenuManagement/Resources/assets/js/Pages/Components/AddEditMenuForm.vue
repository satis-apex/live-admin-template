<template>
    <el-dialog
        v-model="FormVisible"
        :before-close="closeForm"
        :title="FormType + ' Menu'"
    >
        <template #default>
            <el-form
                @submit.prevent
                :model="formData"
                :rules="rules"
                ref="ruleFormRef"
            >
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
                    <el-input
                        placeholder="eg: Manage User"
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
                        @change="checkGeneratorOption()"
                    >
                        <el-option
                            v-if="
                                FormType == 'Add' &&
                                iPropsValue('userCan', 'generate')
                            "
                            label="Auto Generate"
                            value="auto-generate"
                        />
                        <template
                            v-for="(menuRoute, key) in menuRoutes"
                            :key="key"
                        >
                            <el-option :label="menuRoute" :value="menuRoute" />
                        </template>
                    </el-select>
                </el-form-item>
                <el-form-item
                    v-if="
                        FormType == 'Add' &&
                        formData.type == 'parent-single' &&
                        formData.link == 'auto-generate' &&
                        iPropsValue('userCan', 'generate')
                    "
                    label="Files Only"
                    :label-width="formLabelWidth"
                    prop="generateOption"
                >
                    <el-switch
                        v-model="formData.generateOption"
                        active-value="files-only"
                        inactive-value="0"
                    />
                </el-form-item>
                <el-form-item
                    v-if="
                        FormType == 'Add' &&
                        formData.link == 'auto-generate' &&
                        iPropsValue('userCan', 'generate')
                    "
                    :label-width="formLabelWidth"
                    prop="module"
                >
                    <template #label>
                        <p>
                            Module Name
                            <el-tooltip
                                content="Module name must be generic name in studly Case. eg: MenuManagement, StudentManagement "
                                placement="bottom"
                                popper-style="max-width: 250px"
                            >
                                <el-icon color="var(--primary-dark-color)"
                                    ><InfoFilled
                                /></el-icon>
                            </el-tooltip>
                        </p>
                    </template>
                    <el-select
                        @keydown.space.prevent
                        v-model="formData.module"
                        filterable
                        :allow-create="
                            formData.link == 'auto-generate' ? true : false
                        "
                        clearable
                        default-first-option
                        :reserve-keyword="false"
                        placeholder="eg: UserManagement"
                        @change="formatModule"
                    >
                        <el-option
                            v-for="(module, key) in modules"
                            :key="key"
                            :label="module"
                            :value="module"
                        />
                    </el-select>
                </el-form-item>
                <el-form-item
                    v-if="
                        FormType == 'Add' &&
                        formData.link == 'auto-generate' &&
                        iPropsValue('userCan', 'generate')
                    "
                    :label-width="formLabelWidth"
                    prop="controllerName"
                >
                    <template #label>
                        <p>
                            Controller Name
                            <el-tooltip
                                content="Controller name must be specific name in studly Case. eg: UserProfile, DataTable"
                                placement="bottom"
                                popper-style="max-width: 250px"
                            >
                                <el-icon color="var(--primary-dark-color)"
                                    ><InfoFilled
                                /></el-icon>
                            </el-tooltip>
                        </p>
                    </template>
                    <el-input
                        placeholder="eg: UserAccount"
                        v-model="formData.controllerName"
                        @keydown.space.prevent
                        autocomplete="off"
                        :formatter="
                            (value) => {
                                const converted = value.replace('\\', '/');
                                return converted.replace(
                                    /(^\w{1})|(\/+\w{1})/g,
                                    (letter) => letter.toUpperCase()
                                );
                            }
                        "
                    />
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
                    <el-input v-model="menuIcon" autocomplete="off">
                        <template #prepend>
                            <el-tooltip class="box-item" content="Pick an Icon">
                                <el-button
                                    :icon="Search"
                                    @click="iconDialogVisible = true"
                                />
                            </el-tooltip>
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
                            :disabled="role == 'Su-Admin' ? true : false"
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
            <!-- modal for select icon from -->
            <el-dialog
                v-model="iconDialogVisible"
                width="30%"
                title="Icons"
                append-to-body
                class="icon-dialog"
            >
                <el-alert type="success" show-icon :closable="false">
                    <template #default>
                        <p class="text-justify break-normal">
                            Following Icons as hand picked from
                            <a
                                href="https://fontawesome.com/search?o=r&m=free"
                                target="#fontawesome"
                                ><b>Font Awesome</b></a
                            >
                            icon library. You can add icon manually that you can
                            find in
                            <a
                                href="https://fontawesome.com/search?o=r&m=free"
                                target="#fontawesome"
                                ><b>link</b></a
                            >
                        </p>
                    </template>
                </el-alert>
                <ul class="grid icon-picker grid-cols-8 overflow-auto mt-3">
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
                    :loading="formData.processing"
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
import { reactive, markRaw, watch, ref } from "@vue/runtime-core";
import { Edit, Search, InfoFilled } from "@element-plus/icons-vue";
import { useForm } from "@inertiajs/inertia-vue3";
import debounce from "lodash/debounce";
//composable import
import { useInertiaPropsUtility } from "@/Composables/inertiaPropsUtility";
//variable declaration
const FormVisible = $ref(false);
const iconDialogVisible = $ref(false);
const linkToParent = $ref(false);
const formLabelWidth = "150px";
const menuIcon = $ref();
const { iPropsValue } = useInertiaPropsUtility();
const ruleFormRef = $ref();
const FormType = $ref("Add");
const menuRoutes = $ref(iPropsValue("menuRoutes"));
const parentLinks = $ref(iPropsValue("parentLinks"));
const roles = $ref(iPropsValue("userRoles"));
const modules = ref(iPropsValue("modules"));
const menuLinkLists = $ref(iPropsValue("menuLinkLists"));
const editFormData = $ref(); //default edit form data
watch(
    () => iPropsValue("menuRoutes"),
    () => {
        menuRoutes = iPropsValue("menuRoutes");
    }
);
watch(
    () => iPropsValue("menuLinkLists"),
    () => {
        menuLinkLists = iPropsValue("menuLinkLists");
    }
);
watch(
    () => iPropsValue("parentLinks"),
    () => {
        parentLinks = iPropsValue("parentLinks");
    }
);
watch(
    () => menuIcon,
    debounce((value) => {
        formData.icon = value != "" ? value : null;
    }, 600)
);
const formatModule = (value) => {
    const converted = value.charAt(0).toUpperCase() + value.slice(1);
    formData.module = converted;
};
const formData = useForm({
    name: "",
    type: "parent",
    parentId: null,
    link: "#",
    generateOption: null,
    icon: null,
    module: "",
    controllerName: "",
    access: ["Su-Admin"],
});
const validateExists = (rule, value, callback) => {
    if (value !== "") {
        if (isMenuExist(value)) {
            callback(new Error("Please Assign Unique Menu Name"));
        }
    }
    callback();
};
const rules = reactive({
    name: [
        {
            required: true,
            message: "Please Input Menu Name",
            trigger: "blur",
        },
        { validator: validateExists, trigger: "blur" },
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
    module: [
        {
            required: false,
            message: "Please Input Module Name",
            trigger: "blur",
        },
    ],
    controllerName: [
        {
            required: false,
            message: "Please Input Controller Name",
            trigger: "blur",
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
const changedMenuType = function () {
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
    }, 50);
};
const checkGeneratorOption = function () {
    if (FormType == "Add" && formData.link == "auto-generate") {
        rules.module[0].required = true;
        rules.controllerName[0].required = true;
    } else {
        rules.module[0].required = false;
        rules.controllerName[0].required = false;
    }
    formData.generateOption = "";
    setTimeout(() => {
        ruleFormRef.clearValidate();
    }, 50);
};
const isMenuExist = function (newMenu) {
    if (FormType == "Edit") {
        if (editFormData.name == newMenu) return false;
    }
    if (menuLinkLists.indexOf(newMenu) != -1) {
        return true;
    }
    return false;
};
const selectIcon = function (iconName) {
    menuIcon = iconName;
    iconDialogVisible = false;
    ruleFormRef.clearValidate("icon");
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
};
const closeForm = () => {
    FormVisible = false;
    menuIcon = null;
    resetForm(ruleFormRef);
    formData.reset();
};
const insertMenu = async function () {
    ElMessageBox.confirm(
        "You are trying to Add New menu. Continue?",
        "Warning",
        {
            type: "warning",
            icon: markRaw(Edit),
            callback: (action) => {
                if (action == "confirm") {
                    formData.post(route("menuLink.store"), {
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
const updateMenu = function () {
    ElMessageBox.confirm("You are trying to edit menu. Continue?", "Warning", {
        type: "warning",
        icon: markRaw(Edit),
        callback: (action) => {
            if (action == "confirm") {
                formData.patch(route("menuLink.update", editFormData.id), {
                    preserveScroll: true,
                    onSuccess: () => {
                        closeForm();
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
        editFormData = data;
        populateFormData(data);
    }
};
const populateFormData = function (data) {
    formData.name = data.name;
    formData.type = data.type;
    formData.module = data.module;
    formData.link = data.link;
    if (data.type == "child") {
        formData.parentId = data.parent_id;
        linkToParent = true;
    } else {
        formData.icon = data.icon;
        menuIcon = data.icon;
    }
    formData.access = data.access?.split(",");
};

const emit = defineEmits(["added", "updated"]);
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
<style>
.icon-dialog .el-dialog__body {
    padding-top: 0;
}
</style>
