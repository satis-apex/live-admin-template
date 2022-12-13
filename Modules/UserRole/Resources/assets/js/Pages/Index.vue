<template>
    <div>
        <AddEditForm ref="refAddEditForm" :parentFormInput="formInputNames" />
        <AddByExcelForm
            v-if="iPropsValue('userCan', 'massAdd')"
            ref="refAddByExcelForm"
            :parentFormInput="formInputNames"
        />
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
                    <div class="mb-4 flex">
                        <el-checkbox-group
                            v-model="viewableColumn"
                            size="small"
                            @change="searchFilter"
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
                        id="printTable"
                        :data="filterDataList"
                        style="width: 100%"
                        ref="refTable"
                        table-layout="auto"
                        border
                        max-height="70vh"
                    >
                        <el-table-column v-if="isScreenMd" type="expand">
                            <template #default="props">
                                <div class="ml-28">
                                    <p class="mb-2">
                                        Full Name: {{ props.row.name }}
                                    </p>
                                    <p class="mb-2">
                                        Total Users: {{ props.row.users_count }}
                                    </p>
                                </div>
                            </template>
                        </el-table-column>
                        <el-table-column
                            type="index"
                            :index="indexMethod"
                            fixed="left"
                            width="50px"
                        />
                        <el-table-column
                            :label="tableColumnNames.name"
                            sortable
                            prop="name"
                            v-if="isViewableColumn('name')"
                        />
                        <el-table-column
                            :label="tableColumnNames.users_count"
                            v-if="
                                !isScreenMd && isViewableColumn('users_count')
                            "
                            prop="users_count"
                        />
                        <el-table-column
                            align="right"
                            fixed="right"
                            width="140px"
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
                        :page-sizes="[100, 200, 300, 400]"
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
//composable function import
const { iPropsValue } = useInertiaPropsUtility();
const { filterObjectWithGroupedValue } = useObjectUtility();
const { isScreenMd, isDarkMode } = useAppUtility();
//variable declare
const refAddEditForm = $ref(null);
const refAddByExcelForm = $ref(null);
const refViewForm = $ref(null);
const exportLoading = $ref(false);
const viewableColumn = $ref(["name", "users_count"]);
const tableColumnNames = {
    name: "Role",
    users_count: "Total Users",
};
//export table column refrence
const exportTableOption = reactive({
    header: ["Role", "Total Users"],
    headerValue: ["name", "users_count"],
    fileName: "userRoleList",
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
                formData.delete(route("userRole.destroy", data.id), {
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
const dataList = $ref(iPropsValue("userRoleLists"));
watch(
    () => iPropsValue("userRoleLists"),
    () => {
        dataList = iPropsValue("userRoleLists");
        changePage(currentPage);
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
