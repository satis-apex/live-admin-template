<template>
    <el-tabs
        tab-position="top"
        style="min-height: 200px"
        v-model="activeTab"
        class="demo-tabs"
    >
        <template v-for="role in roles" :key="role">
            <el-tab-pane :label="role" :name="role" :lazy="true">
                <el-scrollbar max-height="70vh">
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
                                <PermissionMenuItem
                                    :menuItem="menu"
                                    :activeTab="activeTab"
                                    :role="role"
                                    @edit-menu="editMenuPermission"
                                    @delete-menu="deleteMenu"
                                />

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
                                            <PermissionMenuItem
                                                :menuItem="subMenu"
                                                :activeTab="activeTab"
                                                :role="role"
                                                @edit-menu="editMenuPermission"
                                                @delete-menu="deleteMenu"
                                            />
                                        </li>
                                    </template>
                                </ol>
                            </li>
                        </template>
                        <div id="dd-empty-placeholder"></div>
                    </ol>
                </el-scrollbar>
            </el-tab-pane>
        </template>
    </el-tabs>

    <EditMenuPermissionForm ref="editMenuPermissionForm" />
</template>
<script setup>
import { useInertiaPropsUtility } from "@/Composables/inertiaPropsUtility";
import { computed, watch, markRaw, onMounted } from "@vue/runtime-core";
import { Delete } from "@element-plus/icons-vue";
import { useForm } from "@inertiajs/inertia-vue3";
import EditMenuPermissionForm from "./EditMenuPermissionForm.vue";
import PermissionLabel from "./PermissionLabel.vue";
import PermissionMenuItem from "./PermissionMenuItem.vue";
const { iPropsValue } = useInertiaPropsUtility();
const roles = iPropsValue("userRoles");
const editMenuPermissionForm = $ref();
const menus = $ref(iPropsValue("app_menu"));
const activeTab = $ref("Su-Admin");
const hasMenuAccess = function (menu, role) {
    const accessList = menu.access?.split(",");
    if (accessList.indexOf(role) != -1) {
        return true;
    }
    return false;
};
const deleteMenu = (menuId) => {
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
                    deleteForm.delete(
                        route("menuLinkPermission.destroy", menuId),
                        {
                            preserveScroll: true,
                            onSuccess: () => {
                                deleteForm.reset();
                            },
                        }
                    );
                }
            },
        }
    );
    return;
};
const editMenuPermission = (menuData) => {
    editMenuPermissionForm.showForm(menuData, activeTab);
};
</script>
