<template>
    <div class="common-layout">
        <el-container>
            <side-nav
                :parentIsCollapse="isMobile ? true : isCollapse"
            ></side-nav>
            <el-container class="bg-body">
                <SimpleBar style="height: 100vh; overflow-y: auto; width: 100%">
                    <heads></heads>
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
import { ref } from "vue";
import FlashMessage from "@/Layouts/FlashMessage.vue";
import { useInertiaPropsUtility } from "@/Composables/inertiaPropsUtility";

let { iPropsValue } = useInertiaPropsUtility();
const isCollapse = false;
const isMobile = navigator.userAgentData.mobile;
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
