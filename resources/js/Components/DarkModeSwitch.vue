<script setup>
import { Moon, Sunny } from "@element-plus/icons-vue";
import { onMounted, ref } from "@vue/runtime-core";
import { useAppUtility } from "@/Composables/appUtiility";
const { isDarkScheme, setDarkScheme, setLightScheme, setDefaultScheme } =
    useAppUtility();
const event = new Event("change-color-scheme");
const toggleScheme = () => {
    if (isDarkScheme()) setLightScheme();
    else setDarkScheme();

    document.documentElement.dispatchEvent(event);
};
onMounted(() => {
    if (
        localStorage.theme === "dark" ||
        (!("theme" in localStorage) &&
            window.matchMedia("(prefers-color-scheme: dark)").matches)
    ) {
        setDarkScheme();
    } else {
        setLightScheme();
    }
});
const darkMode = ref(isDarkScheme());
</script>

<template>
    <el-switch
        v-model="darkMode"
        @change="toggleScheme()"
        class="mr-3 dark-mode-toggler"
        style="--el-switch-on-color: #2e2e2e; --el-switch-off-color: #e9e9e9"
        :active-icon="Moon"
        inline-prompt
        size="large"
        :inactive-icon="Sunny"
    />
</template>
<style>
.dark-mode-toggler .el-switch__core {
    border: 1px solid #bdbdbd;
    box-shadow: 0px 0px 3px #d7d7d7;
}
.dark-mode-toggler .el-switch__core .el-switch__inner .is-icon,
.el-switch__core .el-switch__inner .is-text {
    color: black;
    font-size: 18px;
}
</style>
