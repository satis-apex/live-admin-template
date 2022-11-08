<template>
    <div>
        <el-drawer
            v-model="menuDrawer"
            :with-header="true"
            :show-close="true"
            direction="ltr"
            :size="250"
            custom-class="menu-drawer"
        >
            <template #header>
                <div class="logo">Brand Logo</div>
            </template>
            <template #default>
                <SimpleBar id="menubar-drawer">
                    <el-menu
                        :default-active="currentActive"
                        class="el-menu-vertical"
                    >
                        <template v-for="(menu, key) in menuList">
                            <template v-if="menu.link != '#'">
                                <el-menu-item
                                    class="text-lg"
                                    :key="key"
                                    :index="key.toString()"
                                    v-if="hasMenuAccess(menu)"
                                >
                                    <el-icon><fa :icon="menu.icon" /></el-icon>
                                    <template #title>
                                        <nav-link
                                            :title="menu.name"
                                            :href="
                                                menu.link == '#'
                                                    ? menu.link
                                                    : appRoute(menu.link)
                                            "
                                            :set="
                                                (currentActive =
                                                    appRoute().current(
                                                        menu.link
                                                    )
                                                        ? key.toString()
                                                        : currentActive)
                                            "
                                        >
                                            {{ menu.name }}
                                        </nav-link>
                                    </template>
                                </el-menu-item>
                            </template>
                            <template
                                v-else-if="
                                    menu.link == '#' &&
                                    menu.children != undefined &&
                                    menu.children.length != 0
                                "
                            >
                                <el-sub-menu
                                    class="text-lg"
                                    :key="key"
                                    :index="key.toString()"
                                >
                                    <template #title>
                                        <el-icon
                                            ><fa :icon="menu.icon" />
                                        </el-icon>
                                        <span :title="menu.name">{{
                                            menu.name
                                        }}</span>
                                    </template>
                                    <template
                                        v-for="(
                                            sub_menu, subKey
                                        ) in menu.children"
                                        :key="key + '-' + subKey"
                                    >
                                        <el-menu-item
                                            :index="key + '-' + subKey"
                                            v-if="hasMenuAccess(sub_menu)"
                                        >
                                            <template #title
                                                ><el-icon v-show="!isCollapse">
                                                    <span
                                                        class="bullet-dot"
                                                    ></span>
                                                </el-icon>
                                                <nav-link
                                                    v-if="sub_menu.link != '#'"
                                                    :title="sub_menu.name"
                                                    :href="
                                                        appRoute(sub_menu.link)
                                                    "
                                                    :set="
                                                        (currentActive =
                                                            appRoute().current(
                                                                sub_menu.link
                                                            )
                                                                ? key +
                                                                  '-' +
                                                                  subKey
                                                                : currentActive)
                                                    "
                                                >
                                                    {{ sub_menu.name }}
                                                </nav-link>
                                                <span
                                                    :title="sub_menu.name"
                                                    v-else
                                                >
                                                    {{ sub_menu.name }}</span
                                                >
                                            </template>
                                        </el-menu-item>
                                    </template>
                                </el-sub-menu>
                            </template>
                            <template v-else>
                                <el-menu-item
                                    class="text-lg"
                                    :key="key"
                                    :index="key.toString()"
                                >
                                    <el-icon><fa :icon="menu.icon" /></el-icon>
                                    <template #title>
                                        <nav-link
                                            :title="menu.name"
                                            :href="
                                                menu.link == '#'
                                                    ? menu.link
                                                    : appRoute(menu.link)
                                            "
                                            :set="
                                                (currentActive =
                                                    appRoute().current(
                                                        menu.link
                                                    )
                                                        ? key.toString()
                                                        : currentActive)
                                            "
                                        >
                                            {{ menu.name }}
                                        </nav-link>
                                    </template>
                                </el-menu-item>
                            </template>
                        </template>
                    </el-menu>
                </SimpleBar>
            </template>
        </el-drawer>
        <div
            class="brand-logo bg-primary text-white"
            :class="isCollapse ? 'hidden-sm-and-down' : ''"
        >
            <div class="menu-toggler" @click="isCollapse = !isCollapse">
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
            <div class="logo" v-if="!isCollapse">Brand Logo</div>
        </div>
        <SimpleBar id="menubar" :class="isCollapse ? 'is-collapsed' : ''">
            <el-menu
                :default-active="currentActive"
                class="el-menu-vertical"
                :collapse="isCollapse"
                :collapse-transition="false"
            >
                <template v-for="(menu, key) in menuList">
                    <template v-if="menu.link != '#'">
                        <el-menu-item
                            class="text-lg"
                            :key="key"
                            :index="key.toString()"
                            v-if="hasMenuAccess(menu)"
                        >
                            <el-icon><fa :icon="menu.icon" /></el-icon>
                            <template #title>
                                <nav-link
                                    :title="menu.name"
                                    :href="
                                        menu.link == '#'
                                            ? menu.link
                                            : appRoute(menu.link)
                                    "
                                    :set="
                                        (currentActive = appRoute().current(
                                            menu.link
                                        )
                                            ? key.toString()
                                            : currentActive)
                                    "
                                >
                                    {{ menu.name }}
                                </nav-link>
                            </template>
                        </el-menu-item>
                    </template>
                    <template
                        v-else-if="
                            menu.link == '#' &&
                            menu.children != undefined &&
                            menu.children.length != 0
                        "
                    >
                        <el-sub-menu
                            class="text-lg"
                            :key="key"
                            :index="key.toString()"
                        >
                            <template #title>
                                <el-icon><fa :icon="menu.icon" /> </el-icon>
                                <span :title="menu.name">{{ menu.name }}</span>
                            </template>
                            <template
                                v-for="(sub_menu, subKey) in menu.children"
                                :key="key + '-' + subKey"
                            >
                                <el-menu-item
                                    :index="key + '-' + subKey"
                                    v-if="hasMenuAccess(sub_menu)"
                                >
                                    <template #title
                                        ><el-icon v-show="!isCollapse">
                                            <span class="bullet-dot"></span>
                                        </el-icon>
                                        <nav-link
                                            v-if="sub_menu.link != '#'"
                                            :title="sub_menu.name"
                                            :href="appRoute(sub_menu.link)"
                                            :set="
                                                (currentActive =
                                                    appRoute().current(
                                                        sub_menu.link
                                                    )
                                                        ? key + '-' + subKey
                                                        : currentActive)
                                            "
                                        >
                                            {{ sub_menu.name }}
                                        </nav-link>
                                        <span :title="sub_menu.name" v-else>
                                            {{ sub_menu.name }}</span
                                        >
                                    </template>
                                </el-menu-item>
                            </template>
                        </el-sub-menu>
                    </template>
                    <template v-else>
                        <el-menu-item
                            class="text-lg"
                            :key="key"
                            :index="key.toString()"
                        >
                            <el-icon><fa :icon="menu.icon" /></el-icon>
                            <template #title>
                                <nav-link
                                    :title="menu.name"
                                    :href="
                                        menu.link == '#'
                                            ? menu.link
                                            : appRoute(menu.link)
                                    "
                                    :set="
                                        (currentActive = appRoute().current(
                                            menu.link
                                        )
                                            ? key.toString()
                                            : currentActive)
                                    "
                                >
                                    {{ menu.name }}
                                </nav-link>
                            </template>
                        </el-menu-item>
                    </template>
                </template>
            </el-menu>
        </SimpleBar>
    </div>
</template>
<script setup>
import { SimpleBar } from "simplebar-vue3";
import { watch, computed, inject, onMounted } from "vue";
import { useInertiaPropsUtility } from "@/Composables/inertiaPropsUtility";
import { useAppUtility } from "@/Composables/appUtiility";
let { iPropsValue } = useInertiaPropsUtility();
let { mediaCheck, hasMenuAccess } = useAppUtility();
let isCollapse = $ref(inject("isCollapse"));
let menuDrawer = $ref();
let currentActive = $ref("");
let menus = $ref(iPropsValue("app_menu", "menu_list"));
watch(
    () => iPropsValue("app_menu", "menu_list"),
    () => {
        menus = iPropsValue("app_menu", "menu_list");
    }
);
const menuList = computed(() => {
    return JSON.parse(menus);
});
const showMenuDrawer = function () {
    menuDrawer = true;
};
onMounted(() => {
    window.addEventListener("resize", () => {
        isCollapse = mediaCheck("lg");
    });
});
defineExpose({
    showMenuDrawer,
});
</script>
<style scoped>
.brand-logo {
    height: 60px;
    display: flex;
    align-items: center; /* Vertical */
    justify-content: center; /* Horizontal */
}
#menubar {
    height: calc(100vh - 60px); /* 60px header */
    overflow-y: auto;
}
#menubar:not(.is-collapsed) {
    width: 250px;
}
#menubar-drawer {
    height: calc(100vh - 60px); /* 60px header */
    overflow-y: auto;
}
#menubar-drawer:not(.is-collapsed) {
    width: 100%;
}

.el-menu-vertical,
.el-menu--collapse {
    min-height: calc(100vh - 60px); /* 60px header */
}
div.menu-toggler {
    padding: 10px;
    margin: 10px;
}
.menu-toggler:hover {
    background: #fff;
    border-radius: 50%;
    cursor: pointer;
    color: #000;
}
.logo {
    width: 100%;
}

.el-sub-menu.is-active {
    background: var(--menu-active);
}
.el-sub-menu__title span,
.el-menu-item span:not(.bullet-dot),
.el-menu-item a {
    width: 165px;
    text-overflow: ellipsis;
    overflow: hidden;
    line-height: 1.5;
    text-transform: capitalize;
}

.el-menu-item {
    height: 44px;
    line-height: 1.5;
}
.el-sub-menu .el-menu-item {
    height: 36px;
    line-height: 1.5;
}
.el-sub-menu .el-menu-item .el-icon {
    /* font-size: 12px; */
}
.el-menu-item.text-lg a,
.el-sub-menu.text-lg .el-sub-menu__title span {
    /* font-size: 16px; */
}
.el-sub-menu ul .el-icon {
    width: 16px;
}
.el-menu-group-title {
    padding-left: calc(
        var(--el-menu-base-level-padding) + var(--el-menu-level) *
            var(--el-menu-level-padding)
    );
    font-size: 14px;
    font-weight: bold;
    color: #9b9999;
    height: 32px;
    display: flex;
    align-items: center;
}
</style>
<style>
.el-sub-menu .el-sub-menu__icon-arrow {
    /* font-size: 16px; */
}
.el-menu-vertical .el-sub-menu__title {
    height: 44px;
    line-height: 1.5;
}
.bullet-dot {
    width: 4px;
    height: 4px;
    border-radius: 100% !important;
    background: var(--el-menu-text-color);
}

.menu-drawer .el-drawer__body {
    padding: 0px;
}
.menu-drawer .el-drawer__header {
    margin-bottom: 0px;
    padding: 0px 20px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: var(--el-color-primary);
    color: #fff;
}
.menu-drawer .el-drawer__close-btn:hover i {
    color: #d3c3c3;
}
</style>
