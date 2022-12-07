<template>
    <el-button-group>
        <el-tooltip
            class="box-item"
            effect="dark"
            content="View "
            placement="bottom"
            ><el-button
                :plain="isDarkMode"
                :icon="View"
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
                :plain="isDarkMode"
                :disabled="editable(rowData)"
                type="primary"
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
                :plain="isDarkMode"
                :disabled="deletable(rowData)"
                type="danger"
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
const editable = (rowData) => {
    if (rowData.id == 1 || rowData.id == 2) {
        return true;
    }
    return false;
};
const deletable = (rowData) => {
    if (rowData.id == 1 || rowData.id == 2) {
        return true;
    }
    if (rowData.users_count > 0) {
        return true;
    }
    return false;
};
</script>
