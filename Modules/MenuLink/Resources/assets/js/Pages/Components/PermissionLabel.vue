<template>
    <ul class="inline-block label hidden-sm-and-down">
        <li
            class="inline-block"
            v-for="(permission, key) in menuPermission"
            :key="key"
        >
            {{ permission }}
        </li>
    </ul>
</template>
<script setup>
import { watch, computed } from "@vue/runtime-core";
import { useInertiaPropsUtility } from "@/Composables/inertiaPropsUtility";
const props = defineProps({
    menuId: Number,
    role: String,
});
const menuId = $ref(props.menuId);
const role = $ref(props.role);
const { iPropsValue } = useInertiaPropsUtility();
const roleMenus = $ref(iPropsValue("roleMenus"));
watch(
    () => iPropsValue("roleMenus"),
    () => {
        roleMenus = iPropsValue("roleMenus");
    },
    { deep: true }
);
const menuPermission = computed(() => {
    if (
        Object.keys(roleMenus).length != 0 &&
        roleMenus[role]?.hasOwnProperty(menuId)
    ) {
        return roleMenus[role][menuId];
    }
    return null;
});
</script>
<style scoped>
ul.label li {
    background: var(--el-color-primary-dark-2);
    margin: 2px 3px;
    color: #fff;
    font-weight: 600;
    padding: 0px 5px;
    font-size: 11px;
    border-radius: 4px;
}
</style>
