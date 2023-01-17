<template>
    <div>
        <el-drawer
            v-model="menuDrawer"
            :with-header="true"
            :show-close="true"
            direction="ltr"
            :size="250"
            modal-class="menu-drawer"
        >
            <template #header>
                <div class="logo">
                    <ApplicationLogo />
                </div>
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
            class="brand-logo bg-primary text-white hidden-sm-and-down"
            :class="isCollapse ? '!w-16 flex' : ''"
        >
            <div class="logo" v-if="!isCollapse">
                <ApplicationLogo />
            </div>
            <div class="logo" v-if="isCollapse">
                <ApplicationLogo :mnemonic="true" />
            </div>
        </div>
        <SimpleBar
            id="menubar"
            class="hidden-sm-and-down"
            :class="isCollapse ? 'is-collapsed' : ''"
        >
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
                            <nav-link
                                class="link-icon"
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
                                ><el-icon><fa :icon="menu.icon" /></el-icon>
                            </nav-link>
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
import { ref, watch, computed, onMounted } from "@vue/runtime-core";
import { useInertiaPropsUtility } from "@/Composables/inertiaPropsUtility";
import { useAppUtility } from "@/Composables/appUtiility";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
const { iPropsValue } = useInertiaPropsUtility();
const { isScreenLg, hasMenuAccess } = useAppUtility();

const menuDrawer = ref();
const currentActive = ref("");
const menuList = ref(iPropsValue("app_menu"));

const isCollapse = ref(
    localStorage.collapseMenu && localStorage.collapseMenu == "true"
        ? true
        : isScreenLg.value
        ? true
        : false
);
const showMenuDrawer = function () {
    menuDrawer.value = true;
};
const toggleDesktopMenu = function () {
    isCollapse.value = !isCollapse.value;
    localStorage.collapseMenu = isCollapse.value;
};
window.addEventListener("resize", () => {
    isCollapse.value =
        localStorage.collapseMenu && localStorage.collapseMenu == "true"
            ? true
            : isScreenLg.value
            ? true
            : false;
});
defineExpose({
    showMenuDrawer,
    toggleDesktopMenu,
});
</script>
<style lang="scss" scoped>
.brand-logo {
    height: 60px;
    display: flex;
    align-items: center; /* Vertical */
    justify-content: center; /* Horizontal */
    text-align: center;
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

.logo {
    img {
        height: 45px;
        max-width: 100%;
    }
    display: flex;
    align-items: center;
    justify-content: center;
}

.el-sub-menu.is-active {
    background: var(--menu-active-color);
}
.el-sub-menu__title span,
.el-menu-item span:not(.bullet-dot),
.el-menu-item a:not(.link-icon) {
    width: 165px;
    text-overflow: ellipsis;
    overflow: hidden;
    line-height: 1.5;
    text-transform: capitalize;
}
.el-menu-item {
    height: 44px;
    line-height: 1.5;
    a {
        height: 100%;
        vertical-align: middle;
        display: flex;
        align-items: center;
        &.link-icon {
            width: unset;
            padding: unset;
            margin-top: -2px;
        }
    }
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
<style lang="scss">
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
.menu-drawer {
    z-index: 100 !important;
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
