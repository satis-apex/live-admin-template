<template>
    <div>
        <AddEditForm ref="refAddEditForm" :parentFormInput="formInputNames" />
        <AddByExcelForm
            v-if="iPropsValue('userCan', 'massAdd')"
            ref="refAddByExcelForm"
            :parentFormInput="formInputNames"
        />
        <el-row class="mb-3 justify-between" :gutter="20">
            <el-col :xs="20" :sm="16" :md="14" :lg="12">
                <el-row :gutter="20">
                    <el-col :span="16"
                        ><el-input
                            v-model="dataQuery.keyword"
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
                    <el-col :span="8" class="!pl-0">
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
                                    :size="isScreenMd ? 'small' : 'default'"
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
                                    :size="isScreenMd ? 'small' : 'default'"
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

            <el-col :xs="4" :sm="4" :md="6" class="item-right text-right">
                <el-button
                    :plain="isDarkMode"
                    :size="isScreenMd ? 'small' : 'default'"
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
                    <div class="mb-4 flex">
                        <el-checkbox-group
                            v-model="viewableColumn"
                            size="small"
                        >
                            <template
                                :key="key"
                                v-for="(inputName, key) in tableColumnNames"
                            >
                                <el-checkbox :label="key" border class="!mr-2">
                                    {{ inputName }}
                                </el-checkbox>
                            </template>
                        </el-checkbox-group>
                    </div>
                    <el-table
                        v-loading="tableLoading"
                        id="printTable"
                        :data="dataList"
                        style="width: 100%"
                        ref="refTable"
                        table-layout="auto"
                        border
                        max-height="70vh"
                        @sort-change="sortList"
                        @filter-change="filterStatus"
                        :default-sort="{
                            prop: dataQuery.field,
                            order: dataQuery.direction,
                        }"
                    >
                        <el-table-column v-if="isScreenMd" type="expand">
                            <template #default="props">
                                <div class="ml-28">
                                    <p class="mb-2">
                                        Full Name: {{ props.row.name }}
                                    </p>
                                    <p class="mb-2">
                                        date: {{ props.row.date }}
                                    </p>
                                    <p class="mb-2">
                                        Address: {{ props.row.address }}
                                    </p>
                                    <p class="mb-2">
                                        Status: {{ props.row.status }}
                                    </p>
                                </div>
                            </template>
                        </el-table-column>
                        <el-table-column
                            type="index"
                            :index="indexMethod"
                            fixed="left"
                            width="62px"
                            v-if="!isScreenMd"
                        />
                        <el-table-column
                            :label="tableColumnNames.name"
                            sortable
                            prop="name"
                            v-if="isViewableColumn('name')"
                        />
                        <el-table-column
                            :label="tableColumnNames.date"
                            prop="date"
                            :formatter="dateFormatter"
                            v-if="!isScreenMd && isViewableColumn('date')"
                        />
                        <el-table-column
                            sortable
                            :label="tableColumnNames.address"
                            v-if="!isScreenMd && isViewableColumn('address')"
                            prop="address"
                        />
                        <el-table-column
                            :label="tableColumnNames.status"
                            prop="status"
                            column-key="status"
                            v-if="isViewableColumn('status')"
                            :filters="getFilterKey('status')"
                            filter-placement="bottom-end"
                        >
                            <template #default="scope">
                                <el-tag
                                    class="capitalize"
                                    :type="
                                        scope.row.status == 'active'
                                            ? 'success'
                                            : 'danger'
                                    "
                                    disable-transitions
                                    >{{ scope.row.status }}</el-tag
                                >
                            </template>
                        </el-table-column>
                        <el-table-column
                            align="right"
                            fixed="right"
                            :width="isScreenMd ? '70px' : '140px'"
                            label="Action"
                        >
                            <template #default="scope">
                                <ActionButton
                                    @edit="editForm"
                                    @delete="deleteForm"
                                    @view="viewForm"
                                    :rowData="scope.row"
                                />
                            </template>
                        </el-table-column>
                    </el-table>

                    <el-pagination
                        class="mt-5 !p-0"
                        v-model:currentPage="currentPage"
                        v-model:page-size="pageSize"
                        :total="totalSize"
                        :page-sizes="[25, 50, 75, 100]"
                        background
                        layout="total, sizes, prev, pager, next, jumper"
                        @size-change="changePageSize"
                        @current-change="changePage"
                    />
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
import { Inertia } from "@inertiajs/inertia";
//library imports
import { markRaw, onMounted, reactive, watch, ref } from "@vue/runtime-core";
import { useForm } from "@inertiajs/inertia-vue3";
//Component imports
import AddEditForm from "./Components/AddEditForm.vue";
import AddByExcelForm from "./Components/AddByExcelForm.vue";
import ActionButton from "./Components/ActionButton.vue";
import ViewForm from "./Components/ViewForm.vue";
import moment from "moment";
import { Plus, Delete, Search, DocumentAdd } from "@element-plus/icons-vue";
import { pickBy, debounce } from "lodash-es";
//composable function import
const { iPropsValue } = useInertiaPropsUtility();
const { filterObjectWithGroupedValue } = useObjectUtility();
const { isScreenMd, isDarkMode } = useAppUtility();
//variable declare
const refAddEditForm = $ref(null);
const refAddByExcelForm = $ref(null);
const refViewForm = $ref(null);
const exportLoading = $ref(false);
const tableLoading = $ref(false);
const viewableColumn = $ref(["name", "status", "address"]);
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
    fileName: "dataTableList",
});
const formInputNames = {
    name: "",
};
const addForm = () => refAddEditForm.showForm("Add");
const addExcelForm = () => refAddByExcelForm.showForm();
const editForm = (data) => refAddEditForm.showForm("Edit", data);
const viewForm = (data) => refViewForm.showForm(data);
const filterStatus = (data) => {
    dataQuery.filters = { ...dataQuery.filters, ...data };
    getDataFromStorage();
};
const deleteForm = (data) => {
    ElMessageBox.confirm("It will permanently delete. Continue?", "Warning", {
        type: "error",
        icon: markRaw(Delete),
        callback: (action) => {
            if (action == "confirm") {
                const formData = useForm();
                formData.delete(route("dataTable.destroy", data.id), {
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
const currentPage = $ref(iPropsValue("dataTableList", "current_page"));
const pageSize = $ref(iPropsValue("dataTableList", "per_page"));
const totalSize = $ref(iPropsValue("dataTableList", "total"));
const refTable = $ref(null);
const searchText = $ref("");
const filterDataList = $ref();
const indexMethod = (index) => (currentPage - 1) * pageSize + index + 1;
const isViewableColumn = (columnName) => viewableColumn.includes(columnName);
const dataList = $ref(iPropsValue("dataTableList", "data"));
const dataQuery = $ref({
    page: 1,
    limit: iPropsValue("dataQuery", "limit"),
    keyword: iPropsValue("dataQuery", "keyword"),
    field: iPropsValue("dataQuery", "field"),
    direction: iPropsValue("dataQuery", "direction"),
    filters: iPropsValue("dataQuery", "filters"),
});
watch(
    () => iPropsValue("dataTableList"),
    () => {
        dataList = iPropsValue("dataTableList", "data");
        currentPage = iPropsValue("dataTableList", "current_page");
        pageSize = iPropsValue("dataTableList", "per_page");
        totalSize = iPropsValue("dataTableList", "total");
    }
);
const changePageSize = (val) => {
    dataQuery.limit = val;
    getDataFromStorage();
};
const changePage = (val = 1) => {
    dataQuery.page = val;
    getDataFromStorage();
};
const searchFilter = debounce(() => {
    getDataFromStorage();
}, 500);

const getDataFromStorage = async () => {
    dataQuery = pickBy(dataQuery);
    Inertia.get(route("dataTable.index"), dataQuery, {
        replace: true,
        preserveState: true,
        onStart: () => (tableLoading = true),
        onFinish: () => (tableLoading = false),
    });
};
const sortList = (data) => {
    const { prop, order } = data;
    if (order) {
        if (prop === "index") {
            refTable.sort(() => -1);
            console.log("index");
        } else {
            dataQuery.field = prop;
            dataQuery.direction = order;
            handleFilter();
        }
    }
};
const handleFilter = (data) => {
    if (data === "") {
        resetFilters();
    }
    dataQuery.page = 1;
    getDataFromStorage();
};
const resetFilters = () => {
    dataQuery.filters = {};
};
const resetKeyword = () => {
    dataQuery.keyword = "";
    getDataFromStorage();
};
//table Export
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

</script>
<script>
export default {
    layout: "admin",
    data() {
        return {};
    },
};
</script>
<style lang="scss">
.el-table__body {
    width: 100%;
}
</style>
