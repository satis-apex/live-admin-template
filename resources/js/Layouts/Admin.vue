<template>
    <div class="common-layout">
        <el-container>
            <side-nav
                :parentIsCollapse="isMobile ? true : isCollapse"
            ></side-nav>
            <el-container>
                <heads></heads>
                <el-main class="main-container-wrapper"><slot /></el-main>
                <el-footer
                    >&copy; {{ moment().year() }},
                    {{ iPropsValue("app_info", "title") }}
                </el-footer>
            </el-container>
        </el-container>
    </div>
</template>
<script setup>
import SideNav from "@/Layouts/SideNav.vue";
import heads from "@/Layouts/Head.vue";

import moment from "moment";
import { watch } from "vue";
import { useInertiaPropsUtility } from "@/Composables/inertiaPropsUtility";

let { iPropsValue } = useInertiaPropsUtility();
const isCollapse = false;
const isMobile = navigator.userAgentData.mobile;
watch(
    () => iPropsValue("flash"),
    () => {
        if (
            iPropsValue("flash", "message") != undefined &&
            iPropsValue("flash", "message") != ""
        ) {
            ElNotification({
                title: iPropsValue("flash", "title"),
                message: iPropsValue("flash", "message"),
                dangerouslyUseHTMLString: iPropsValue("flash", "hasHTML"),
                type: iPropsValue("flash", "type"),
            });
        }
    }
);
</script>

<script></script>
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
