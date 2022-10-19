<template>
    <el-header class="relative custom-header">
        <el-row class="row-bg h-full items-center" justify="space-between">
            <el-col :span="8">
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
                                    <span class="hidden-xs-only pl-2">
                                        {{
                                            iPropsValue(
                                                "auth",
                                                "user.first_name"
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
import { inject, watch } from "vue";
import ChangeUserPassword from "@/Layouts/ChangeUserPassword.vue";
let { iPropsValue } = useInertiaPropsUtility();
const formVisible = $ref(false);
const headDropdown = $ref();
const breadcrumb = $ref(iPropsValue("breadcrumb"));
const closeForm = function () {
    formVisible = false;
};
watch(
    () => iPropsValue("breadcrumb"),
    () => {
        breadcrumb = iPropsValue("breadcrumb");
    }
);
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
