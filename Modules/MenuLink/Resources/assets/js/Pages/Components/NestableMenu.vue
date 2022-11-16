<template>
    <div id="nestable-menu-list" class="dd">
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
                    :class="menu.type == 'parent-single' ? 'dd-nochildren' : ''"
                >
                    <!-- particular menu expand/collapse purpose -->
                    <MenuItem
                        :menuItem="menu"
                        @editMenu="editMenu"
                        @deleteMenu="deleteMenu"
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
                                :data-type="subMenu.type"
                                class="dd-item"
                                :data-id="subMenu.id"
                                :data-name="subMenu.name"
                                :data-icon="subMenu.icon"
                                :data-link="subMenu.link"
                                :data-access="subMenu.access"
                                :data-parent_id="subMenu.parent_id"
                            >
                                <MenuItem
                                    :menuItem="subMenu"
                                    @editMenu="editMenu"
                                    @deleteMenu="deleteMenu"
                                />
                            </li>
                        </template>
                    </ol>
                </li>
            </template>
            <div id="dd-empty-placeholder"></div>
        </ol>
    </div>
</template>
<script setup>
import "~/css/nestable.css";
import { watch, markRaw, onMounted, onBeforeMount } from "@vue/runtime-core";
import { Delete } from "@element-plus/icons-vue";
import { useInertiaPropsUtility } from "@/Composables/inertiaPropsUtility";
import { useAppUtility } from "@/Composables/appUtiility";
import MenuItem from "./MenuItem.vue";
const { iPropsValue } = useInertiaPropsUtility();
const { loadScript } = useAppUtility();
const menuList = $ref(props.parentMenus);
const menus = $ref(JSON.parse(menuList)); //using computed property glitch the sorting

const emit = defineEmits([
    "deleteMenu",
    "editMenu",
    "updateNestable",
    "renderNestable",
]);
const props = defineProps({
    parentMenus: String,
});
watch(
    () => props.parentMenus,
    () => {
        menus = JSON.parse(props.parentMenus);
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
                    emit("renderNestable");
                    return false;
                }
                emit("renderNestable");
                return true;
            } else if (type == "child") {
                if (type2 == undefined) {
                } else if (type2 != "parent" || type2 == "root") {
                    emit("renderNestable");
                    return false;
                }
                emit("renderNestable");
                return true;
            } else if (type == "parent-single") {
                if (type2 != "root") {
                    emit("renderNestable");
                    return false;
                }
                emit("renderNestable");
                return true;
            }
            emit("renderNestable");
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
            emit("renderNestable");
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
