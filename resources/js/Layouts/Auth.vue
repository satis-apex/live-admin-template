<template>
    <div class="common-layout">
        <el-container>
            <!-- <el-header>auth Header</el-header> -->
            <el-main class="!p-0"> <slot /></el-main>
        </el-container>
    </div>
</template>
<script>
export default {
    data() {
        return {
            // registerBg2: "img/register_bg_2.png",
        };
    },
    components: {},
};
</script>
