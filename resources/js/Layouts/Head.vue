<template>
    <el-header class="relative bg-white">
        <el-row class="row-bg h-full items-center" justify="space-between">
            <el-col :span="8"
                ><div class="grid-content text-lg text-lightBlue-500">
                    {{ breadcrumb }}
                </div>
            </el-col>
            <el-col :span="10">
                <el-row class="row-bg flex items-center" justify="end">
                    <!-- <el-col :span="10" class="text-white">search</el-col> -->

                    <el-col :span="14" class="content-center text-right">
                        <el-dropdown class="" trigger="click">
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
                                <el-dropdown-menu>
                                    <el-dropdown-item>
                                        <fa icon="user" class="pr-1" />
                                        {{
                                            iPropsValue(
                                                "auth",
                                                "user.account.role"
                                            )
                                        }}</el-dropdown-item
                                    >
                                    <el-dropdown-item divided
                                        ><fa
                                            icon="unlock-keyhole"
                                            class="pr-1"
                                        />change password</el-dropdown-item
                                    >
                                    <el-dropdown-item divided>
                                        <fa
                                            icon="right-from-bracket"
                                            class="pr-1"
                                        />
                                        <nav-link
                                            class="is-link text-left"
                                            method="post"
                                            as="button"
                                            :href="appRoute('logout')"
                                        >
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
import { inject } from "vue";
let { iPropsValue } = useInertiaPropsUtility();

const breadcrumb = iPropsValue("breadcrumb");
</script>
<script>
export default {
    data() {
        return {};
    },
};
</script>
