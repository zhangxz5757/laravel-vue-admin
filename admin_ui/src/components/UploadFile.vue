<template>
    <div>
        <div v-if="showMsg">{{ msg }}</div>
        <el-upload
            ref="upload"
            action="/api/admin/uploads"
            :list-type="listType"
            :auto-upload="true"
            :headers="headerObj"
            :file-list="fileList"
            :before-upload="beforeUpload"
            :on-success="successUpload"
            :on-preview="handlePictureCardPreview"
            :on-remove="handleRemove"
            :limit="limit"
            accept=".jpg, .jpeg, .png"
        >
            <i slot="default" class="el-icon-plus" />
            <div v-if="showText" slot="tip" class="el-upload__tip">
                支持上传jpg/png格式图片,单个图片不能超过{{
                    maxSize
                }}M,最多上传{{ limit }}张<span v-if="suggestedSize"
                    >,建议尺寸: {{ suggestedSize }}</span
                >
            </div>
        </el-upload>
        <el-dialog :visible.sync="dialogVisible" append-to-body>
            <img width="100%" :src="dialogImageUrl" alt="" />
        </el-dialog>
    </div>
</template>

<script>
import { getToken } from "@/utils/auth";

export default {
    name: "XUploadList",
    props: {
        //文件大小限制
        maxSize: {
            default: 2,
        },
        //文件数限制
        limit: {
            default: 1,
        },
        // 显示提示文本？
        showText: {
            default: true,
        },
        // 是否显示上文本
        showMsg: {
            default: false,
            type: Boolean,
        },
        msg: {
            default: "",
            type: String,
        },
        value: {
            default: null,
            type: Array,
        },
        //建议尺寸
        suggestedSize: {
            default: "",
        },
        listType: {
            default: "picture-card",
            type: String,
        },
    },
    computed: {
        // 获取公共封装的请求地址
        uploadUrl: {
            get() {
                return process.env.VUE_APP_BASE_API + "/api/upload";
            },
        },
    },
    data() {
        return {
            dialogImageUrl: "",
            dialogVisible: false,
            fileList: [],
            disabled: false,
            // 设置请求头-看后端需求
            headerObj: {
                Authorization: getToken(),
            },
        };
    },
    watch: {
        // 监听初始时图片列表长度, 与limit相等则隐藏按钮
        fileList: {
            handler() {
                this.checkLimit(this.fileList);
                this.fileChange();
            },
        },
        value: {
            deep: true, // 深度监听
            handler(val) {
                console.log("图片izhi", val);
                if (val) {
                    if (Array.isArray(val)) {
                        this.fileList = [];
                        // 图片列表回显
                        val.forEach((item, index) => {
                            // this.fileList.push({name:item,url:process.env.VUE_APP_API+"/"+item})
                            this.fileList.push({ name: item, url: item });
                        });
                    } else {
                        this.fileList = [];
                        this.fileList.push({ name: val, url: val });
                    }
                }
            },
        },
    },
    methods: {
        // 检查对比图片数量，对新增按钮进行隐藏
        checkLimit(filelist) {
            const limit = this.limit;
            this.$nextTick(() => {
                const uploadDom = this.$refs["upload"];
                if (filelist.length === limit) {
                    uploadDom.$children[1].$el.style.display = "none";
                } else {
                    uploadDom.$children[1].$el.style.display = "";
                }
            });
        },
        // 放大查看图片
        handlePictureCardPreview(file) {
            this.dialogImageUrl = file.url;
            this.dialogVisible = true;
        },
        // 删除图片
        handleRemove(file) {
            // console.log(file)
            for (let i = 0; i < this.fileList.length; i++) {
                if (this.fileList[i].uid === file.uid) {
                    this.fileList.splice(i, 1);
                }
            }
        },
        //监听改变
        fileChange() {
            this.$emit("fileChange", this.fileList);
        },
        //成功上传
        successUpload(response, file, fileList) {
            console.log("执行到这里。。。", response, file, fileList);
            this.fileList.push(file);
        },
        // 选中前钩子
        beforeUpload(file) {
            // console.log("123456",file);
            const fileSuffix = file.name.substring(
                file.name.lastIndexOf(".") + 1
            );
            // 文件类型
            const whiteList = ["jpg", "jpeg", "png"];
            // 验证格式
            if (whiteList.indexOf(fileSuffix) === -1) {
                this.$message.error(
                    file.name + "上传文件只能是 jpg、jpeg、png格式"
                );
                this.$refs.upload.handleRemove(file);
                return false;
            }

            const isLt5M = file.size / 1024 / 1024 < this.maxSize;
            // 验证大小
            if (!isLt5M) {
                setTimeout(() => {
                    this.$message.error(
                        file.name +
                            "上传文件大小不能超过 " +
                            this.maxSize +
                            "MB"
                    );
                }, 10);
                this.$refs.upload.handleRemove(file);
                return false;
            }
        },
    },
};
</script>
