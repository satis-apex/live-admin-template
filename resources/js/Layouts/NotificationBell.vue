<template>
    <div
        class="inline-block text-xl hover:bg-gray-200 dark:hover:bg-zinc-800 w-11 cursor-pointer h-11 flex justify-center rounded-full items-center mr-2 relative text-primary-dark"
    >
        <fa icon="bell" @click="notificationDrawer = true" />
        <div
            class="absolute top-2 right-2 status-badge w-2 h-2 block rounded-full bg-complementary"
        ></div>
    </div>

    <el-drawer
        v-model="notificationDrawer"
        custom-class="notification-panel"
        direction="rtl"
        :append-to-body="true"
        :show-close="false"
    >
        <template #header="{ close, titleId, titleClass }">
            <div :id="titleId" :class="titleClass" class="!text-xl font-bold">
                Notifications
            </div>
            <el-dropdown trigger="click">
                <div
                    class="w-10 h-10 flex hover:bg-opacity-30 hover:bg-gray-300 rounded-full justify-center items-center text-slate-50 p-2"
                >
                    <MoreFilled />
                </div>
                <template #dropdown>
                    <el-dropdown-menu>
                        <el-dropdown-item :icon="Finished" @click="markAllRead"
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
                            class="font-semibold flex text-lg justify-between capitalize"
                        >
                            {{ notification.name }}
                        </div>
                        <div class="absolute right-0 top-0 text-sm">
                            {{ notification.date }}
                        </div>
                        <div
                            @click="viewAnnouncement(notification)"
                            class="line-clamp-3 md:line-clamp-2 text-base"
                        >
                            {{ notification.detail }}
                        </div>
                    </div>
                </div>
                <el-divider class="!my-0" />
            </template>
        </div>
    </el-drawer>
</template>

<script setup>
import { ref } from "@vue/runtime-core";
import { MoreFilled, Finished, Setting } from "@element-plus/icons-vue";
const notificationDrawer = ref(false);
const markRead = (announcement) => {
    console.log("markRead");
};
const notifications = [
    {
        name: "test",
        detail: "t is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like	 ",
        date: "2022-12-6",
    },
    {
        name: "test",
        detail: "t is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like",
        date: "2022-12-6",
    },
    {
        name: "test",
        detail: "dexcription",
        date: "2022-12-6",
    },
    {
        name: "test",
        detail: "dexcription",
        date: "2022-12-6",
    },
];

const viewAnnouncement = (announcement) => {
    console.log("viewAnnouncement");
};
const markAllRead = () => {
    console.log("markAllRead");
};
</script>

<style lang="scss">
.has-notification:after {
    content: "";
    height: 10px;
    width: 10px;
    background: red;
    display: inline-block;
    position: absolute;
}
.notification-panel {
    min-width: 250px;
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
