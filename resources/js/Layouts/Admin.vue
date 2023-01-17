<template>
    <section class="el-container">
        <side-nav ref="refSideNav" :key="navComponentKey"></side-nav>
        <section class="el-container is-vertical bg-body">
            <SimpleBar style="height: 100vh; overflow-y: auto; width: 100%">
                <heads
                    @showMobileMenu="showDrawer"
                    @toggleDesktopMenu="toggleDesktopMenu"
                ></heads>

                <main class="el-main main-container-wrapper">
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
                </main>
                <footer class="el-footer">
                    &copy; {{ moment().year() }},
                    {{ iPropsValue("app_info", "title") }}
                </footer>
            </SimpleBar>
        </section>
    </section>

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
const navComponentKey = ref(0);

const showDrawer = () => refSideNav.showMenuDrawer();
const toggleDesktopMenu = () => refSideNav.toggleDesktopMenu();
watch(
    () => iPropsValue("app_menu"),
    () => {
        navComponentKey.value += 1;
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
    setCssProp("--complementary-color", appInfo.complementary_color);
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
