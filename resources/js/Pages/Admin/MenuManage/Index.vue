<template>
    <el-row class="mb-4">
        <el-col :span="12" class="pr-4 pt-4"
            ><div class="grid-content">
                <el-row class="mb-2">
                    <el-col :span="24">
                        <div class="flex justify-between">
                            <el-button
                                data-action="expand-all"
                                type="success"
                                size="default"
                                rounded
                                @click="addForm"
                                :icon="Plus"
                            >
                                Add Menu
                            </el-button>
                            <el-button
                                type="success"
                                size="default"
                                rounded
                                @click="saveMenuChanges()"
                                v-if="menuChangedStatus"
                            >
                                Save changes
                            </el-button>
                            <menu-action-button />
                        </div>
                    </el-col>
                </el-row>
                <div id="nestable-menu-list" class="dd">
                    <SortableMenu
                        :key="nestableComponentKey"
                        :parentMenus="menus"
                        @edit-menu="editForm"
                        @delete-menu="deleteMenu"
                    />
                </div>
            </div>
        </el-col>
        <el-col :span="12" class="pl-4 pt-4"
            ><div class="grid-content" />
            role menu view
        </el-col>
    </el-row>

    <add-edit-menu-form ref="addEditMenuForm" />
</template>
<script setup>
import "~/css/nestable.css";

import "/node_modules/nestable2/jquery.nestable.js";
//library import
import { reactive, watch, ref, onMounted } from "vue";
import { useForm } from "@inertiajs/inertia-vue3";
import { Plus } from "@element-plus/icons-vue";
// component import
import MenuActionButton from "./Components/MenuActionButton.vue";
import SortableMenu from "./Components/SortableMenu.vue";
import AddEditMenuForm from "./Components/AddEditMenuForm.vue";
// composable import
import { useObjectUtility } from "@/Composables/objectUtility";
import { useInertiaPropsUtility } from "@/Composables/inertiaPropsUtility";
// declaration
let { populateObject, sanitizeObject, resetObjectKey } = useObjectUtility();
let { iPropsValue } = useInertiaPropsUtility();
let menus = $ref(iPropsValue("app_menu", "menu_list"));
const nestableComponentKey = $ref(0);
let menuChangedStatus = $ref(false);
let addEditMenuForm = $ref(null);
let changedMenus = $ref({});
watch(
    () => iPropsValue("app_menu", "menu_list"),
    () => {
        menus = iPropsValue("app_menu", "menu_list");
        forceNestableRerender();
    }
);
const forceNestableRerender = (timeOut = 0) => {
    return new Promise((resolve) =>
        setTimeout(() => {
            nestableComponentKey += 1;
            resolve();
        }, timeOut)
    );
};

let setEditFormVisible = function () {
    editFormVisible = false;
};
let addForm = function () {
    addEditMenuForm.showForm("Add");
};
let editForm = function (menuData) {
    addEditMenuForm.showForm("Edit", menuData);
};

let deleteMenu = async function (menuId) {
    const app = this;
    let menuObj = JSON.parse(menus);
    const filteredMenu = filterMenu(menuObj, menuId);
    menus = JSON.stringify(filteredMenu);
    await forceNestableRerender();
    const deleteForm = useForm({
        menuList: menus,
        deletedId: menuId,
    });
    deleteForm.delete("/menu-link/" + menuId, {
        preserveScroll: true,
        onSuccess: () => {
            ElNotification({
                title: "Success",
                message: "Menu Deleted Successfully",
                type: "success",
            });
        },
    });
};

let filterMenu = function (menuObj, menuId) {
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
let initiateSortable = function () {
    const app = this;
    jQuery("#nestable-menu-list").nestable({
        beforeDragStop: function (l, e, p) {
            let type = jQuery(e).data("type");
            let type2 = jQuery(p).data("type");
            if (type == "parent") {
                if (type2 != "root") {
                    forceNestableRerender();
                    return false;
                }
                forceNestableRerender();
                return true;
            } else if (type == "child") {
                if (type2 == undefined) {
                } else if (type2 != "parent" || type2 == "root") {
                    forceNestableRerender();
                    return false;
                }
                forceNestableRerender();
                return true;
            }
        },
        callback: function (l, e) {
            if (jQuery(e).data("type") == "child") {
                const id = jQuery(e).data("id");
                const parent = jQuery(e)
                    .closest("li[data-type^='parent']")
                    .data("id");
                changedMenus[id] = parent;
                jQuery(e).data("parent_id", parent);
            }
            updateOutput();
            forceNestableRerender();
            menuChangedStatus = true;
        },
        group: 1,
        maxDepth: 2,
    });
};
let updateOutput = function () {
    menus = JSON.stringify(jQuery("#nestable-menu-list").nestable("serialize"));
};
let saveMenuChanges = function () {
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
            ElNotification({
                title: "Success",
                message: "Menu Updated Successfully",
                type: "success",
            });
        },
    });
};

onMounted(() => {
    initiateSortable();
});
</script>
<script>
export default {
    layout: "admin",
    methods: {},
};
</script>
