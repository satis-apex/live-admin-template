<template>
    <div>
        <!-- <AddByExcelForm
            ref="refAddByExcelForm"
            :parentFormInput="formInputNames"
            @exportTemplate="exportTemplate"
        /> -->
        <el-row class="mb-3 justify-between" :gutter="20">
            <el-col :xs="12" :sm="12" :md="10">
                <el-row :gutter="20">
                    <!-- <el-col :span="8">
                        <el-button-group>
                            <el-tooltip
                                v-if="iPropsValue('userCan', 'massAdd')"
                                class="box-item"
                                effect="dark"
                                content="Excel Add"
                                placement="bottom"
                            >
                                <el-button
                                    :plain="isDarkMode"
                                    type="warning"
                                    size="default"
                                    rounded
                                    @click="addExcelForm"
                                    :icon="DocumentAdd"
                                >
                                </el-button>
                            </el-tooltip>
                        </el-button-group>
                    </el-col> -->
                </el-row>
            </el-col>
        </el-row>
        <el-row :gutter="20">
            <el-col :span="24">
                <el-card>
                    <AcademicCalender />
                </el-card>
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
import AcademicCalender from "./Components/AcademicCalender.vue";
// import AddByExcelForm from "./Components/AddByExcelForm.vue";
import moment from "moment";
import { Plus, Delete, Search, DocumentAdd } from "@element-plus/icons-vue";
//composable function import
const { iPropsValue } = useInertiaPropsUtility();
const { isScreenMd, isDarkMode } = useAppUtility();
//variable declare
const isMobile = $ref(isScreenMd);
const refAddByExcelForm = $ref(null);
const exportLoading = $ref(false);
// const tableColumnNames = {
//     name: "Full Name",
//     status: "Status",
//     address: "Address",
//     date: "Date",
// };

// const formInputNames = {
//     title: "",
//     detail: "",
//     id: "",
//     allDay: false,
//     holiday: false,
//     notify: false,
//     viewer: ["Su-Admin"],
//     start: "",
//     end: "",
//     private: false,
// };

const addExcelForm = () => refAddByExcelForm.showForm();

const dateFormatter = (row, column) => moment(row.date).format("MMMM Do, YYYY");
//table pagination / search related

// const exportTableOption = reactive({
//     header: ["Name", "Date"],
//     headerValue: ["name", "date"],
//     fileName: "AnnouncementList",
// });
// const exportTemplate = () => {
//     const exportNow = function (excel) {
//         return new Promise((resolve) => {
//             const tHeader = Object.keys(formInputNames);

//             const data = [];
//             excel.export_json_to_excel({
//                 header: tHeader,
//                 data,
//                 filename: exportTableOption.fileName + "ExcelTemplate",
//                 autoWidth: exportTableOption?.autoWidth ?? true,
//                 bookType: exportTableOption?.fileType ?? "xlsx",
//             });
//             resolve("resolved");
//         });
//     };
//     import("@/Export2Excel").then(async (excel) => {
//         await exportNow(excel);
//     });
// };
// const formatJson = (filterVal, jsonData) => {
//     return jsonData.map((v) =>
//         filterVal.map((j) => {
//             if (j === "date") {
//                 return moment(v[j]).format("MMMM Do, YYYY");
//             } else {
//                 return v[j];
//             }
//         })
//     );
// };
//page event cycle
onMounted(() => {});
</script>
<script>
export default {
    layout: "admin",
    data() {
        return {};
    },
};
</script>
