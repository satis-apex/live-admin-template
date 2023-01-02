<template>
    <div class="nestable-content !pl-3 text-sm dark:bg-zinc-800">
        <el-icon
            v-if="menuItem.type != 'child'"
            class="flex-none pl-1 pr-3 dark:text-white"
            ><fa :icon="menuItem.icon" />
        </el-icon>
        <span class="grow dark:text-white"> {{ menuItem.name }}</span>
        <PermissionLabel :menuId="+menuItem.id" :role="activeTab" />

        <div
            class="action flex-none"
            v-if="menuItem.name != 'Home' && menuItem.name != 'Dashboard'"
        >
            <el-button-group class="px-2">
                <el-tooltip
                    class="box-item"
                    effect="dark"
                    content="Edit Menu Permission"
                    placement="top"
                    v-if="menuItem.type != 'parent' && role != 'Su-Admin'"
                >
                    <el-button
                        type="primary"
                        @click="emit('editMenu', menuItem)"
                        size="small"
                        rounded
                        class="!px-2"
                        :plain="isDarkMode"
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
                        @click="emit('deleteMenu', menuItem.id)"
                        size="small"
                        rounded
                        class="!px-2"
                        :plain="isDarkMode"
                        ><fa icon="trash"
                    /></el-button>
                </el-tooltip>
            </el-button-group>
        </div>
    </div>
</template>
<script setup>
import { useInertiaPropsUtility } from "@/Composables/inertiaPropsUtility";
import { useAppUtility } from "@/Composables/appUtiility";
import { onMounted, watch } from "@vue/runtime-core";
import PermissionLabel from "./PermissionLabel.vue";
const { iPropsValue } = useInertiaPropsUtility();
const { isDarkMode } = useAppUtility();
const props = defineProps({
    menuItem: Object,
    activeTab: String,
    role: String,
});
const activeTab = $ref(props.activeTab);
let menuItem = $ref(props.menuItem);
const role = $ref(props.role);
const emit = defineEmits(["deleteMenu", "editMenu"]);
</script>
