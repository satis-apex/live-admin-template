<template>
    <div>
        <el-row :gutter="20">
            <el-col :span="24">
                <AddEditForm />
            </el-col>
        </el-row>
    </div>
</template>
<script setup>
import AddEditForm from "./Components/AddEditForm.vue";
</script>
<script>
export default {
    layout: "admin",
    data() {
        return {};
    },
};
</script>
