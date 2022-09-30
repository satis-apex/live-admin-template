<template>
    <el-header class="relative bg-blue-300">
        <el-row class="row-bg h-full items-center" justify="space-between">
            <el-col :span="8"
                ><div class="grid-content ep-bg-purple" />
                Dashboard /
            </el-col>
            <el-col :span="10">
                <el-row class="row-bg flex items-center" justify="end">
                    <el-col :span="10" class="">search</el-col>

                    <el-col :span="14" class="content-center text-right">
                        <el-dropdown class="" trigger="click">
                            <span class="el-dropdown-link flex">
                                <el-avatar> user </el-avatar>
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
                                    <el-dropdown-item divided :icon="Plus"
                                        >super admin</el-dropdown-item
                                    >
                                    <el-dropdown-item :icon="CirclePlusFilled">
                                        Action 2
                                    </el-dropdown-item>
                                    <el-dropdown-item :icon="CirclePlus"
                                        >Action 3</el-dropdown-item
                                    >
                                    <el-dropdown-item :icon="Check"
                                        >change password</el-dropdown-item
                                    >
                                    <el-dropdown-item
                                        divided
                                        :icon="CircleCheck"
                                    >
                                        <nav-link
                                            class="is-link text-left"
                                            title="Dashboard"
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
    Plus,
    CirclePlusFilled,
    CirclePlus,
    Check,
    CircleCheck,
} from "@element-plus/icons-vue";
import { useInertiaPropsUtility } from "@/Composables/inertiaPropsUtility";

let { iPropsValue } = useInertiaPropsUtility();
</script>
<script>
export default {
    data() {
        return {};
    },
};
</script>
