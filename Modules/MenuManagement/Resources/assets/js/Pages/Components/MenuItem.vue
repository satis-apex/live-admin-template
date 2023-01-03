<template>
    <div class="nestable-content text-sm dark:bg-zinc-800" v-if="menuItem">
        <div
            :class="
                menuItem.name == 'Home' || menuItem.name == 'Dashboard'
                    ? 'dd-nodrag'
                    : 'dd-handle'
            "
            class="nestable-handle flex-none dark:bg-zinc-800"
        >
            <fa icon="grip-vertical" />
        </div>
        <el-icon
            v-if="menuItem.type != 'child'"
            class="flex-none m-1 text-black dark:text-white"
            ><fa :icon="menuItem.icon" />
        </el-icon>
        <div
            class="grow text-black dark:text-white flex items-center"
            :class="menuItem.type == 'child' ? 'pl-1' : ''"
        >
            {{ menuItem.name }}
            <template
                v-if="
                    menuItem.hasOwnProperty('module') &&
                    disabled_module.includes(menuItem.module)
                "
            >
                <el-tooltip
                    :content="
                        menuItem.module +
                        ' Module is disabled. This menu will not be visible until ' +
                        menuItem.module +
                        ' Module is enabled'
                    "
                    placement="bottom"
                    popper-style="max-width: 250px"
                >
                    <el-icon
                        class="ml-1"
                        :size="16"
                        color="var(--el-color-danger)"
                        ><RemoveFilled
                    /></el-icon>
                </el-tooltip>
            </template>
        </div>
        <div
            class="action flex-none"
            v-if="
                menuItem.name != 'Home' &&
                menuItem.name != 'Dashboard' &&
                !menuItem.default_menu
            "
        >
            <el-button-group class="px-2">
                <el-tooltip
                    class="box-item"
                    effect="dark"
                    content="Edit Menu"
                    placement="top"
                    v-if="iPropsValue('userCan', 'edit')"
                >
                    <el-button
                        type="primary"
                        @click="emit('editMenu', menuItem)"
                        size="small"
                        rounded
                        :plain="isDarkMode"
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
                        iPropsValue('userCan', 'delete') &&
                        !menuItem.default_menu
                    "
                >
                    <el-button
                        type="danger"
                        @click="emit('deleteMenu', +menuItem.id)"
                        size="small"
                        rounded
                        :plain="isDarkMode"
                        class="!px-2"
                        ><fa icon="trash"
                    /></el-button>
                </el-tooltip>
            </el-button-group>
        </div>
    </div>
</template>
<script setup>
import { useInertiaPropsUtility } from "@/Composables/inertiaPropsUtility";
import { onMounted, ref } from "@vue/runtime-core";
import { useAppUtility } from "@/Composables/appUtiility";
import { RemoveFilled } from "@element-plus/icons-vue";
const { iPropsValue } = useInertiaPropsUtility();
const { isDarkMode } = useAppUtility();
const props = defineProps({
    menuItem: Object,
});
const disabled_module = ref(iPropsValue("app_disabled_module"));

const menuItem = $ref(props.menuItem);
const emit = defineEmits(["deleteMenu", "editMenu"]);
</script>
