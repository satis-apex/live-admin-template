<template>
    <el-row class="mb-4">
        <el-col :span="11" class="p-3 border rounded-lg bg-white">
            <h2 class="pb-4 font-medium text-xl text-lightBlue-500">
                Application Menu Control
            </h2>
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
                    :parentMenus="menus"
                    @edit-menu="editForm"
                    @delete-menu="deleteMenu"
                    @render-nestable="renderNestable"
                    @update-nestable="updateNestable"
                />
            </div>
        </el-col>
        <el-col :span="12" :offset="1" class="p-3 border rounded-lg bg-white">
            <h2 class="pb-4 font-medium text-xl text-lightBlue-500">
                Menu Permission Control
            </h2>
            <RoleMenuList />
        </el-col>
    </el-row>
    <AddEditMenuForm ref="addEditMenuForm" />
</template>
<script setup>
//library import
import { watch } from "vue";
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
// declaration
const nestableComponentKey = $ref(0);
const menuChangedStatus = $ref(false);
const addEditMenuForm = $ref(null);
const saveMenuLoading = $ref(false);
let { resetObjectKey } = useObjectUtility();
let { iPropsValue } = useInertiaPropsUtility();
let menus = $ref(iPropsValue("app_menu", "menu_list"));
let changedMenus = $ref({});
watch(
    () => iPropsValue("app_menu", "menu_list"),
    () => {
        menus = iPropsValue("app_menu", "menu_list");
        renderNestable();
    }
);

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
    menus = JSON.stringify(jQuery("#nestable-menu-list").nestable("serialize"));
};
const deleteMenu = async function (menuId) {
    const app = this;
    let menuObj = JSON.parse(menus);
    const filteredMenu = filterMenu(menuObj, menuId);
    menus = JSON.stringify(filteredMenu);
    await renderNestable();
    const deleteForm = useForm({
        menuList: menus,
        deletedId: menuId,
    });
    deleteForm.delete("/menu-link/" + menuId, {
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
    formData.post("/menu", {
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
