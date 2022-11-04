<template>
    <el-button-group>
        <el-button
            data-action="expand-all"
            type="primary"
            size="default"
            rounded
            @click="expandAllMenu()"
        >
            Expand All
        </el-button>
        <el-button
            data-action="collapse-all"
            type="primary"
            size="default"
            rounded
            @click="collapseAllMenu()"
        >
            Collapse All
        </el-button>
    </el-button-group>
</template>
<script setup>
let expandAllMenu = function () {
    jQuery(".dd").nestable("expandAll");
};
let collapseAllMenu = function () {
    jQuery(".dd").nestable("collapseAll");
};
</script>
