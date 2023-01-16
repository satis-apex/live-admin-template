<template>
    <el-affix :offset="0.1" style="width: 100%">
        <el-header class="relative custom-header">
            <el-row class="row-bg h-full items-center" justify="space-between">
                <el-col
                    :xs="6"
                    :sm="6"
                    :md="12"
                    class="!flex h-full items-center"
                >
                    <div
                        class="menu-toggler block h-full"
                        @click="
                            isScreenMd
                                ? emit('showMobileMenu')
                                : emit('toggleDesktopMenu')
                        "
                    >
                        <!-- hero icon -->
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="2.5"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"
                            />
                        </svg>
                    </div>
                    <div
                        class="flex h-full pl-3 hidden-sm-and-down breadcrumb items-center sm:text-lg text-sm text-lightBlue-500"
                    >
                        {{ breadcrumb }}
                    </div>
                </el-col>
                <el-col :xs="16" :sm="16" :md="12" class="h-full">
                    <el-row
                        class="row-bg flex items-center h-full"
                        justify="end"
                    >
                        <NotificationBell />
                        <DarkToggler />
                        <el-dropdown trigger="click" ref="headDropdown">
                            <span class="el-dropdown-link flex">
                                <el-avatar
                                    :src="iPropsValue('auth', 'user.avatar')"
                                >
                                    <img
                                        src="https://cube.elemecdn.com/e/fd/0fc7d20532fdaf769a25683617711png.png"
                                    />
                                </el-avatar>
                                <div class="flex items-center">
                                    <span class="hidden-sm-and-down pl-2">
                                        {{
                                            iPropsValue(
                                                "auth",
                                                "user.firstName"
                                            )
                                        }}
                                    </span>
                                    <el-icon class="el-icon--right">
                                        <arrow-down />
                                    </el-icon>
                                </div>
                            </span>
                            <template #dropdown>
                                <el-dropdown-menu class="user-info-dropdown">
                                    <el-dropdown-item>
                                        <fa icon="shield" class="pr-1" />{{
                                            iPropsValue(
                                                "auth",
                                                "user.account.role"
                                            )
                                        }}
                                    </el-dropdown-item>
                                    <el-dropdown-item divided>
                                        <nav-link
                                            @click="headDropdown.handleClose()"
                                            :href="
                                                appRoute('userProfile.index')
                                            "
                                        >
                                            <fa icon="user" class="pr-1" />
                                            User Profile
                                        </nav-link>
                                    </el-dropdown-item>
                                    <el-dropdown-item
                                        @click="formVisible = true"
                                        ><fa
                                            icon="unlock-keyhole"
                                            class="pr-1"
                                        />Change Password</el-dropdown-item
                                    >

                                    <el-dropdown-item divided>
                                        <nav-link
                                            class="is-link text-left"
                                            method="post"
                                            as="button"
                                            :href="appRoute('logout')"
                                            ><fa
                                                icon="right-from-bracket"
                                                class="pr-1"
                                            />
                                            Logout
                                        </nav-link>
                                    </el-dropdown-item>
                                </el-dropdown-menu>
                            </template>
                        </el-dropdown>
                    </el-row>
                </el-col>
            </el-row>
        </el-header>
    </el-affix>
    <ChangeUserPassword
        @closeForm="closeForm()"
        :parentFormVisible="formVisible"
    />
</template>
<script setup>
import {
    ArrowDown,
    CirclePlusFilled,
    CirclePlus,
    Check,
    CircleCheck,
} from "@element-plus/icons-vue";
import DarkToggler from "@/Components/DarkModeSwitch.vue";
import NotificationBell from "@/Layouts/NotificationBell.vue";
import { useInertiaPropsUtility } from "@/Composables/inertiaPropsUtility";
import { ref, inject, onMounted, watch } from "@vue/runtime-core";
import ChangeUserPassword from "@/Layouts/ChangeUserPassword.vue";
import { useAppUtility } from "@/Composables/appUtiility";
// import { set } from "@vueuse/shared";
const { isScreenMd } = useAppUtility();
const { iPropsValue } = useInertiaPropsUtility();
const formVisible = ref(false);
const notifications = ref(false);
const headDropdown = ref();
const breadcrumb = ref(iPropsValue("breadcrumb"));
const closeForm = function () {
    formVisible.value = false;
};

watch(
    () => iPropsValue("breadcrumb"),
    () => {
        breadcrumb.value = iPropsValue("breadcrumb");
    }
);
const emit = defineEmits(["showMobileMenu", "toggleDesktopMenu"]);
</script>
<style scoped>
.custom-header {
    background-image: radial-gradient(transparent 1px, white 1px);
    background-size: 4.6px 4.6px;
    backdrop-filter: saturate(50%) blur(4px);
    -webkit-backdrop-filter: saturate(50%) blur(4px);
}
</style>
<style>
.user-info-dropdown .el-dropdown-menu__item--divided {
    margin: 0;
}
.user-info-dropdown.el-dropdown-menu {
    padding: 0;
}
.user-info-dropdown .el-dropdown-menu__item {
    height: 34px;
}
div.menu-toggler {
    width: 45px;
    height: 45px;
    display: flex;
    justify-content: center;
}
div.menu-toggler svg {
    width: 32px;
}
.menu-toggler:hover {
    background: #e2e2e2;
    border-radius: 50%;
    cursor: pointer;
}
.breadcrumb {
    width: calc(100% - 45px);
}
.el-header {
    --el-header-padding: 0 10px !important;
}
</style>
