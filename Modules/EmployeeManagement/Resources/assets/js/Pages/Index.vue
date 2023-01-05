<template>
    <div>
        <AddEditForm
            ref="refAddEditForm"
            :roles="roleList"
            :parentFormInput="formInputNames"
        />
        <AddByExcelForm
            v-if="iPropsValue('userCan', 'massAdd')"
            ref="refAddByExcelForm"
            :parentFormInput="formInputNames"
            @export-template="exportTemplate"
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
                                        Full Name: {{ props.row.fullName }}
                                    </p>
                                    <p class="mb-2">
                                        Email: {{ props.row.email }}
                                    </p>
                                    <p class="mb-2">
                                        Joined Date:
                                        {{ props.row.joined_date ?? "-" }}
                                    </p>
                                    <p class="mb-2">
                                        Address: {{ props.row.address ?? "-" }}
                                    </p>
                                    <p class="mb-2">
                                        Phone: {{ props.row.phone ?? "-" }}
                                    </p>
                                    <p class="mb-2">
                                        Account Status:
                                        <StatusBadge
                                            :type="
                                                props.row.account != null
                                                    ? 'success'
                                                    : 'danger'
                                            "
                                            >{{
                                                props.row.account != null
                                                    ? "active"
                                                    : "not created"
                                            }}
                                        </StatusBadge>
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
                            :label="tableColumnNames.fullName"
                            sortable
                            prop="fullName"
                            v-if="isViewableColumn('fullName')"
                        />
                        <el-table-column
                            :label="tableColumnNames.email"
                            v-if="isViewableColumn('email')"
                            prop="email"
                        />
                        <el-table-column
                            :label="tableColumnNames.address"
                            v-if="isViewableColumn('address')"
                            prop="address"
                        />

                        <el-table-column
                            :label="tableColumnNames.phone"
                            v-if="isViewableColumn('phone')"
                            prop="phone"
                        />
                        <el-table-column
                            :label="tableColumnNames.joined_date"
                            prop="joined_date"
                            :formatter="dateFormatter"
                            v-if="isViewableColumn('joined_date')"
                        />
                        <el-table-column
                            :label="tableColumnNames.account_status"
                            v-if="isViewableColumn('account_status')"
                            :filters="[
                                { text: 'active', value: 'active' },
                                { text: 'not created', value: 'not created' },
                            ]"
                            :filter-method="filterStatus"
                            filter-placement="bottom-end"
                        >
                            <template #default="scope">
                                <StatusBadge
                                    :type="
                                        scope.row.account != null
                                            ? 'success'
                                            : 'danger'
                                    "
                                    >{{
                                        scope.row.account != null
                                            ? "active"
                                            : "not created"
                                    }}
                                </StatusBadge>
                            </template>
                        </el-table-column>
                        <el-table-column
                            align="center"
                            fixed="right"
                            label="Action"
                            width="75px"
                        >
                            <template #default="scope">
                                <el-dropdown trigger="click">
                                    <span class="el-dropdown-link">
                                        <el-button
                                            type="primary"
                                            :plain="isDarkMode"
                                            :icon="MoreFilled"
                                            circle
                                        />
                                    </span>
                                    <template #dropdown>
                                        <el-dropdown-menu>
                                            <el-dropdown-item
                                                :disabled="
                                                    scope.row.account != null
                                                        ? true
                                                        : false
                                                "
                                                @click="
                                                    scope.row.account != null
                                                        ? false
                                                        : createAccount(
                                                              scope.row
                                                          )
                                                "
                                            >
                                                Create Account
                                            </el-dropdown-item>
                                            <el-dropdown-item
                                                :disabled="
                                                    scope.row.account != null
                                                        ? false
                                                        : true
                                                "
                                                @click="
                                                    scope.row.account != null
                                                        ? resetPassword(
                                                              scope.row
                                                          )
                                                        : false
                                                "
                                            >
                                                Reset Password
                                            </el-dropdown-item>
                                            <el-dropdown-item
                                                v-if="
                                                    iPropsValue(
                                                        'userCan',
                                                        'impersonate'
                                                    )
                                                "
                                                :disabled="
                                                    scope.row.account != null
                                                        ? false
                                                        : true
                                                "
                                                divided
                                                @click="
                                                    scope.row.account != null
                                                        ? impersonateAccount(
                                                              scope.row
                                                          )
                                                        : false
                                                "
                                            >
                                                Impersonate
                                            </el-dropdown-item>
                                            <el-dropdown-item
                                                divided
                                                @click="viewForm(scope.row)"
                                                >View</el-dropdown-item
                                            >
                                            <el-dropdown-item
                                                @click="editForm(scope.row)"
                                                >Edit</el-dropdown-item
                                            >
                                            <el-dropdown-item
                                                @click="deleteForm(scope.row)"
                                                >Delete</el-dropdown-item
                                            >
                                        </el-dropdown-menu>
                                    </template>
                                </el-dropdown>
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
import {
    markRaw,
    onMounted,
    reactive,
    watch,
    ref,
    inject,
} from "@vue/runtime-core";
import { useForm } from "@inertiajs/inertia-vue3";
//Component imports
import AddEditForm from "./Components/AddEditForm.vue";
import AddByExcelForm from "./Components/AddByExcelForm.vue";
import ViewForm from "./Components/ViewForm.vue";
import StatusBadge from "@/Components/StatusBadge.vue";
import moment from "moment";
import {
    Plus,
    Delete,
    Refresh,
    Search,
    Switch,
    DocumentAdd,
    MoreFilled,
} from "@element-plus/icons-vue";
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
    !isMobile
        ? ["fullName", "email", "address", "account_status", "joined_date"]
        : ["fullName", "account_status"]
);
const tableColumnNames = {
    fullName: "Full Name",
    email: "Email",
    address: "Address",
    phone: "Phone",
    joined_date: "Joined Date",
    account_status: "Account Status",
};
//export table column refrence
const exportTableOption = reactive({
    header: [
        "First Name",
        "Middle Name",
        "Last Name",
        "Email",
        "Address",
        "Phone",
        "Joined Date",
    ],
    headerValue: [
        "first_name",
        "middle_name",
        "last_name",
        "email",
        "address",
        "phone",
        "joined_date",
    ],
    fileName: "employeeList",
});
const formInputNames = {
    first_name: "",
    middle_name: "",
    last_name: "",
    phone: "",
    gender: "",
    email: "",
    address: "",
    joined_date: "",
};
const addForm = () => refAddEditForm.showForm("Add");
const addExcelForm = () => refAddByExcelForm.showForm();
const editForm = (data) => refAddEditForm.showForm("Edit", data);
const viewForm = (data) => refViewForm.showForm(data);
const filterStatus = (value, row) => {
    if (value == "active") {
        return row.account != null;
    } else {
        return row.account == null;
    }
};
const deleteForm = (data) => {
    ElMessageBox.confirm("It will permanently delete. Continue?", "Warning", {
        type: "error",
        icon: markRaw(Delete),
        callback: (action) => {
            if (action == "confirm") {
                const formData = useForm();
                formData.delete(route("employee.destroy", data.id), {
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

const dateFormatter = (row, column) => moment(row.date).format("MMM Do, YYYY");
//table pagination / search related
const currentPage = $ref(1);
const pageSize = $ref(100);
const totalSize = $ref(0);
const refTable = ref(null);
const searchText = $ref("");
const filterDataList = $ref();
const indexMethod = (index) => (currentPage - 1) * pageSize + index + 1;
const isViewableColumn = (columnName) => viewableColumn.includes(columnName);
const dataList = $ref(iPropsValue("employeeList"));
const roleList = $ref(iPropsValue("roleList"));
watch(
    () => iPropsValue("employeeList"),
    () => {
        dataList = iPropsValue("employeeList");
        changePage(currentPage);
    }
);
watch(
    () => isMobile,
    () => {
        viewableColumn = !isMobile
            ? ["fullName", "email", "address", "account_status", "joined_date"]
            : ["fullName", "account_status"];
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
    exportLoading = true;
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
const createAccount = (rowData) => {
    ElMessageBox.confirm(
        "Create New User Account for <b class='capitalize'>" +
            rowData.fullName +
            "</b>. Continue?",
        "Info",
        {
            dangerouslyUseHTMLString: true,
            type: "warning",
            icon: markRaw(Plus),
            callback: (action) => {
                if (action == "confirm") {
                    const formData = useForm({
                        employeeId: rowData.id,
                    });
                    formData.post(route("employee.createAccount"), {
                        preserveScroll: true,
                        onSuccess: () => {
                            formData.reset();
                        },
                    });
                }
            },
        }
    );
    return;
};
const resetPassword = (rowData) => {
    ElMessageBox.confirm(
        "Reset User Account Password of <b class='capitalize'>" +
            rowData.fullName +
            "</b>. Continue?",
        "Warning",
        {
            dangerouslyUseHTMLString: true,
            type: "error",
            icon: markRaw(Refresh),
            callback: (action) => {
                if (action == "confirm") {
                    const formData = useForm({
                        employeeId: rowData.id,
                    });
                    formData.post(route("employee.resetPassword"), {
                        preserveScroll: true,
                        onSuccess: () => {
                            formData.reset();
                        },
                    });
                }
            },
        }
    );
    return;
};
const impersonateAccount = (rowData) => {
    ElMessageBox.confirm(
        "Impersonate <b class='capitalize'>" +
            rowData.fullName +
            "</b> User Account. Continue?",
        "Info",
        {
            dangerouslyUseHTMLString: true,
            type: "warning",
            icon: markRaw(Switch),
            callback: (action) => {
                if (action == "confirm") {
                    const formData = useForm({
                        userId: rowData.account.id,
                    });
                    formData.post(route("user.impersonate"), {
                        preserveScroll: true,
                        onSuccess: () => {
                            formData.reset();
                        },
                    });
                }
            },
        }
    );
    return;
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
