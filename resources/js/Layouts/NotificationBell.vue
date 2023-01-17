<template>
    <div
        @click="notificationDrawer = true"
        class="inline-block text-xl hover:bg-gray-200 dark:hover:bg-zinc-800 w-11 cursor-pointer h-11 flex justify-center rounded-full items-center mr-2 relative text-primary-dark"
    >
        <fa icon="bell" />
        <div
            v-if="unreadNotificationCount > 0"
            class="absolute top-2 right-2 status-badge w-2 h-2 block rounded-full bg-complementary"
        ></div>
    </div>

    <el-drawer
        v-model="notificationDrawer"
        modal-class="notification-panel"
        direction="rtl"
        :append-to-body="true"
        :show-close="false"
    >
        <template #header="{ close, titleId, titleClass }">
            <div :id="titleId" :class="titleClass" class="!text-xl font-bold">
                <div class="relative">
                    Notifications
                    <span
                        v-if="unreadNotificationCount > 0"
                        style="border: 1px solid"
                        class="absolute top-0 text-sm ml-1 bg-primary-dark shadow-md px-2 rounded-full"
                        >{{ unreadNotificationCount }}</span
                    >
                </div>
            </div>
            <el-dropdown trigger="click">
                <div
                    class="w-10 h-10 flex hover:bg-opacity-30 hover:bg-gray-300 rounded-full justify-center items-center text-slate-50 p-2"
                >
                    <MoreFilled />
                </div>
                <template #dropdown>
                    <el-dropdown-menu>
                        <el-dropdown-item
                            :icon="Finished"
                            :disabled="
                                unreadNotificationCount > 0 ? false : true
                            "
                            @click="markAllRead"
                            >Mark all as read</el-dropdown-item
                        >
                        <el-dropdown-item :icon="Setting"
                            >Notification settings</el-dropdown-item
                        >
                    </el-dropdown-menu>
                </template>
            </el-dropdown>
        </template>
        <div class="notification-list">
            <template :key="key" v-for="(notification, key) in notifications">
                <div
                    class="flex notification cursor-pointer p-3 rounded-sm overflow-hidden hover:bg-gray-200 dark:hover:bg-zinc-800 relative"
                >
                    <div class="pl-4 w-full relative">
                        <el-tooltip
                            v-if="notification.read_at === null"
                            class="box-item"
                            effect="dark"
                            content="Mark as read"
                            placement="bottom"
                        >
                            <div
                                @click="markRead(notification)"
                                class="justify-center h-2 w-2 absolute top-2 left-0 rounded-full bg-primary"
                            ></div
                        ></el-tooltip>
                        <div
                            @click="viewAnnouncement(notification)"
                            class="flex text-sm justify-between capitalize"
                        >
                            <span class="font-semibold">{{
                                notification.data.title
                            }}</span>
                            <div
                                class="text-xs flex justify-center items-center"
                            >
                                {{
                                    moment(notification.created_at).format(
                                        "MMM Do, YYYY"
                                    )
                                }}
                            </div>
                        </div>
                        <div
                            @click="viewAnnouncement(notification)"
                            class="line-clamp-3 md:line-clamp-2 text-sm"
                        >
                            {{ notification.data.message }}
                        </div>
                    </div>
                </div>
                <el-divider class="!my-0" />
            </template>
        </div>
    </el-drawer>
</template>

<script setup>
import { ref, watch } from "@vue/runtime-core";
import { MoreFilled, Finished, Setting } from "@element-plus/icons-vue";
import moment from "moment";
import { useInertiaPropsUtility } from "@/Composables/inertiaPropsUtility";
import { useForm } from "@inertiajs/inertia-vue3";
import { Inertia } from "@inertiajs/inertia";
const { iPropsValue } = useInertiaPropsUtility();
const notificationDrawer = ref(false);
const markRead = async (announcement) => {
    const formData = useForm({
        id: announcement.id,
    });
    await formData.patch(route("markNotificationRead"), {
        preserveScroll: true,
    });
};
const notifications = ref(iPropsValue("app_notification", "all_notification"));
const unreadNotificationCount = ref(
    iPropsValue("app_notification", "unread_count")
);
watch(
    () => iPropsValue("app_notification"),
    () => {
        unreadNotificationCount.value = iPropsValue(
            "app_notification",
            "unread_count"
        );
        notifications.value = iPropsValue(
            "app_notification",
            "all_notification"
        );
    }
);

const viewAnnouncement = async (announcement) => {
    if (
        !announcement.data.hasOwnProperty("link") ||
        announcement.data?.link == ""
    )
        return false;
    if (announcement.read_at == null) {
        await markRead(announcement);
    }
    await Inertia.get(
        announcement.data.link,
        {},
        {
            onStart: () => {
                notificationDrawer.value = false;
            },
        }
    );
};
const markAllRead = () => {
    const formData = useForm({});
    formData.patch(route("markAllNotificationRead"), {
        preserveScroll: true,
    });
};

Echo.private("announcement." + iPropsValue("auth", "user.id")).notification(
    (announcement) => {
        const notification = ref({
            id: announcement.id,
            data: announcement.notification.data,
            created_at: moment(),
            read_at: null,
        });
        unreadNotificationCount.value += 1;
        notifications.value = [notification.value, ...notifications.value];
    }
);
</script>

<style lang="scss">
.notification-panel {
    z-index: 100 !important;
    .el-drawer {
        min-width: 250px;
    }
    .el-drawer__header {
        margin-bottom: 0;
        background: var(--primary-color);
        color: white;
        height: 60px;
        padding: 0 15px 0 15px;
    }
    .el-drawer__body {
        padding: unset;
    }
}
</style>
