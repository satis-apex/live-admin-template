<template>
    <div>
        <el-upload
            v-loading="loading"
            class="upload-demo"
            drag
            ref="refUpload"
            action="#"
            :limit="1"
            :show-file-list="true"
            :on-change="handleUpload"
            :on-remove="handleRemove"
            :on-exceed="handleExceed"
            :auto-upload="false"
            accept=".xlsx, .xls"
        >
            <el-icon class="el-icon--upload"><upload-filled /></el-icon>
            <div class="el-upload__text">
                Drop file here or <em>click to upload</em>
            </div>
            <template #tip>
                <div class="el-upload__tip">
                    xls / xlsx files with a size less than 1Mb
                </div>
            </template>
        </el-upload>
    </div>
</template>
<script setup>
import { UploadFilled } from "@element-plus/icons-vue";
import { genFileId } from "element-plus";
import { reactive } from "@vue/reactivity";
const props = defineProps({
    onSuccess: Function,
});
const loading = $ref(false);
const refUpload = $ref(null);
const excelData = reactive({
    header: null,
    results: null,
});
const generateData = function ({ header, results }) {
    excelData.header = header;
    excelData.results = results;
    props.onSuccess && props.onSuccess(excelData);
};
const handleRemove = function () {
    excelData.header = null;
    excelData.results = null;
    props.onSuccess && props.onSuccess(excelData);
};
const handleExceed = function (files) {
    clearFiles();
    const file = files[0];
    file.uid = genFileId();
    refUpload.handleStart(file);
};
const clearFiles = function () {
    refUpload.clearFiles();
};
const handleUpload = function (file) {
    if (validateUpload(file.raw)) {
        const rawFile = file.raw;
        readerData(rawFile);
        import("xlsx").then(async (xlsx) => {
            await readerData(rawFile, xlsx);
        });
        return;
    } else {
        handleRemove();
    }
};
const readerData = function (rawFile, xlsx) {
    if (!xlsx) return;
    loading = true;
    return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.onload = (e) => {
            const data = e.target.result;
            const workbook = xlsx.read(data, { type: "array" });
            const firstSheetName = workbook.SheetNames[0];
            const worksheet = workbook.Sheets[firstSheetName];
            const header = getHeaderRow(worksheet, xlsx);
            const results = xlsx.utils.sheet_to_json(worksheet);
            generateData({ header, results });
            loading = false;
            resolve();
        };
        reader.readAsArrayBuffer(rawFile);
    });
};
const getHeaderRow = function (sheet, xlsx) {
    const headers = [];
    const range = xlsx.utils.decode_range(sheet["!ref"]);
    let C;
    const R = range.s.r;
    /* start in the first row */
    for (C = range.s.c; C <= range.e.c; ++C) {
        /* walk every column in the range */
        const cell = sheet[xlsx.utils.encode_cell({ c: C, r: R })];
        /* find the cell in the first row */
        let hdr = "UNKNOWN " + C; // <-- replace with your desired default
        if (cell && cell.t) {
            hdr = xlsx.utils.format_cell(cell);
        }
        headers.push(hdr);
    }
    return headers;
};
const isExcel = function (file) {
    return /\.(xlsx|xls|csv)$/.test(file.name);
};

const validateUpload = function (file) {
    const isLt2M = file.size / 1024 / 1024 < 1;
    if (!isExcel(file)) {
        ElMessage.error("Uploaded File must be xls or xlsx format!");
        return false;
    }
    if (!isLt2M) {
        ElMessage.error("Uploaded File size can not exceed 1MB!");
        return false;
    }
    return true;
};

defineExpose({
    clearFiles,
});
</script>
