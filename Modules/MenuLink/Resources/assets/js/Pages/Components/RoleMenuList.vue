<template>
    <el-tabs
        tab-position="top"
        style="height: 200px"
        v-model="activeTab"
        class="demo-tabs"
    >
        <template v-for="role in roles" :key="role">
            <el-tab-pane :label="role" :name="role" :lazy="true">
                <ol class="dd-list" data-type="root">
                    <template v-for="(menu, key) in menus" :key="key">
                        <li
                            :data-type="menu.type"
                            class="dd-item flex"
                            :data-id="menu.id"
                            :data-name="menu.name"
                            :data-icon="menu.icon"
                            :data-link="menu.link"
                            :data-access="menu.access"
                            v-if="hasMenuAccess(menu, role)"
                        >
                            <div class="nestable-content !pl-3 text-sm">
                                <el-icon><fa :icon="menu.icon" /> </el-icon>
                                {{ menu.name }}
                                <PermissionLabel
                                    :menuId="menu.id"
                                    :role="activeTab"
                                />
                            </div>
                            <div
                                class="action"
                                v-if="
                                    menu.name != 'Home' &&
                                    menu.name != 'Dashboard'
                                "
                            >
                                <el-button-group class="ml-4">
                                    <el-tooltip
                                        class="box-item"
                                        effect="dark"
                                        content="Edit Menu Permission"
                                        placement="top"
                                        v-if="menu.type != 'parent'"
                                    >
                                        <el-button
                                            type="primary"
                                            @click="editMenuPermission(menu)"
                                            size="small"
                                            rounded
                                            class="!px-2"
                                        >
                                            <fa icon="pen"
                                        /></el-button>
                                    </el-tooltip>
                                    <el-tooltip
                                        class="box-item"
                                        effect="dark"
                                        content="Delete Menu Permission"
                                        placement="top"
                                        v-if="role != 'Su-Admin'"
                                    >
                                        <el-button
                                            type="danger"
                                            @click="deleteMenu(menu.id)"
                                            size="small"
                                            rounded
                                            class="!px-2"
                                            ><fa icon="trash"
                                        /></el-button>
                                    </el-tooltip>
                                </el-button-group>
                            </div>
                            <ol
                                v-if="
                                    menu.hasOwnProperty('children') &&
                                    Object.keys(menu.children).length > 0
                                "
                                class="dd-list"
                                :data-type="menu.type"
                            >
                                <template
                                    v-for="(
                                        subMenu, subMenuKey
                                    ) in menu.children"
                                    :key="subMenuKey"
                                >
                                    <li
                                        :data-type="subMenu.type"
                                        class="dd-item"
                                        :data-id="subMenu.id"
                                        :data-name="subMenu.name"
                                        :data-icon="subMenu.icon"
                                        :data-link="subMenu.link"
                                        :data-access="subMenu.access"
                                        :data-parent_id="subMenu.parent_id"
                                        v-if="hasMenuAccess(subMenu, role)"
                                    >
                                        <div class="nestable-content !pl-3">
                                            {{ subMenu.name }}
                                            <PermissionLabel
                                                :menuId="subMenu.id"
                                                :role="activeTab"
                                            />
                                        </div>
                                        <div class="action">
                                            <el-button-group class="ml-4">
                                                <el-tooltip
                                                    class="box-item"
                                                    effect="dark"
                                                    content="Edit Menu Permission"
                                                    placement="top"
                                                >
                                                    <el-button
                                                        type="primary"
                                                        @click="
                                                            editMenuPermission(
                                                                subMenu
                                                            )
                                                        "
                                                        size="small"
                                                        rounded
                                                        class="!px-2"
                                                    >
                                                        <fa icon="pen"
                                                    /></el-button>
                                                </el-tooltip>
                                                <el-tooltip
                                                    class="box-item"
                                                    effect="dark"
                                                    content="Delete Menu Permission"
                                                    placement="top"
                                                >
                                                    <el-button
                                                        type="danger"
                                                        @click="
                                                            deleteMenu(
                                                                subMenu.id
                                                            )
                                                        "
                                                        size="small"
                                                        rounded
                                                        class="!px-2"
                                                        ><fa icon="trash"
                                                    /></el-button>
                                                </el-tooltip>
                                            </el-button-group>
                                        </div>
                                    </li>
                                </template>
                            </ol>
                        </li>
                    </template>
                    <div id="dd-empty-placeholder"></div>
                </ol>
            </el-tab-pane>
        </template>
    </el-tabs>

    <EditMenuPermissionForm
        :parentTabName="activeTab"
        ref="editMenuPermissionForm"
    />
</template>
<script setup>
import { useInertiaPropsUtility } from "@/Composables/inertiaPropsUtility";
import { computed, watch, markRaw } from "vue";
import { Delete } from "@element-plus/icons-vue";
import { useForm } from "@inertiajs/inertia-vue3";
import EditMenuPermissionForm from "./EditMenuPermissionForm.vue";
import PermissionLabel from "./PermissionLabel.vue";
let { iPropsValue } = useInertiaPropsUtility();
let roles = iPropsValue("userRoles");
const editMenuPermissionForm = $ref();
let menuList = $ref(iPropsValue("app_menu", "menu_list"));
let roleMenus = $ref(iPropsValue("roleMenus"));
watch(
    () => iPropsValue("app_menu", "menu_list"),
    () => {
        menuList = iPropsValue("app_menu", "menu_list");
    }
);
watch(
    () => iPropsValue(roleMenus),
    () => {
        roleMenus = iPropsValue(roleMenus);
    }
);
const activeTab = $ref("Su-Admin");
const menus = computed(() => JSON.parse(menuList));
let hasMenuAccess = function (menu, role) {
    const accessList = menu.access?.split(",");
    if (accessList.indexOf(role) != -1) {
        return true;
    }
    return false;
};
let deleteMenu = (menuId) => {
    ElMessageBox.confirm(
        "It will permanently Revoke menu Permission. Continue?",
        "Warning",
        {
            type: "error",
            icon: markRaw(Delete),
            callback: (action) => {
                if (action == "confirm") {
                    const deleteForm = useForm({
                        role: activeTab,
                    });
                    deleteForm.delete("/menu-link-permission/" + menuId, {
                        preserveScroll: true,
                        onSuccess: () => {
                            deleteForm.reset();
                        },
                    });
                }
            },
        }
    );
    return;
};

let editMenuPermission = (menuData) => {
    editMenuPermissionForm.showForm(menuData);
};
</script>
