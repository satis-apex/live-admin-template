<template>
    <el-header class="relative custom-header">
        <el-row class="row-bg h-full items-center" justify="space-between">
            <el-col :span="12">
                <div
                    v-show="mobileMenu"
                    class="menu-toggler block w-6 mr-2 float-left"
                    @click="emit('showMenu')"
                >
                    <!-- hero icon -->
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="2.5"
                        stroke="currentColor"
                        class="w-6 h-6"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"
                        />
                    </svg>
                </div>

                <div class="grid-content text-lg text-lightBlue-500">
                    {{ breadcrumb }}
                </div>
            </el-col>
            <el-col :span="10">
                <el-row class="row-bg flex items-center" justify="end">
                    <!-- <el-col :span="10" class="text-white">search</el-col> -->

                    <el-col :span="14" class="content-center text-right">
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
                        </el-dropdown></el-col
                    >
                </el-row>
            </el-col>
        </el-row>
    </el-header>
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
import { useInertiaPropsUtility } from "@/Composables/inertiaPropsUtility";
import { inject, onMounted, watch } from "vue";
import ChangeUserPassword from "@/Layouts/ChangeUserPassword.vue";
import { useAppUtility } from "@/Composables/appUtiility";
const { mediaCheck } = useAppUtility();
const { iPropsValue } = useInertiaPropsUtility();
const formVisible = $ref(false);
const headDropdown = $ref();
const breadcrumb = $ref(iPropsValue("breadcrumb"));
const closeForm = function () {
    formVisible = false;
};
const mobileMenu = $ref(mediaCheck("md"));
watch(
    () => iPropsValue("breadcrumb"),
    () => {
        breadcrumb = iPropsValue("breadcrumb");
    }
);
const emit = defineEmits(["showMenu"]);
onMounted(() => {
    window.addEventListener("resize", () => {
        mobileMenu = mediaCheck("md");
    });
});
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
</style>
