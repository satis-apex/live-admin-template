<template>
    <el-button-group>
        <el-tooltip
            class="box-item"
            effect="dark"
            content="View "
            placement="bottom"
            ><el-button
                :icon="View"
                :plain="isDarkMode"
                @click="emit('view', rowData)"
                size="small"
                rounded
            />
        </el-tooltip>
        <el-tooltip
            class="box-item"
            effect="dark"
            content="Edit "
            placement="bottom"
            v-if="iPropsValue('userCan', 'edit')"
            ><el-button
                type="primary"
                :plain="isDarkMode"
                @click="emit('edit', rowData)"
                :icon="Edit"
                size="small"
                rounded
        /></el-tooltip>
        <el-tooltip
            class="box-item"
            effect="dark"
            content="Delete "
            placement="bottom"
            v-if="iPropsValue('userCan', 'delete')"
            ><el-button
                type="danger"
                :plain="isDarkMode"
                @click="emit('delete', rowData)"
                :icon="Delete"
                size="small"
                rounded
        /></el-tooltip>
    </el-button-group>
</template>
<script setup>
import { Edit, Delete, View } from "@element-plus/icons-vue";
import { useInertiaPropsUtility } from "@/Composables/inertiaPropsUtility";
import { useAppUtility } from "@/Composables/appUtiility";
const { isDarkMode } = useAppUtility();
let { iPropsValue } = useInertiaPropsUtility();
const emit = defineEmits(["delete", "edit", "view"]);
let RowData = $ref(props.rowData);
const props = defineProps({
    rowData: Object,
});
</script>
