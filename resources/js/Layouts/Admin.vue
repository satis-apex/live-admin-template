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
                    <el-main class="main-container-wrapper">
                        <div v-if="iPropsValue('auth', 'isImpersonator')">
                            <div
                                class="text-center p-2 mb-4 bg-gray-300 dark:bg-neutral-800 rounded-md"
                            >
                                <fa icon="circle-info" />
                                Your are Impersonating
                                <strong>{{
                                    iPropsValue("auth", "user.fullName")
                                }}</strong
                                >.
                                <span
                                    class="text-blue-500 cursor-pointer hover:underline"
                                    @click="leaveImpersonate()"
                                    ><fa icon="right-from-bracket" /> leave
                                    Impersonation.</span
                                >
                            </div>
                        </div>
                        <slot />
                    </el-main>
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
import { ref, onMounted, watch, markRaw } from "@vue/runtime-core";
import FlashMessage from "@/Layouts/FlashMessage.vue";
import { useInertiaPropsUtility } from "@/Composables/inertiaPropsUtility";
import { Switch } from "@element-plus/icons-vue";
import { useForm } from "@inertiajs/inertia-vue3";
const { iPropsValue } = useInertiaPropsUtility();

const refSideNav = $ref(null);
let navComponentKey = $ref(0);

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
const leaveImpersonate = () => {
    ElMessageBox.confirm(
        "You are about to leave Impersonated User Account. Continue?",
        "Info",
        {
            dangerouslyUseHTMLString: true,
            type: "warning",
            icon: markRaw(Switch),
            callback: (action) => {
                if (action == "confirm") {
                    const formData = useForm();
                    formData.delete(route("user.revert-impersonate"), {
                        preserveScroll: true,
                        onSuccess: () => {
                            formData.reset();
                        },
                    });
                }
            },
        }
    );
    return;
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
