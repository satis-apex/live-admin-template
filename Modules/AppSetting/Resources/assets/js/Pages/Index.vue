<template>
    <div>
        <el-row :gutter="20">
            <el-col :span="24">
                <AddEditForm
                    ref="refAddEditForm"
                    :parentDefaultData="defaultData"
                />
            </el-col>
        </el-row>
    </div>
</template>
<script setup>
//composable imports
import { useInertiaPropsUtility } from "@/Composables/inertiaPropsUtility";
import { useObjectUtility } from "@/Composables/objectUtility";
import { useAppUtility } from "@/Composables/appUtiility";

//library imports
import { markRaw, onMounted, reactive, watch, ref } from "@vue/runtime-core";
import { useForm } from "@inertiajs/inertia-vue3";
//Component imports
import AddEditForm from "./Components/AddEditForm.vue";

import moment from "moment";
import { Plus, Delete, Search, DocumentAdd } from "@element-plus/icons-vue";
//composable function import
const { iPropsValue } = useInertiaPropsUtility();
const { filterObjectWithGroupedValue } = useObjectUtility();
const { mediaCheck } = useAppUtility();
//variable declare
const mobileView = $ref(mediaCheck("md"));
const refAddEditForm = $ref(null);

const defaultData = $ref(iPropsValue("appInfo"));
watch(
    () => iPropsValue("appInfo"),
    () => {
        defaultData = iPropsValue("appInfo");
    }
);

//page event cycle
onMounted(() => {
    window.addEventListener("resize", () => {
        mobileView = mediaCheck("md");
    });
});
</script>
<script>
export default {
    layout: "admin",
    data() {
        return {};
    },
};
</script>
