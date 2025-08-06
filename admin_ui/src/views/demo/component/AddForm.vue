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

      <el-form-item prop="status" label="状态">
        <el-radio v-model="form.status" :label="1">启用</el-radio>
        <el-radio v-model="form.status" :label="0">禁用</el-radio>
      </el-form-item>

    </el-form>
  </div>
</template>

<script>
import {DemoFormRule, DemoFormData} from "../options";
import {deepClone} from "@/utils";
import {demoType} from "@/api/demo";

export default {
  name: "AddForm",
  components: {},
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
      typeList: []
    }
  },

  created() {
    // this.getTypeOption()

    if (!this.rowData.id) {
      this.form = deepClone(DemoFormData)
    } else {
      this.form = deepClone(this.rowData)
    }
  },

  methods: {
    // 查询下拉选项值
    getTypeOption() {
      demoType().then(res => {
        this.query.type_list = res.data.list
      })
    },
  }
}
</script>


<style>

</style>
