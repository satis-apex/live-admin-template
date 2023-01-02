<script setup>
import BreezeButton from "@/Components/Button.vue";
import BreezeCheckbox from "@/Components/Checkbox.vue";
import BreezeGuestLayout from "@/Layouts/Guest.vue";
import BreezeInput from "@/Components/Input.vue";
import BreezeLabel from "@/Components/Label.vue";
import BreezeValidationErrors from "@/Components/ValidationErrors.vue";
import { Head, Link, useForm } from "@inertiajs/inertia-vue3";
import { User, Lock } from "@element-plus/icons-vue";
import { reactive } from "@vue/reactivity";
// import customMixins from "@/Includes/customMixins";
defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    email: "",
    password: "",
    remember: false,
});
const refLoginForm = $ref(null);
const submit = async () => {
    if (!refLoginForm) return;
    await refLoginForm.validate((valid, fields) => {
        if (valid) {
            form.post(route("login"), {
                onFinish: () => resetForm(),
            });
        } else {
        }
    });
};
const resetForm = () => {
    if (!refLoginForm) return;
    refLoginForm.resetFields();
    form.reset("password");
};
const rules = reactive({
    email: [
        {
            required: true,
            type: "email",
            message: "Please Type Valid Email",
            trigger: "blur",
        },
    ],
    password: [
        { required: true, message: "Please Enter Password", trigger: "blur" },
    ],
});
</script>
<script>
export default {
    layout: "auth",
};
</script>
<style lang="scss">
#login-form {
    .el-form-item__error {
        color: #c10000;
        text-shadow: 1px 1px 4px #ffffff;
    }
}
</style>
<template>
    <BreezeGuestLayout>
        <Head title="Log in" />
        <h2 class="mb-4 text-gray-50 text-3xl text-center">Sign In</h2>
        <BreezeValidationErrors class="mb-4 bg-white/70 p-3 sm:rounded-lg" />

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <el-form
            ref="refLoginForm"
            id="login-form"
            @submit.prevent="submit"
            :rules="rules"
            :model="form"
        >
            <el-form-item label="" prop="email">
                <el-input
                    v-model="form.email"
                    class=""
                    placeholder="Email"
                    :prefix-icon="User"
                    size="large"
                    type="email"
                    id="email"
                    autofocus
                    autocomplete="username"
                    required
                    v-on:keyup.enter="submit()"
                />
            </el-form-item>
            <el-form-item label="" prop="password">
                <el-input
                    v-model="form.password"
                    class="mt-4"
                    placeholder="Password"
                    :prefix-icon="Lock"
                    size="large"
                    type="password"
                    id="password"
                    autofocus
                    autocomplete="current-password"
                    required
                    show-password
                    v-on:keyup.enter="submit()"
                />
            </el-form-item>
            <div class="block mt-4">
                <label class="flex items-center">
                    <BreezeCheckbox
                        name="remember"
                        v-model:checked="form.remember"
                    />
                    <span class="ml-2 text-md text-white">Remember me</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                <Link
                    v-if="canResetPassword"
                    :href="appRoute('password.request')"
                    class="underline text-sm text-gray-100 hover:text-gray-900"
                >
                    Forgot your password?
                </Link>
                <el-button
                    type="primary"
                    plain
                    size="large"
                    :class="{ 'opacity-60': form.processing }"
                    :loading="form.processing"
                    @click="submit"
                >
                    <fa class="mr-1" icon="right-to-bracket" /> Sign
                    In</el-button
                >
            </div>
        </el-form>
    </BreezeGuestLayout>
</template>
