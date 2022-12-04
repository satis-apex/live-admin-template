<script setup>
import { onMounted, watch } from "@vue/runtime-core";
import { useInertiaPropsUtility } from "@/Composables/inertiaPropsUtility";

const { iPropsValue } = useInertiaPropsUtility();
const title = function (status) {
    return {
        503: "503: Service Unavailable",
        500: "500: Server Error",
        404: "404: Page Not Found",
        403: "403: Forbidden",
        419: "419: Page Expired",
    }[status];
};
const description = function (status) {
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
const displayFlash = function () {
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
const displayException = function () {
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
<template></template>
