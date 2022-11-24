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
                :plain="darkMode"
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
                :plain="darkMode"
                @click="collapseAllMenu()"
            >
                <fa icon="angles-up" />
            </el-button>
        </el-tooltip>
    </el-button-group>
</template>
<script setup>
import { useAppUtility } from "@/Composables/appUtiility";
const { isDarkScheme } = useAppUtility();
const expandAllMenu = function () {
    jQuery(".dd").nestable("expandAll");
};
const collapseAllMenu = function () {
    jQuery(".dd").nestable("collapseAll");
};

const darkMode = $ref(isDarkScheme());
document.documentElement.addEventListener(
    "change-color-scheme",
    (e) => {
        darkMode = isDarkScheme();
    },
    false
);
</script>
