<template>
  <div>
    <el-form size="mini" ref="addFormComponent" :model="form" :rules="rules" label-width="100px">
      <el-form-item prop="title" label="字典名称">
        <el-input v-model="form.title" autocomplete="off"/>
      </el-form-item>
      <el-form-item prop="alias" label="字典别名">
        <el-input v-model="form.alias" autocomplete="off"/>
      </el-form-item>

      <el-form-item prop="remark" label="字典备注">
        <el-input v-model="form.remark" autocomplete="off"/>
      </el-form-item>

      <el-form-item prop="sort_id" label="字典排序">
        <el-input v-model="form.sort_id" autocomplete="off"/>
      </el-form-item>

      <el-form-item prop="status" label="状态">
        <el-radio v-model="form.status" :label="1">启用</el-radio>
        <el-radio v-model="form.status" :label="0">禁用</el-radio>
      </el-form-item>

    </el-form>
  </div>
</template>

<script>
import {DictTypeFormRule, DictTypeFormData} from "../options";
import {deepClone} from "@/utils";
import {demoType} from "@/api/demo";
import {dictTypeMaxSort} from "@/api/tool/dict_type";

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
      rules: DictTypeFormRule,
      typeList: []
    }
  },

  created() {
    if (!this.rowData.id) {
      this.form = deepClone(DictTypeFormData)
      this.getMaxSortId()
    } else {
      this.form = deepClone(this.rowData)
    }
  },

  methods: {
    getMaxSortId() {
      dictTypeMaxSort().then(res => {
        if (res.code === 200) {
          this.form.sort_id = res.data.max + 1
        } else {
          this.form.sort_id = 1
        }
      }).catch(() => {
        this.form.sort_id = 1
      })
    }
  }
}
</script>


<style>

</style>
