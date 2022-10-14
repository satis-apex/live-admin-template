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
import { onMounted, watch } from "vue";
import { useInertiaPropsUtility } from "@/Composables/inertiaPropsUtility";

let { iPropsValue } = useInertiaPropsUtility();
const isCollapse = false;
const isMobile = navigator.userAgentData.mobile;
let title = function (status) {
    return {
        503: "503: Service Unavailable",
        500: "500: Server Error",
        404: "404: Page Not Found",
        403: "403: Forbidden",
        419: "419: Page Expired",
    }[status];
};
let description = function (status) {
    return {
        503: "Sorry, we are doing some maintenance. Please check back soon.",
        500: "Whoops, something went wrong on our servers.",
        404: "Sorry, the page you are looking for could not be found.",
        403: "Sorry, you are forbidden from accessing this request.",
        419: "Sorry, requested page has expired. Please reload the page.",
    }[status];
};
watch(
    () => iPropsValue("page_exception"),
    () => {
        displayException();
    }
);
watch(
    () => iPropsValue("flash"),
    () => {
        displayFlash();
    }
);
let displayFlash = function () {
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
};
let displayException = function () {
    if (
        iPropsValue("page_exception", "statusCode") != undefined &&
        iPropsValue("page_exception", "statusCode") != ""
    ) {
        ElNotification({
            title: title(iPropsValue("page_exception", "statusCode")),
            message: description(iPropsValue("page_exception", "statusCode")),
            type: "error",
        });
    }
};
onMounted(() => {
    displayFlash();
    displayException();
});
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
