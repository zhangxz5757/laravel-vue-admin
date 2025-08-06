<template>
  <div>
    <el-form size="mini" ref="addFormComponent" :model="form" :rules="rules" label-width="100px">
      <el-form-item prop="title" label="名称">
        <el-input v-model="form.title" autocomplete="off"/>
      </el-form-item>


      <el-form-item prop="type_id" label="分类">
        <el-select v-model="form.type_id">
          <el-option :key="item.id" v-for="item in typeList" :value="item.id" :label="item.title"></el-option>
        </el-select>
      </el-form-item>

      <el-form-item prop="cover_url" label="封面图">
        <upload-file
          :showText="false"
          :value="picImg"
          :max-size="5"
          :limit="1"
          @fileChange="changeCover">
        </upload-file>
      </el-form-item>

    </el-form>
  </div>
</template>

<script>
import {DemoFormRule, DemoFormData} from "../options";
import {deepClone} from "@/utils";
import UploadFile from "@/components/UploadFile.vue";

export default {
  name: "AddForm",
  components: {UploadFile},
  props: {
    rowData: {
      type: Object,
      default: function () {
        return {}
      }
    }
  },
  data() {
    return {
      form: {},
      rules: DemoFormRule,
      typeList: [],
      picImg: []
    }
  },

  created() {
    if (!this.rowData.id) {
      this.form = deepClone(DemoFormData)
    } else {
      console.log("update", this.rowData)
      this.form = deepClone(this.rowData)
      this.$nextTick(() => {
        this.picImg = [this.form.cover_url]
      })
    }
  },

  methods: {
    /**
     * 上传封面文件
     *
     * @param fileList
     */
    changeCover(fileList) {
      this.form.cover_url = fileList.length > 0 ? fileList[0].url : null
    },
  }
}
</script>


<style>

</style>
