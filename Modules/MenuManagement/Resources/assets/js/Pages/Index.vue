<template>
    <el-row class="mb-4 justify-between">
        <el-col
            :sm="11"
            :xs="24"
            class="p-3 border rounded-lg bg-white dark:bg-neutral-800 mb-3"
        >
            <h2 class="font-medium text-lg">Application Menu Control</h2>
            <el-divider class="!mt-3 !mb-4" />
            <div class="grid-content">
                <el-row class="mb-2">
                    <el-col :span="24">
                        <div class="flex justify-between">
                            <el-button-group>
                                <el-tooltip
                                    class="box-item"
                                    effect="dark"
                                    content="Add Menu"
                                    placement="top"
                                    v-if="iPropsValue('userCan', 'create')"
                                >
                                    <el-button
                                        data-action="expand-all"
                                        type="success"
                                        size="default"
                                        rounded
                                        :plain="isDarkMode"
                                        @click="addForm"
                                        :icon="Plus"
                                    >
                                    </el-button>
                                </el-tooltip>
                                <el-tooltip
                                    class="box-item"
                                    effect="dark"
                                    content="Save Changes"
                                    placement="top"
                                    v-if="
                                        menuChangedStatus &&
                                        iPropsValue('userCan', 'edit')
                                    "
                                >
                                    <el-button
                                        type="success"
                                        size="default"
                                        rounded
                                        :plain="isDarkMode"
                                        @click="saveMenuChanges()"
                                        :loading="saveMenuLoading"
                                        v-if="menuChangedStatus"
                                    >
                                        <fa icon="floppy-disk" />
                                    </el-button>
                                </el-tooltip>
                            </el-button-group>
                            <MenuActionButton />
                        </div>
                    </el-col>
                </el-row>
                <NestableMenu
                    :key="nestableComponentKey"
                    @edit-menu="editForm"
                    @delete-menu="deleteMenu"
                    @render-nestable="renderNestable"
                    @update-nestable="updateNestable"
                />
            </div>
        </el-col>
        <el-col
            :sm="12"
            :xs="24"
            class="p-3 border rounded-lg bg-white dark:bg-neutral-800 mb-3"
        >
            <h2 class="font-medium text-lg">Menu Permission Control</h2>
            <el-divider class="!mt-3 !mb-4" />
            <RoleMenuList :key="nestableComponentKey" />
        </el-col>
    </el-row>
    <AddEditMenuForm ref="addEditMenuForm" />
</template>
<script setup>
//library import
import { onBeforeMount, onBeforeUnmount, watch } from "@vue/runtime-core";
import { useForm } from "@inertiajs/inertia-vue3";
import { Plus } from "@element-plus/icons-vue";
// component import
import MenuActionButton from "./Components/MenuActionButton.vue";
import NestableMenu from "./Components/NestableMenu.vue";
import AddEditMenuForm from "./Components/AddEditMenuForm.vue";
import RoleMenuList from "./Components/RoleMenuList.vue";
// composable import
import { useObjectUtility } from "@/Composables/objectUtility";
import { useInertiaPropsUtility } from "@/Composables/inertiaPropsUtility";
import { useAppUtility } from "@/Composables/appUtiility";
// declaration
const nestableComponentKey = $ref(0);
const menuChangedStatus = $ref(false);
const addEditMenuForm = $ref(null);
const saveMenuLoading = $ref(false);
const { resetObjectKey } = useObjectUtility();
const { iPropsValue } = useInertiaPropsUtility();
const { isDarkMode } = useAppUtility();
const changedMenus = $ref({});
const renderNestable = (timeOut = 0) => {
    return new Promise((resolve) =>
        setTimeout(() => {
            nestableComponentKey += 1;
            resolve();
        }, timeOut)
    );
};
const setEditFormVisible = function () {
    editFormVisible = false;
};
const filterMenu = function (menuObj, menuId) {
    let filteredObj = [];
    _.forEach(menuObj, (menu, key) => {
        if (menu.id == menuId) {
        } else {
            if (menu.hasOwnProperty("children")) {
                const menuChildren = menu.children;
                filteredObj[key] = menu;
                filteredObj[key]["children"] = [];
                _.forEach(menuChildren, (subMenu, subKey) => {
                    if (subMenu.hasOwnProperty("id") && subMenu.id == menuId) {
                    } else {
                        filteredObj[key]["children"].push(subMenu);
                    }
                });
            } else {
                filteredObj[key] = menu;
            }
        }
    });
    return resetObjectKey(filteredObj);
};
const addForm = function () {
    addEditMenuForm.showForm("Add");
};
const editForm = function (menuData) {
    addEditMenuForm.showForm("Edit", menuData);
};
const updateNestable = function (changedMenuLists) {
    menuChangedStatus = true;
    changedMenus = changedMenuLists;
};
const deleteMenu = async function (menuId) {
    const app = this;
    let menuObj = jQuery("#nestable-menu-list").nestable("serialize"); //JSON.parse(menus);
    const filteredMenu = filterMenu(menuObj, menuId);
    const menus = JSON.stringify(filteredMenu);

    await renderNestable();
    const deleteForm = useForm({
        menuList: menus,
        deletedId: menuId,
    });
    deleteForm.delete(route("menuLink.destroy", menuId), {
        preserveScroll: true,
        onSuccess: () => {
            deleteForm.reset();
        },
    });
};
const saveMenuChanges = function () {
    saveMenuLoading = true;
    const updatedMenu = window.JSON.stringify(
        jQuery("#nestable-menu-list").nestable("serialize")
    );

    const formData = useForm({
        menus: updatedMenu,
        changedMenu: changedMenus,
    });
    formData.post(route("menu.store"), {
        preserveScroll: true,
        onSuccess: () => {
            menuChangedStatus = false;
            saveMenuLoading = false;
        },
    });
};
</script>
<script>
export default {
    layout: "admin",
    methods: {},
};
</script>
