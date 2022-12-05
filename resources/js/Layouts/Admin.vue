<template>
    <div class="common-layout">
        <el-container>
            <side-nav ref="refSideNav" :key="navComponentKey"></side-nav>
            <el-container class="bg-body">
                <SimpleBar style="height: 100vh; overflow-y: auto; width: 100%">
                    <el-affix :offset="0.1" style="width: 100%">
                        <heads
                            @showMobileMenu="showDrawer"
                            @toggleDesktopMenu="toggleDesktopMenu"
                        ></heads>
                    </el-affix>
                    <el-main class="main-container-wrapper"><slot /></el-main>
                    <el-footer
                        >&copy; {{ moment().year() }},
                        {{ iPropsValue("app_info", "title") }}
                    </el-footer>
                </SimpleBar>
            </el-container>
        </el-container>
    </div>
    <FlashMessage />
</template>
<script setup>
import { SimpleBar } from "simplebar-vue3";
import SideNav from "@/Layouts/SideNav.vue";
import heads from "@/Layouts/Head.vue";
import moment from "moment";
import { ref, onMounted, watch } from "@vue/runtime-core";
import FlashMessage from "@/Layouts/FlashMessage.vue";
import { useInertiaPropsUtility } from "@/Composables/inertiaPropsUtility";
const { iPropsValue } = useInertiaPropsUtility();

const refSideNav = $ref(null);
const navComponentKey = $ref(0);

const showDrawer = () => refSideNav.showMenuDrawer();
const toggleDesktopMenu = () => refSideNav.toggleDesktopMenu();
watch(
    () => iPropsValue("app_menu"),
    () => {
        navComponentKey += 1;
    }
);
const setCssProp = (propName, value) => {
    document.documentElement.style.setProperty(propName, value);
};
const setAppThemeColor = function () {
    const appInfo = iPropsValue("app_info");
    setCssProp("--primary-color", appInfo.primary_color);
    setCssProp("--primary-light-color", appInfo.primary_light_color);
    setCssProp("--primary-dark-color", appInfo.primary_dark_color);
    setCssProp("--complementary_color", appInfo.complementary_color);
};
onMounted(() => {
    setAppThemeColor();
    // document.documentElement.style.setProperty("--el-color-primary", "pink");
    // document.documentElement.style.setProperty("--el-menu-text-color", "pink");
    // document.documentElement.style.setProperty(
    //     "--el-menu-active-color",
    //     "pink"
    // );
    // document.documentElement.style.setProperty(
    //     "--el-color-primary-light-3",
    //     "pink"
    // );
});
</script>

<style scoped>
.el-footer {
    height: 42px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.main-container-wrapper {
    min-height: calc(100vh - 102px); /* (head + footer height)*/
}
</style>
