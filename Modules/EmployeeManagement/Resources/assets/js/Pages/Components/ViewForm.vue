<template>
    <el-dialog
        v-model="FormVisible"
        :before-close="closeForm"
        title="View Employee"
    >
        <template #default>
            <div class="text-center">
                <el-avatar
                    :size="120"
                    :src="viewData.avatar"
                    @error="errorHandler"
                    class="shadow-lg border-1"
                >
                    <img
                        src="https://cube.elemecdn.com/e/fd/0fc7d20532fdaf769a25683617711png.png"
                    />
                </el-avatar>
                <p
                    class="text-base mb-5 font-semibold text-gray-500 capitalize"
                >
                    {{ viewData.fullName }}
                </p>
                <div>
                    <el-descriptions
                        class="info-list sm:px-8 text-base"
                        :column="1"
                        border
                    >
                        <el-descriptions-item
                            label="Address"
                            label-align="left"
                            align="right"
                            >{{ viewData.address }}</el-descriptions-item
                        >
                        <el-descriptions-item
                            label="Phone"
                            label-align="left"
                            align="right"
                            >{{ viewData.phone }}</el-descriptions-item
                        >
                        <el-descriptions-item
                            label="Gender"
                            label-align="left"
                            align="right"
                        >
                            {{ viewData.gender }}</el-descriptions-item
                        >
                        <el-descriptions-item
                            label="Email"
                            label-align="left"
                            align="right"
                            >{{ viewData.email }}</el-descriptions-item
                        >
                        <el-descriptions-item
                            label="Role"
                            label-align="left"
                            align="right"
                            >{{
                                viewData.account?.roles[0].name
                            }}</el-descriptions-item
                        >
                        <el-descriptions-item
                            label="Joined Date"
                            label-align="left"
                            align="right"
                            >{{ viewData.joined_date }}</el-descriptions-item
                        >
                    </el-descriptions>
                </div>
            </div>
        </template>
    </el-dialog>
</template>
<script setup>
const FormVisible = $ref(false);
let viewData = $ref();
const closeForm = () => {
    FormVisible = false;
};
const showForm = function (data) {
    FormVisible = true;
    viewData = data;
};
defineExpose({
    showForm,
});
</script>
<style lang="scss">
.info-list {
    table {
        td.el-descriptions__cell {
            padding: 3px 3px 3px 8px !important;
        }
    }
}
</style>
