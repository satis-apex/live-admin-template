<template>
    <AddEditForm ref="refAddEditForm" />
    <el-row class="mb-3 justify-between" :gutter="20">
        <el-col :xs="18" :sm="12" :md="10">
            <el-row :gutter="20">
                <el-col :span="18"
                    ><el-input
                        v-model="searchText"
                        placeholder="Type to search"
                        @input="searchFilter"
                    >
                        <template #prepend
                            ><el-button :icon="Search"
                        /></template> </el-input
                ></el-col>
                <el-col :span="2"
                    ><el-tooltip
                        class="box-item"
                        effect="dark"
                        content="Add "
                        placement="bottom"
                    >
                        <el-button
                            data-action="expand-all"
                            type="success"
                            size="default"
                            rounded
                            @click="addForm"
                            :icon="Plus"
                        >
                        </el-button>
                    </el-tooltip>
                </el-col>
            </el-row>
        </el-col>

        <el-col :span="6" class="item-right text-right">
            <el-button type="success" @click="exportTable()">
                <fa icon="file-excel" />
            </el-button>
        </el-col>
    </el-row>
    <el-row :gutter="20">
        <el-col :span="24">
            <el-table
                id="printTable"
                class="p-6"
                :data="filterDataList"
                style="width: 100%"
                height="75vh"
            >
                <el-table-column type="index" fixed="left" width="50" />
                <el-table-column
                    label="Date"
                    prop="date"
                    :formatter="dateFormatter"
                />
                <el-table-column label="Name" sortable prop="name" />
                <el-table-column label="Address" prop="address" />
                <el-table-column
                    label="Status"
                    prop="status"
                    :filters="[
                        { text: 'Active', value: 'Active' },
                        { text: 'In-Active', value: 'In-Active' },
                    ]"
                    :filter-method="filterStatus"
                    filter-placement="bottom-end"
                >
                    <template #default="scope">
                        <el-tag
                            :type="
                                scope.row.status === 'Active'
                                    ? 'success'
                                    : 'danger'
                            "
                            disable-transitions
                            >{{ scope.row.status }}</el-tag
                        >
                    </template>
                </el-table-column>
                <el-table-column align="right" fixed="right" label="Action">
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
                v-model:currentPage="currentPage"
                v-model:page-size="pageSize"
                :total="totalSize"
                :page-sizes="[100, 200, 300, 400]"
                background
                layout="total, sizes, prev, pager, next, jumper"
                @size-change="changePageSize"
                @current-change="changePage"
            />
        </el-col>
    </el-row>
    <ViewForm ref="refViewForm" />
</template>
<script setup>
import { useInertiaPropsUtility } from "@/Composables/inertiaPropsUtility";
import { computed, markRaw, onMounted, reactive, ref } from "@vue/runtime-core";
import { useForm } from "@inertiajs/inertia-vue3";
import AddEditForm from "./Components/AddEditForm.vue";
import ActionButton from "./Components/ActionButton.vue";
import ViewForm from "./Components/ViewForm.vue";
import { Plus, Delete, Search } from "@element-plus/icons-vue";
import moment from "moment";
let { iPropsValue } = useInertiaPropsUtility();

const refAddEditForm = $ref(null);
const refViewForm = $ref(null);
const addForm = function () {
    refAddEditForm.showForm("Add");
};
const editForm = function (data) {
    refAddEditForm.showForm("Edit", data);
};
const deleteForm = function (data) {
    ElMessageBox.confirm("It will permanently delete. Continue?", "Warning", {
        type: "error",
        icon: markRaw(Delete),
        callback: (action) => {
            if (action == "confirm") {
                const deleteForm = useForm({
                    deleteId: data.id,
                });
                deleteForm.delete(route("{routeName}", data.id), {
                    preserveScroll: true,
                    onSuccess: () => {
                        deleteForm.reset();
                    },
                });
            }
        },
    });
    return;
};
const viewForm = function (data) {
    refViewForm.showForm(data);
};
const filterStatus = (value, row) => {
    return row.status === value;
};
const dateFormatter = (row, column) => {
    return moment(row.date).format("MMMM Do, YYYY");
};
const dataList = [
    {
        date: "2016-05-03",
        name: "Tom",
        address: "No. 189, Grove St, Los Angeles",
        status: "Active",
    },
    {
        date: "2016-05-02",
        name: "John",
        address: "No. 199, Grove St, Los Angeles",
        status: "Active",
    },
    {
        date: "2016-05-04",
        name: "Morgan",
        address: "No. 179, Grove St, Los Angeles",
        status: "In-Active",
    },
    {
        date: "2016-05-01",
        name: "Jessy",
        address: "No. 169, Grove St, Los Angeles",
        status: "In-Active",
    },
    {
        date: "2016-05-03",
        name: "Tom",
        address: "No. 189, Grove St, Los Angeles",
        status: "Active",
    },
    {
        date: "2016-05-02",
        name: "John",
        address: "No. 199, Grove St, Los Angeles",
        status: "Active",
    },
    {
        date: "2016-05-04",
        name: "Morgan",
        address: "No. 179, Grove St, Los Angeles",
        status: "In-Active",
    },
    {
        date: "2016-05-01",
        name: "Jessy",
        address: "No. 169, Grove St, Los Angeles",
        status: "In-Active",
    },
];

//table pagination / search related
let currentPage = $ref(1);
let pageSize = $ref(100);
let totalSize = $ref(0);
let page = $ref(1);
const searchText = $ref("");
const exportData = reactive({
    header: ["Name", "Date"],
    headerValue: ["name", "date"],
    fileName: "testfile",
});
let filterDataList = $ref();
const changePageSize = (val) => {
    pageSize = val;
    changePage();
};
const changePage = (val = 1) => {
    // this.busy = true;
    page = currentPage = val;
    const listStorage = dataList;
    // CHECK IF SEARCH EMPTY
    if (searchText == "") {
        totalSize = listStorage.length;
        const append = listStorage.slice(
            (page - 1) * pageSize,
            (page - 1) * pageSize + pageSize
        );
        filterDataList = append;
    } else {
        const filteredPosts = listStorage.filter(
            (data) =>
                data.name.toLowerCase().includes(searchText.toLowerCase()) ||
                data.address
                    .toString()
                    .toLowerCase()
                    .includes(searchText.toLowerCase())
        );
        totalSize = filteredPosts.length;
        const append = filteredPosts.slice(
            (page - 1) * pageSize,
            (page - 1) * pageSize + pageSize
        );
        filterDataList = append;
    }
    // this.busy = false;
};
const searchFilter = function () {
    // this.busy = true;
    page = currentPage = 1;
    const listStorage = dataList;

    const filteredPosts = listStorage.filter(
        (data) =>
            data.name.toLowerCase().includes(searchText.toLowerCase()) ||
            data.address
                .toString()
                .toLowerCase()
                .includes(searchText.toLowerCase())
    );
    totalSize = filteredPosts.length;
    const append = filteredPosts.slice(
        (page - 1) * pageSize,
        (page - 1) * pageSize + pageSize
    );
    filterDataList = append;
    // this.busy = false;
};
const exportTable = function () {
    import("@/Export2Excel").then((excel) => {
        const tHeader = exportData.header;
        const filterVal = exportData.headerValue;

        const data = formatJson(filterVal, dataList);
        excel.export_json_to_excel({
            header: tHeader,
            data,
            filename: exportData.fileName,
            autoWidth: exportData?.autoWidth ?? true,
            bookType: exportData?.fileType ?? "xlsx",
        });
    });
};
const formatJson = function (filterVal, jsonData) {
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
