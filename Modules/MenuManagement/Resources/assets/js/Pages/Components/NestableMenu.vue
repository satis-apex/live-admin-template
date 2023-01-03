<template>
    <div
        id="nestable-menu-list"
        class="dd border dark:border-none bg-gray-100 dark:bg-neutral-700"
    >
        <el-scrollbar max-height="70vh">
            <ol
                class="dd-list"
                v-if="menus && Object.keys(menus).length > 0"
                data-type="root"
            >
                <li
                    v-for="(menu, key) in menus"
                    :key="key"
                    :data-type="menu.type"
                    class="dd-item flex"
                    :data-module="menu.module"
                    :data-id="menu.id"
                    :data-name="menu.name"
                    :data-icon="menu.icon"
                    :data-link="menu.link"
                    :data-default_menu="menu.default_menu"
                    :data-access="menu.access"
                    :class="menu.type == 'parent-single' ? 'dd-nochildren' : ''"
                >
                    <MenuItem
                        :menuItem="menu"
                        @edit-menu="editMenu"
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
                            v-for="(subMenu, subMenuKey) in menu.children"
                            :key="subMenuKey"
                        >
                            <li
                                v-if="Object.keys(subMenu).length > 0"
                                :data-type="subMenu.type"
                                class="dd-item"
                                :data-module="subMenu.module"
                                :data-id="subMenu.id"
                                :data-name="subMenu.name"
                                :data-icon="subMenu.icon"
                                :data-link="subMenu.link"
                                :data-default_menu="subMenu.default_menu"
                                :data-access="subMenu.access"
                                :data-parent_id="subMenu.parent_id"
                            >
                                <MenuItem
                                    :menuItem="subMenu"
                                    @edit-menu="editMenu"
                                    @delete-menu="deleteMenu"
                                />
                            </li>
                        </template>
                    </ol>
                </li>
                <div id="dd-empty-placeholder"></div>
            </ol>
        </el-scrollbar>
    </div>
</template>
<script setup>
import "~/css/nestable.css";
import { watch, markRaw, onMounted } from "@vue/runtime-core";
import { Delete } from "@element-plus/icons-vue";
import { useInertiaPropsUtility } from "@/Composables/inertiaPropsUtility";
import { useAppUtility } from "@/Composables/appUtiility";
import MenuItem from "./MenuItem.vue";
const { iPropsValue } = useInertiaPropsUtility();
const { loadScript } = useAppUtility();
const emit = defineEmits([
    "deleteMenu",
    "editMenu",
    "updateNestable",
    "renderNestable",
]);
const props = defineProps({
    parentMenus: Object,
});
const menus = $ref(iPropsValue("app_menu"));
watch(
    () => iPropsValue("app_menu"),
    () => {
        emit("renderNestable");
    }
);
const changedMenuLists = $ref({});
const editMenu = (menuItem) => {
    return emit("editMenu", menuItem);
};
const deleteMenu = (menuId) => {
    ElMessageBox.confirm(
        "It will permanently delete the menu and its permission. Continue?",
        "Warning",
        {
            type: "error",
            icon: markRaw(Delete),
            callback: (action) => {
                if (action == "confirm") {
                    return emit("deleteMenu", menuId);
                }
            },
        }
    );
    return;
};
const initiateSortable = function () {
    jQuery("#nestable-menu-list").nestable({
        beforeDragStop: function (l, e, p) {
            let type = jQuery(e).data("type");
            let type2 = jQuery(p).data("type");
            if (type == "parent") {
                if (type2 != "root") {
                    return false;
                }
                return true;
            } else if (type == "child") {
                if (type2 == undefined) {
                } else if (type2 != "parent" || type2 == "root") {
                    return false;
                }
                return true;
            } else if (type == "parent-single") {
                if (type2 != "root") {
                    return false;
                }
                return true;
            }
            return false;
        },
        callback: function (l, e) {
            if (jQuery(e).data("type") == "child") {
                const id = jQuery(e).data("id");
                const parent = jQuery(e)
                    .closest("li[data-type^='parent']")
                    .data("id");
                changedMenuLists[id] = parent;
                jQuery(e).data("parent_id", parent);
            }
            emit("updateNestable", changedMenuLists);
        },
        group: 1,
        maxDepth: 2,
    });
};
onMounted(() => {
    loadScript(
        "https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.slim.min.js",
        () => {
            loadScript(
                "https://cdnjs.cloudflare.com/ajax/libs/nestable2/1.6.0/jquery.nestable.min.js",
                () => {
                    initiateSortable();
                }
            );
        }
    );
});
</script>
