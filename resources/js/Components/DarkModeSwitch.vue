<script setup>
import { Moon, Sunny } from "@element-plus/icons-vue";
import { onMounted } from "@vue/runtime-core";
const setDark = () => {
    // Whenever the user explicitly chooses dark mode
    localStorage.theme = "dark";
    document.documentElement.classList.add("dark");
};
const setLight = () => {
    // Whenever the user explicitly chooses light mode
    localStorage.theme = "light";
    document.documentElement.classList.remove("dark");
};
const setDefault = () => {
    // Whenever the user explicitly chooses to respect the OS preference
    localStorage.removeItem("theme");
};
const isDark = () => localStorage.theme === "dark";
const toggleDark = () => {
    if (isDark()) setLight();
    else setDark();
};
onMounted(() => {
    if (
        localStorage.theme === "dark" ||
        (!("theme" in localStorage) &&
            window.matchMedia("(prefers-color-scheme: dark)").matches)
    ) {
        setDark();
    } else {
        setLight();
    }
});
const darkMode = $ref(isDark());
</script>

<template>
    <el-switch
        v-model="darkMode"
        @change="toggleDark()"
        class="ml-2 dark-mode-toggler"
        style="--el-switch-on-color: #2e2e2e; --el-switch-off-color: #e9e9e9"
        :active-icon="Moon"
        inline-prompt
        size="large"
        :inactive-icon="Sunny"
    />
    <!-- <span class="ml-2">{{ isDark() ? "Dark" : "Light" }}</span> -->
</template>
<style>
.dark-mode-toggler .el-switch__core {
    border: 1px solid #c5c1c1;
    box-shadow: 0px 0px 4px #d7d7d7;
}
.dark-mode-toggler .el-switch__core .el-switch__inner .is-icon,
.el-switch__core .el-switch__inner .is-text {
    color: black;
    font-size: 18px;
}
</style>
