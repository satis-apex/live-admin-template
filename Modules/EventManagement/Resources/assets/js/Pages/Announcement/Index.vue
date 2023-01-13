<template>
    <div>
        <AddEditForm ref="refAddEditForm" :parentFormInput="formInputNames" />

        <el-row class="mb-3 justify-between" :gutter="20">
            <el-col :xs="12" :sm="12" :md="10">
                <el-row :gutter="20">
                    <el-col :span="16"
                        ><el-input
                            v-model="searchText"
                            placeholder="Type to search"
                            @input="searchFilter"
                            class="searchable"
                            clearable
                        >
                            <template #prepend
                                ><el-button :icon="Search"
                            /></template>
                        </el-input>
                    </el-col>
                    <el-col :span="8">
                        <el-button-group>
                            <el-tooltip
                                v-if="iPropsValue('userCan', 'create')"
                                class="box-item"
                                effect="dark"
                                content="Add "
                                placement="bottom"
                            >
                                <el-button
                                    :plain="isDarkMode"
                                    type="success"
                                    size="default"
                                    rounded
                                    @click="addForm"
                                    :icon="Plus"
                                >
                                </el-button>
                            </el-tooltip>
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
                    </el-col>
                </el-row>
            </el-col>

            <el-col :span="6" class="item-right text-right">
                <el-button
                    :plain="isDarkMode"
                    type="success"
                    :loading="exportLoading"
                    @click="exportTable()"
                >
                    <fa icon="file-excel" />
                </el-button>
            </el-col>
        </el-row>
        <el-row :gutter="20">
            <el-col :span="24">
                <el-card>
                    <AcademicCalender />
                </el-card>
            </el-col>
        </el-row>
        <ViewForm ref="refViewForm" />
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
import AddEditForm from "./Components/AddEditForm.vue";
import AddByExcelForm from "./Components/AddByExcelForm.vue";
import ActionButton from "./Components/ActionButton.vue";
import ViewForm from "./Components/ViewForm.vue";
import moment from "moment";
import { Plus, Delete, Search, DocumentAdd } from "@element-plus/icons-vue";
//composable function import
const { iPropsValue } = useInertiaPropsUtility();
const { filterObjectWithGroupedValue } = useObjectUtility();
const { isScreenMd, isDarkMode } = useAppUtility();
//variable declare
const isMobile = $ref(isScreenMd);
const refAddEditForm = $ref(null);
const refAddByExcelForm = $ref(null);
const refViewForm = $ref(null);
const exportLoading = $ref(false);
const viewableColumn = $ref(
    !isMobile ? ["name", "status", "address"] : ["name", "status"]
);
const tableColumnNames = {
    name: "Full Name",
    status: "Status",
    address: "Address",
    date: "Date",
};

//export table column refrence
const exportTableOption = reactive({
    header: ["Name", "Date"],
    headerValue: ["name", "date"],
    fileName: "AnnouncementList",
});
const formInputNames = {
    name: "",
};
const addForm = () => refAddEditForm.showForm("Add");
const addExcelForm = () => refAddByExcelForm.showForm();
const editForm = (data) => refAddEditForm.showForm("Edit", data);
const viewForm = (data) => refViewForm.showForm(data);
const filterStatus = (value, row) => row.status === value;
const deleteForm = (data) => {
    ElMessageBox.confirm("It will permanently delete. Continue?", "Warning", {
        type: "error",
        icon: markRaw(Delete),
        callback: (action) => {
            if (action == "confirm") {
                const formData = useForm();
                formData.delete(route("announcement.destroy", data.id), {
                    preserveScroll: true,
                    onSuccess: () => {
                        formData.reset();
                    },
                });
            }
        },
    });
    return;
};
const getFilterKey = (columnKey) => {
    const filteredValues = filterObjectWithGroupedValue(dataList, columnKey);
    let filterKey = [];
    filteredValues.forEach((item, index) => {
        filterKey[index] = { text: item, value: item };
    });
    return filterKey;
};
const dateFormatter = (row, column) => moment(row.date).format("MMMM Do, YYYY");
//table pagination / search related
const currentPage = $ref(1);
const pageSize = $ref(100);
const totalSize = $ref(0);
const refTable = ref(null);
const searchText = $ref("");
const filterDataList = $ref();
const indexMethod = (index) => (currentPage - 1) * pageSize + index + 1;
const isViewableColumn = (columnName) => viewableColumn.includes(columnName);
const dataList = $ref(iPropsValue("AnnouncementList"));
watch(
    () => iPropsValue("AnnouncementList"),
    () => {
        dataList = iPropsValue("AnnouncementList");
        changePage(currentPage);
    }
);
watch(
    () => isMobile,
    () => {
        viewableColumn = !isMobile
            ? ["name", "status", "address"]
            : ["name", "status"];
    }
);
const changePageSize = (val) => {
    pageSize = val;
    changePage();
};

const changePage = (val = 1) => {
    currentPage = val;
    const listStorage = dataList;
    // CHECK IF SEARCH EMPTY
    if (listStorage) {
        if (searchText == "") {
            totalSize = listStorage.length;
            const append = listStorage.slice(
                (currentPage - 1) * pageSize,
                (currentPage - 1) * pageSize + pageSize
            );
            filterDataList = append;
        } else {
            const filteredPosts = listStorage.filter((data) => {
                let hasValue = false;
                viewableColumn.forEach((value) => {
                    if (
                        data[value] &&
                        data[value]
                            .toString()
                            .toLowerCase()
                            .includes(searchText.toLowerCase())
                    )
                        hasValue = true;
                });
                return hasValue;
            });
            totalSize = filteredPosts.length;
            const append = filteredPosts.slice(
                (currentPage - 1) * pageSize,
                (currentPage - 1) * pageSize + pageSize
            );
            filterDataList = append;
        }
    }
};
const searchFilter = () => {
    currentPage = 1;
    const listStorage = dataList;

    const filteredPosts = listStorage.filter((data) => {
        let hasValue = false;
        viewableColumn.forEach((value) => {
            if (
                data[value] &&
                data[value]
                    .toString()
                    .toLowerCase()
                    .includes(searchText.toLowerCase())
            )
                hasValue = true;
        });
        return hasValue;
    });
    totalSize = filteredPosts.length;
    const append = filteredPosts.slice(
        (currentPage - 1) * pageSize,
        (currentPage - 1) * pageSize + pageSize
    );
    filterDataList = append;
};
const exportTable = () => {
    exportLoading = true;
    const exportNow = function (excel) {
        return new Promise((resolve) => {
            const tHeader = exportTableOption.header;
            const filterVal = exportTableOption.headerValue;

            const data = formatJson(filterVal, dataList);
            excel.export_json_to_excel({
                header: tHeader,
                data,
                filename: exportTableOption.fileName,
                autoWidth: exportTableOption?.autoWidth ?? true,
                bookType: exportTableOption?.fileType ?? "xlsx",
            });
            resolve("resolved");
        });
    };
    import("@/Export2Excel")
        .then(async (excel) => {
            await exportNow(excel);
        })
        .then(() => {
            exportLoading = false;
        });
};
const exportTemplate = () => {
    const exportNow = function (excel) {
        return new Promise((resolve) => {
            const tHeader = Object.keys(formInputNames);

            const data = [];
            excel.export_json_to_excel({
                header: tHeader,
                data,
                filename: exportTableOption.fileName + "ExcelTemplate",
                autoWidth: exportTableOption?.autoWidth ?? true,
                bookType: exportTableOption?.fileType ?? "xlsx",
            });
            resolve("resolved");
        });
    };
    import("@/Export2Excel").then(async (excel) => {
        await exportNow(excel);
    });
};
const formatJson = (filterVal, jsonData) => {
    return jsonData.map((v) =>
        filterVal.map((j) => {
            if (j === "date") {
                return moment(v[j]).format("MMMM Do, YYYY");
            } else {
                return v[j];
            }
        })
    );
};
//page event cycle
onMounted(() => {
    changePage();
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
