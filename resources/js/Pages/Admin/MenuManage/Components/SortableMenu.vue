<template>
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
                        menu.name == 'home' || menu.name == 'dashboard'
                            ? 'dd-nodrag'
                            : 'dd-handle'
                    "
                    class="nestable-handle"
                ></div>
                <div class="nestable-content text-sm">
                    <el-icon><fa :icon="menu.icon" /> </el-icon> {{ menu.name }}
                </div>
                <div
                    class="action"
                    v-if="menu.name != 'home' && menu.name != 'dashboard'"
                >
                    <el-button-group class="ml-4">
                        <el-button
                            type="primary"
                            @click="emit('editMenu', menu)"
                            size="small"
                            rounded
                        >
                            <fa icon="pen"
                        /></el-button>
                        <el-button
                            type="danger"
                            @click="deleteMenu(menu.id)"
                            size="small"
                            rounded
                            ><fa icon="trash"
                        /></el-button>
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
                                    <el-button
                                        type="primary"
                                        @click="emit('editMenu', subMenu)"
                                        size="small"
                                        rounded
                                    >
                                        <fa icon="pen"
                                    /></el-button>
                                    <el-button
                                        type="danger"
                                        @click="deleteMenu(subMenu.id)"
                                        size="small"
                                        rounded
                                        ><fa icon="trash"
                                    /></el-button>
                                </el-button-group>
                            </div>
                        </li>
                    </template>
                </ol>
            </li>
        </template>
        <div id="dd-empty-placeholder"></div>
    </ol>
</template>

<script setup>
import { watch, markRaw, defineEmits } from "vue";
import { Delete, Edit } from "@element-plus/icons-vue";
// import "element-plus/es/components/message/style/css";
// import "element-plus/es/components/notification/style/css";
watch(
    () => props.parentMenus,
    () => {
        menus = JSON.parse(props.parentMenus);
    }
);
let menuList = $ref(props.parentMenus);
let menus = JSON.parse(menuList); //usijn computed property glitch the sorting
const emit = defineEmits(["deleteMenu", "editMenu"]);
const props = defineProps({
    parentMenus: String,
});

let deleteMenu = (menuId) => {
    ElMessageBox.confirm(
        "It will permanently delete the menu. Continue?",
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
</script>
