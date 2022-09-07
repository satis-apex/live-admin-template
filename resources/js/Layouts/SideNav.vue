<template>
    <div>
        <div class="brand-logo">
            <div
                class="menu-toggler"
                @click="this.isCollapse = !this.isCollapse"
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
            <div class="logo" v-if="!isCollapse">Brand Logo</div>
        </div>

        <el-menu
            :default-active="currentActive"
            class="el-menu-vertical"
            :collapse="isCollapse"
            :collapse-transition="false"
        >
            <!-- <el-menu-item-group title="Group Two">
                    <el-menu-item index="1-3">item three</el-menu-item>
                </el-menu-item-group> -->
            <template v-for="(menu, key) in menus">
                <template v-if="menu.link != '#'">
                    <el-menu-item class="text-lg" :key="key" :index="key">
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
                                        ? key
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
                    <el-sub-menu class="text-lg" :key="key" :index="key">
                        <template #title>
                            <el-icon><fa :icon="menu.icon" /> </el-icon>
                            <span :title="menu.name">{{ menu.name }}</span>
                        </template>

                        <el-menu-item
                            v-for="(sub_menu, subKey) in menu.children"
                            :index="key + '-' + subKey"
                            :key="key + '-' + subKey"
                        >
                            <template #title
                                ><el-icon>
                                    <span class="bullet-dot"></span>
                                </el-icon>
                                <nav-link
                                    v-if="sub_menu.link != '#'"
                                    :title="sub_menu.name"
                                    :href="appRoute(sub_menu.link)"
                                    :set="
                                        (currentActive = appRoute().current(
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
                    </el-sub-menu>
                </template>
                <template v-else>
                    <el-menu-item class="text-lg" :key="key" :index="key">
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
                                        ? key
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
    </div>
</template>
<script setup></script>
<script>
export default {
    props: { parentIsCollapse: Boolean },
    watch: {
        parentIsCollapse() {
            this.isCollapse = this.parentIsCollapse;
        },
    },
    data() {
        return {
            iconName: "Location",
            currentActive: "",
            isCollapse: this.parentIsCollapse,
            isMobile: navigator.userAgentData.mobile,
            menus: [
                {
                    link: "#",
                    name: "home",
                    icon: "pen",
                },
                {
                    link: "#",
                    name: "manage Organization",
                    icon: "key",
                    children: [
                        {
                            link: "#",
                            name: "Organization Group",
                            icon: "fa-minus",
                        },
                        {
                            link: "login",
                            name: "login",
                            icon: "folder",
                        },
                        {
                            link: "#",
                            name: "Organization Member",
                            icon: "eye",
                        },
                    ],
                },
            ],
        };
    },
    methods: {},
};
</script>
<style scoped>
.brand-logo {
    height: 64px;
    background: #f3f3f3;
    display: flex;
    align-items: center; /* Vertical */
    justify-content: center; /* Horizontal */
}
.el-menu-vertical:not(.el-menu--collapse) {
    width: 250px;
    min-height: 100%;
}
.el-menu--collapse {
    min-height: 100%;
}
div.menu-toggler {
    padding: 10px;
    margin: 10px;
}
.menu-toggler:hover {
    background: #c1c1c1;
    border-radius: 50%;
    cursor: pointer;
}
.logo {
    width: 100%;
}

.el-sub-menu.is-active {
    background: var(--active-highlight);
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
</style>
