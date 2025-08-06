<!--
 * @Description: 
 * @version: 
 * @Author: smy
 * @Date: 2023-09-28 18:49:57
 * @LastEditors: smy
 * @LastEditTime: 2023-10-07 08:29:44
-->
<template>
  <el-upload
    class="upload-demo"
    ref="upload"
    action="/api/admin/uploads"
    :headers="{
      Authorization: Authorization
    }"
    name="file"
    :on-preview="handlePreview"
    :on-remove="handleRemove"
    :file-list="fileList"
    :auto-upload="false"
  >
    <el-button slot="trigger" size="small" type="primary">选取文件</el-button>
    <el-button style="margin-left: 10px" size="small" type="success" @click="submitUpload"
      >上传到服务器</el-button
    >
  </el-upload>
</template>

<script>
import { getToken } from '@/utils/auth' // get token from cookie
export default {
  data() {
    return {
      Authorization: getToken(),
      fileList: []
    }
  },
  methods: {
    submitUpload() {
      this.$refs.upload.submit()
    },
    handleRemove(file, fileList) {
      console.log(file, fileList)
    },
    handlePreview(file) {
      console.log(file)
    }
  }
}
</script>

<style>
</style>