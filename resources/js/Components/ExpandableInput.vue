<template>
    <div class="relative contents">
        <a class="search-btn" @click.prevent="toggleInput" href="#">
            <!-- Seach Icon -->
            <fa icon="search"></fa>
        </a>
        <div class="search-box" :class="active ? 'active' : ''">
            <input
                ref="refUserInput"
                v-model="userInput"
                type="text"
                class="search-input"
                placeholder="Start Looking"
            />
        </div>
    </div>
</template>
<script setup>
const active = $ref(false);
const refUserInput = $ref(null);
const userInput = $ref(null);
const toggleInput = function () {
    if (active) {
        userInput = "";
        active = false;
    } else {
        active = true;
        refUserInput.focus();
    }
};
defineExpose({ userInput });
</script>
<style>
.search-box {
    position: absolute;
    top: 50%;

    transform: translate(-50%, -50%);

    height: 40px;
    border-radius: 50px;
    /* padding: 10px; */
}
/* input */
.search-input {
    outline: none;
    border: none;
    background: none;
    width: 0;
    padding: 0;
    color: #fff;
    float: left;
    font-size: 16px;
    transition: 0.3s;
    line-height: 40px;
    background: var(--el-color-primary);
    position: absolute;
    border-radius: 0px 20px 20px 0px;
    margin-left: 18px;
}
.search-input::placeholder {
    color: #ddd;
}
/* icon */
.search-btn {
    color: #fff;
    float: left;
    width: 40px;
    height: 40px;
    border-radius: 50px;
    background: var(--el-color-primary);
    display: flex;
    justify-content: center;
    align-items: center;
    text-decoration: none;
    transition: 0.3s;
    position: absolute;
    z-index: 1;
    top: -4px;
}
.search-input:focus,
.search-input:not(:placeholder-shown) {
    width: 240px;
    padding: 0 6px;
}
.search-box.active > .search-input {
    width: 240px;
    padding: 0 12px;
    padding-left: 22px;
}
.search-box.active > .search-btn,
.search-input:focus + .search-btn,
.search-input:not(:placeholder-shown) + .search-btn {
    background: #fff;
    color: #cd595a;
}
</style>
