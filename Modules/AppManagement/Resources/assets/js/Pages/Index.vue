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

//variable declare

const refAddEditForm = $ref(null);

const defaultData = $ref(iPropsValue("appInfo"));
watch(
    () => iPropsValue("appInfo"),
    () => {
        defaultData = iPropsValue("appInfo");
    }
);
</script>
<script>
export default {
    layout: "admin",
    data() {
        return {};
    },
};
</script>
