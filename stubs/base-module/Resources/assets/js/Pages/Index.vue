<template>
    <el-row class="mb-3" :gutter="20">
        <el-col :xs="12" :sm="8" :md="6">
            <el-input v-model="search" placeholder="Type to search">
                <template #prepend><el-button :icon="Search" /></template>
            </el-input>
        </el-col>
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

    <el-row :gutter="20">
        <el-col :span="24">
            <el-table :data="filterTableData" style="width: 100%" height="75vh">
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
        </el-col>
    </el-row>
    <AddEditForm ref="refAddEditForm" />
    <ViewForm ref="refViewForm" />
</template>
<script setup>
import { useInertiaPropsUtility } from "@/Composables/inertiaPropsUtility";
import { computed, markRaw, ref } from "@vue/runtime-core";
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
                deleteForm.delete(route("{routeName}.delete", data.id), {
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

const search = ref("");
const filterTableData = computed(() =>
    tableData.filter(
        (data) =>
            !search.value ||
            data.name.toLowerCase().includes(search.value.toLowerCase())
    )
);
const filterStatus = (value, row) => {
    return row.status === value;
};
const dateFormatter = (row, column) => {
    return moment(row.date).format("MMMM Do, YYYY");
};
const tableData = [
    {
        date: "2016-05-03",
        name: "Tom",
        address: "No. 189, Grove St, Los Angeles",
        status: "Active",
    },
    {
        date: "2016-05-02",
        name: "John",
        address: "No. 189, Grove St, Los Angeles",
        status: "Active",
    },
    {
        date: "2016-05-04",
        name: "Morgan",
        address: "No. 189, Grove St, Los Angeles",
        status: "In-Active",
    },
    {
        date: "2016-05-01",
        name: "Jessy",
        address: "No. 189, Grove St, Los Angeles",
        status: "In-Active",
    },
];
</script>
<script>
export default {
    layout: "admin",
    data() {
        return {};
    },
};
</script>
