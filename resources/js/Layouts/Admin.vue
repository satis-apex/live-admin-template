<template>
    <div class="common-layout">
        <el-container>
            <side-nav
                :parentIsCollapse="isMobile ? true : isCollapse"
            ></side-nav>
            <el-container>
                <heads></heads>
                <el-main><slot /></el-main>
                <el-footer>Footer</el-footer>
            </el-container>
        </el-container>
    </div>
</template>
<script setup>
import SideNav from "@/Layouts/SideNav.vue";
import heads from "@/Layouts/Head.vue";
</script>

<script>
export default {
    data() {
        return {
            isCollapse: false,
            isMobile: navigator.userAgentData.mobile,
        };
    },
    methods: {},
    components: {},
};
</script>
