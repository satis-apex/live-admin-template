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
                    <div
                        :class="
                            menu.name == 'Home' || menu.name == 'Dashboard'
                                ? 'dd-nodrag'
                                : 'dd-handle'
                        "
                        class="nestable-handle"
                    ></div>
                    <div class="nestable-content text-sm">
                        <el-icon><fa :icon="menu.icon" /> </el-icon>
                        {{ menu.name }}
                    </div>
                    <div
                        class="action"
                        v-if="menu.name != 'Home' && menu.name != 'Dashboard'"
                    >
                        <el-button-group class="ml-4">
                            <el-tooltip
                                class="box-item"
                                effect="dark"
                                content="Edit Menu"
                                placement="top"
                                v-if="iPropsValue('userCan', 'edit')"
                            >
                                <el-button
                                    type="primary"
                                    @click="emit('editMenu', menu)"
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
                                content="Delete Menu"
                                placement="top"
                                v-if="iPropsValue('userCan', 'delete')"
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
                                <div class="dd-handle nestable-handle"></div>
                                <div class="nestable-content">
                                    {{ subMenu.name }}
                                </div>
                                <div class="action">
                                    <el-button-group class="ml-4">
                                        <el-tooltip
                                            class="box-item"
                                            effect="dark"
                                            content="Edit Menu"
                                            placement="top"
                                            v-if="
                                                iPropsValue('userCan', 'edit')
                                            "
                                        >
                                            <el-button
                                                type="primary"
                                                @click="
                                                    emit('editMenu', subMenu)
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
                                            content="Delete Menu"
                                            placement="top"
                                            v-if="
                                                iPropsValue('userCan', 'delete')
                                            "
                                        >
                                            <el-button
                                                type="danger"
                                                @click="deleteMenu(subMenu.id)"
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
    </div>
</template>
<script setup>
import "~/css/nestable.css";
import "/node_modules/nestable2/jquery.nestable.js";
import { watch, markRaw, defineEmits, onMounted } from "vue";
import { Delete } from "@element-plus/icons-vue";
import { useInertiaPropsUtility } from "@/Composables/inertiaPropsUtility";
let { iPropsValue } = useInertiaPropsUtility();
let menuList = $ref(props.parentMenus);
let menus = JSON.parse(menuList); //using computed property glitch the sorting

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
let changedMenuLists = $ref({});

let deleteMenu = (menuId) => {
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

let initiateSortable = function () {
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
    initiateSortable();
});
</script>
