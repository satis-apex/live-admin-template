<template>
    <div class="nestable-content text-sm" v-if="menuItem">
        <div
            :class="
                menuItem.name == 'Home' || menuItem.name == 'Dashboard'
                    ? 'dd-nodrag'
                    : 'dd-handle'
            "
            class="nestable-handle flex-none"
        >
            <fa icon="grip-vertical" />
        </div>
        <el-icon v-if="menuItem.type != 'child'" class="flex-none m-1"
            ><fa :icon="menuItem.icon" />
        </el-icon>
        <span class="grow" :class="menuItem.type == 'child' ? 'pl-1' : ''">
            {{ menuItem.name }}</span
        >
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
import { onMounted } from "@vue/runtime-core";
const { iPropsValue } = useInertiaPropsUtility();
const props = defineProps({
    menuItem: Object,
});
const menuItem = $ref(props.menuItem);
const emit = defineEmits(["deleteMenu", "editMenu"]);
</script>
