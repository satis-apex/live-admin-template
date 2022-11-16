<template>
    <el-button-group>
        <el-tooltip
            class="box-item"
            effect="dark"
            content="Expand All Menu"
            placement="top"
        >
            <el-button
                data-action="expand-all"
                type="primary"
                size="default"
                rounded
                @click="expandAllMenu()"
            >
                <fa icon="angles-down" />
            </el-button>
        </el-tooltip>
        <el-tooltip
            class="box-item"
            effect="dark"
            content="Collapse All Menu"
            placement="top"
        >
            <el-button
                data-action="collapse-all"
                type="primary"
                size="default"
                rounded
                @click="collapseAllMenu()"
            >
                <fa icon="angles-up" />
            </el-button>
        </el-tooltip>
    </el-button-group>
</template>
<script setup>
const expandAllMenu = function () {
    jQuery(".dd").nestable("expandAll");
};
const collapseAllMenu = function () {
    jQuery(".dd").nestable("collapseAll");
};
</script>
