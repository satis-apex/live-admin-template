<template>
    <el-dialog
        v-model="FormVisible"
        :before-close="closeForm"
        :title="FormType + ' Announcement'"
        width="400px"
    >
        <template #default>
            <el-form
                @submit.prevent
                :model="formData"
                ref="refForm"
                label-position="top"
                :scroll-to-error="true"
                :rules="rules"
                status-icon
            >
                <el-form-item prop="title">
                    <el-input
                        v-model="formData.title"
                        :prefix-icon="CollectionTag"
                        autocomplete="off"
                        placeholder="Title"
                    >
                        <template #append>
                            <el-tooltip
                                class="box-item"
                                effect="dark"
                                :content="`Click for ${
                                    !formData.private ? 'Private' : 'Public'
                                } Announcement`"
                            >
                                <el-button
                                    @click="
                                        formData.private = !formData.private
                                    "
                                    type="success"
                                >
                                    <fa
                                        :icon="
                                            formData.private
                                                ? 'lock'
                                                : 'lock-open'
                                        "
                                    />
                                </el-button>
                            </el-tooltip>
                        </template>
                    </el-input>
                </el-form-item>
                <el-form-item prop="detail">
                    <el-input
                        v-model="formData.detail"
                        autocomplete="off"
                        placeholder="Announcement detail"
                        :prefix-icon="Memo"
                    />
                </el-form-item>
                <el-row :gutter="10">
                    <el-col :xs="24" :sm="12"
                        ><el-form-item prop="start">
                            <el-date-picker
                                :prefix-icon="Calendar"
                                :type="formData.allDay ? 'date' : 'datetime'"
                                v-model="formData.start"
                                autocomplete="off"
                                :clearable="false"
                            /> </el-form-item
                    ></el-col>
                    <el-col :xs="24" :sm="12"
                        ><el-form-item prop="end">
                            <el-date-picker
                                :prefix-icon="Calendar"
                                :type="formData.allDay ? 'date' : 'datetime'"
                                v-model="formData.end"
                                autocomplete="off"
                                :clearable="false"
                            /> </el-form-item
                    ></el-col>
                </el-row>
                <el-row :gutter="10">
                    <el-col :xs="12" :sm="6">
                        <el-form-item prop="allDay">
                            <el-checkbox
                                v-model="formData.allDay"
                                label="All Day"
                            />
                        </el-form-item>
                    </el-col>
                    <el-col :xs="12" :sm="6">
                        <el-form-item prop="holiday">
                            <el-checkbox
                                v-model="formData.holiday"
                                label="Holiday"
                            />
                        </el-form-item>
                    </el-col>
                    <el-col :xs="12" :sm="6">
                        <el-form-item prop="notify">
                            <el-checkbox
                                v-model="formData.notify"
                                label="Send Notification"
                            />
                        </el-form-item>
                    </el-col>
                </el-row>

                <el-form-item prop="viewer" label="Viewer :">
                    <el-select
                        v-model="formData.viewer"
                        multiple
                        style="width: 100%"
                        clearable
                    >
                        <el-option
                            key="key"
                            label="Su-Admin"
                            value="Su-Admin"
                            disabled
                        />
                        <el-option
                            v-for="(role, key) in roleList"
                            :key="key"
                            :label="role"
                            :value="role"
                        />
                    </el-select>
                </el-form-item>
            </el-form>
        </template>
        <template #footer>
            <span class="dialog-footer">
                <el-button @click="closeForm()">Cancel</el-button>
                <el-button
                    type="primary"
                    :loading="formData.processing"
                    @click="submitForm(refForm)"
                >
                    Confirm
                </el-button>
            </span>
        </template>
    </el-dialog>

    <FullCalendar
        ref="refFullCalendar"
        :key="calendarComponentKey"
        :options="calendarOptions"
    >
        <template #eventContent="arg">
            <el-popover
                placement="bottom"
                width="350"
                trigger="click"
                :offset="6"
                popper-style="padding:0"
            >
                <template #default>
                    <div class="calendar-event-content p-3">
                        <p class="text-xl font-bold capitalize">
                            {{ arg.event.title }}
                            <el-tooltip
                                class="box-item"
                                effect="dark"
                                :content="`${
                                    arg.event.extendedProps.private
                                        ? 'Private'
                                        : 'Public'
                                } Announcement`"
                            >
                                <fa
                                    class="text-sm"
                                    :icon="
                                        arg.event.extendedProps.private
                                            ? 'lock'
                                            : 'lock-open'
                                    "
                                />
                            </el-tooltip>
                        </p>
                        <p class="text-xs" v-if="arg.timeText.length > 0">
                            {{
                                moment(arg.event.start).format(
                                    "YYYY-MM-DD  h:mm a"
                                )
                            }}
                            -
                            {{ moment(arg.event.end).format("h:mm a") }}
                        </p>
                        <p class="text-xs" v-else>
                            {{
                                moment
                                    .duration(
                                        moment(
                                            arg.event.end,
                                            "YYYY-MM-DD"
                                        ).diff(
                                            moment(
                                                arg.event.start,
                                                "YYYY-MM-DD"
                                            )
                                        )
                                    )
                                    .asDays() > 1
                                    ? moment(arg.event.start).format(
                                          "YYYY-MM-DD"
                                      ) +
                                      " - " +
                                      moment(arg.event.end).format("YYYY-MM-DD")
                                    : moment(arg.event.start).format(
                                          "YYYY-MM-DD"
                                      )
                            }}
                        </p>
                        <p class="pt-2 mt-1 flex border-t">
                            <el-tooltip
                                class="box-item"
                                effect="dark"
                                content="Detail"
                            >
                                <fa icon="info-circle" class="mr-2" />
                            </el-tooltip>
                            <span class="first-letter:capitalize text-justify"
                                >{{ arg.event.extendedProps.detail }}
                            </span>
                        </p>
                        <p class="mt-2">
                            <el-tooltip
                                class="box-item"
                                effect="dark"
                                content="Viewer"
                                ><fa icon="users" />
                            </el-tooltip>
                            <span>
                                <template
                                    :key="key"
                                    v-for="(role, key) in viewableRole(
                                        arg.event
                                    )"
                                >
                                    <el-tag class="ml-2">{{ role }}</el-tag>
                                </template></span
                            >
                        </p>
                    </div>
                    <div class="calendar-event-action">
                        <el-row class="border-t">
                            <el-col
                                v-if="iPropsValue('userCan', 'edit')"
                                :span="12"
                                @click="
                                    poperEdit($event),
                                        handleEventEdit(arg.event)
                                "
                                class="text-center cursor-pointer hover:bg-gray-200 dark:hover:bg-zinc-800 py-2 border-r"
                            >
                                <el-icon><Edit /></el-icon> Edit
                            </el-col>
                            <el-col
                                v-if="iPropsValue('userCan', 'delete')"
                                :span="12"
                                @click="handleEventCancel(arg.event)"
                                class="text-center cursor-pointer hover:bg-gray-200 dark:hover:bg-zinc-800 py-2"
                            >
                                <el-icon><Delete /></el-icon>Delete</el-col
                            >
                        </el-row>
                    </div>
                </template>
                <template #reference>
                    <div v-if="arg.timeText.length > 0">
                        <div
                            class="flex items-center"
                            :class="
                                arg.event.extendedProps.holiday
                                    ? 'isholiday'
                                    : ''
                            "
                        >
                            <div class="fc-daygrid-event-dot"></div>
                            <div class="fc-event-time text-xs">
                                <fa
                                    v-if="arg.event.extendedProps.private"
                                    icon="lock"
                                />
                                {{ arg.timeText }}
                            </div>
                            <div class="fc-event-title truncate text-xs">
                                {{ arg.event.title }}
                            </div>
                        </div>
                    </div>
                    <div v-else>
                        <div
                            class="fc-event-title fc-sticky text-xs"
                            :class="
                                arg.event.extendedProps.holiday
                                    ? 'isholiday'
                                    : ''
                            "
                        >
                            <fa
                                v-if="arg.event.extendedProps.private"
                                icon="lock"
                            />
                            {{ arg.event.title }}
                        </div>
                    </div>
                </template>
            </el-popover>
        </template>
    </FullCalendar>
</template>
<script setup>
//full calender import
import FullCalendar from "@fullcalendar/vue3";
import moment from "moment";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";
import interactionPlugin from "@fullcalendar/interaction";
import listPlugin from "@fullcalendar/list";
import {
    Edit,
    Delete,
    Search,
    Calendar,
    CollectionTag,
    Memo,
    Lock,
    Unlock,
} from "@element-plus/icons-vue";
import { reactive, ref, unref, markRaw } from "@vue/reactivity";
import { useForm } from "@inertiajs/inertia-vue3";

//composable import
import { useInertiaPropsUtility } from "@/Composables/inertiaPropsUtility";
import { useAppUtility } from "@/Composables/appUtiility";
import { computed, watch } from "@vue/runtime-core";
//variable declaration
const { iPropsValue } = useInertiaPropsUtility();
const { isScreenMd } = useAppUtility();
const formLabelWidth = "90px";
const refFullCalendar = $ref(null);
const calendarComponentKey = $ref(0);
const refForm = $ref(null);
const FormVisible = ref(false);
const roleList = $ref(iPropsValue("roleList"));
const FormType = ref("Add");
const viewableRole = (event) => {
    const roleList = event.extendedProps.viewer
        .split(",")
        .filter(function (el) {
            return el != null && el != "";
        });
    return roleList.length > 0 ? roleList : null;
};
let todayStr = new Date().toISOString().replace(/T.*$/, ""); // YYYY-MM-DD of today

const announcementList = $ref(
    iPropsValue("announcementList")
        ? JSON.parse(iPropsValue("announcementList"))
        : ""
);
watch(
    () => iPropsValue("announcementList"),
    () => {
        announcementList = iPropsValue("announcementList")
            ? JSON.parse(iPropsValue("announcementList"))
            : "";
        calendarOptions.events = announcementList;
        calendarComponentKey++;
    }
);

const currentEvents = $ref();

const formData = useForm({
    _method: "POST",
    title: "",
    detail: "",
    id: "",
    allDay: false,
    holiday: false,
    notify: false,
    viewer: ["Su-Admin"],
    start: "",
    end: "",
    private: false,
});
const rules = reactive({
    title: [
        {
            required: true,
            message: "Please Input Title",
            trigger: "blur",
        },
    ],
});

const resetForm = (formEl) => {
    if (!formEl) return;
    formEl.resetFields();
};
const closeForm = () => {
    FormVisible.value = false;
    resetForm(refForm);
    formData.reset();
};
const checkCustom = (event) => {
    return false;
};
const clickedDateInfo = $ref();
let eventGuid = 0;
const createEventId = () => {
    return String(eventGuid++);
};
const handleDateSelect = (selectInfo) => {
    if (!iPropsValue("userCan", "create")) return;
    FormType.value = "Add";
    clickedDateInfo = selectInfo;
    FormVisible.value = true;
    formData.start = selectInfo.startStr;
    formData.end = selectInfo.endStr;
    formData.allDay = selectInfo.allDay;

    let calendarApi = selectInfo.view.calendar;
    calendarApi.unselect(); // clear date selection
};

const submitForm = async (formEl) => {
    if (!formEl) return;
    await formEl.validate((valid, fields) => {
        if (valid) {
            if (FormType.value == "Add") {
                formData._method = "POST";
                create();
            } else {
                formData._method = "PATCH";
                update();
            }
        } else {
            ElNotification({
                title: "Warning",
                message:
                    "Form validation Error, please check before submitting!",
                type: "warning",
            });
            console.log("error submit!", fields);
        }
    });
};

const create = () => {
    ElMessageBox.confirm("You are trying to Add. Continue?", "Warning", {
        type: "warning",
        icon: markRaw(Edit),
        callback: (action) => {
            if (action == "confirm") {
                clearServerValidationError();
                formData.post(route("announcement.store"), {
                    preserveScroll: true,
                    onSuccess: () => {
                        closeForm();
                    },
                    onError: (errors) => {
                        loadServerValidationError();
                    },
                });
            }
        },
    });
};
const update = () => {
    ElMessageBox.confirm("You are trying to edit. Continue?", "Warning", {
        type: "warning",
        icon: markRaw(Edit),
        callback: (action) => {
            if (action == "confirm") {
                clearServerValidationError();
                try {
                    formData.post(route("announcement.update", formData.id), {
                        preserveScroll: true,
                        onSuccess: () => {
                            closeForm();
                        },
                        onError: (errors) => {
                            loadServerValidationError();
                        },
                    });
                } catch (error) {
                    ElNotification({
                        title: "Error",
                        message: "Request Form Error.",
                        type: "error",
                    });
                    console.log(error);
                }
            }
        },
    });
};
const formErrors = reactive({
    title: null,
});
const loadServerValidationError = () => {
    Object.assign(formErrors, formData.errors);
};
const clearServerValidationError = () => {
    for (const key in formErrors) {
        formErrors[key] = null;
    }
};
const populateFormData = (info) => {
    formData.title = info.title;
    formData.detail = info.extendedProps.detail;
    formData.holiday = info.extendedProps.holiday == 1 ? true : false;
    formData.private = info.extendedProps.private == 1 ? true : false;
    formData.notify = info.extendedProps.notify == 1 ? true : false;
    formData.viewer = viewableRole(info);
    formData.start = info.startStr;
    formData.end = info.endStr;
    formData.allDay = info.allDay;
    formData.id = info.id;
};
const handleEventEdit = (clickInfo) => {
    FormVisible.value = true;
    FormType.value = "Edit";
    populateFormData(clickInfo);
};

const poperEdit = (event) => {
    event.target
        .closest(".el-popper.el-popover")
        .style.setProperty("display", "none");
};
const handleEventCancel = (clickInfo) => {
    ElMessageBox.confirm(
        "It will cancel and permanently delete event. Continue?",
        "Warning",
        {
            type: "error",
            icon: markRaw(Delete),
            callback: (action) => {
                if (action == "confirm") {
                    const formData = useForm();
                    formData.delete(
                        route("announcement.destroy", clickInfo.id),
                        {
                            preserveScroll: true,
                            onSuccess: () => {
                                formData.reset();
                                clickInfo.remove();
                            },
                        }
                    );
                }
            },
        }
    );
    return;
};

const handleEventChange = (data) => {
    if (!iPropsValue("userCan", "edit")) {
        data.revert();
        return;
    }
    ElMessageBox.confirm("You are trying to edit. Continue?", "Warning", {
        type: "warning",
        icon: markRaw(Edit),
        callback: (action) => {
            if (action == "confirm") {
                clearServerValidationError();
                populateFormData(data.event);
                try {
                    formData.patch(route("announcement.update", formData.id), {
                        preserveScroll: true,
                        onSuccess: () => {
                            closeForm();
                        },
                        onError: (errors) => {
                            loadServerValidationError();
                        },
                    });
                } catch (error) {
                    ElNotification({
                        title: "Error",
                        message: "Request Form Error.",
                        type: "error",
                    });
                    console.log(error);
                }
            } else data.revert();
        },
    });
};

const calendarOptions = {
    plugins: [dayGridPlugin, listPlugin, interactionPlugin, timeGridPlugin],
    headerToolbar: {
        left: "listYear,dayGridMonth,timeGridWeek",
        center: "title",
        right: "today prev,next",
    },
    views: {
        listYear: { buttonText: "Yearly" },
        dayGridMonth: { buttonText: "Monthly" },
        timeGridWeek: { buttonText: "Weekly" },
    },
    initialView: isScreenMd.value ? "listYear" : "dayGridMonth",
    events: announcementList,
    editable: true,
    selectable: true,
    selectMirror: true,
    dayMaxEvents: true,
    select: handleDateSelect,
    eventChange: handleEventChange,
    eventDurationEditable: false,
    moreLinkText: "more",
};
</script>

<style lang="scss">
.fc-event-main {
    span {
        display: block;
    }
}
.el-tooltip__trigger:has(.truncate) {
    width: 100%;
}
@media (width <= 768px) {
    .fc .fc-toolbar {
        flex-direction: column;
    }
}
.fc-daygrid-day-frame:has(.isholiday) {
    background: var(--el-color-danger-light-7);
}
.fc-list-event-title {
    .el-tooltip__trigger {
        display: inline-block;
        &:hover {
            cursor: pointer;
        }
    }
    .isholiday:after {
        content: "holiday";
        background: var(--el-color-danger);
        border-radius: 6px;
        padding: 2px 6px;
        margin-left: 3px;
        color: aliceblue;
    }
    .fc-event-title {
        display: inline;
    }
}

.fc-theme-standard .fc-list-day-cushion {
}
</style>
