<template>
  <div>
    <el-form ref="addFormComponent" :model="form" :rules="rules" label-width="100px">
      <el-form-item prop="alias" label="字典名称">
        <el-select v-model="form.alias" disabled class="wd100">
          <el-option :key="item.alias" v-for="item in aliasList" :value="item.alias" :label="item.title" clearable></el-option>
        </el-select>
      </el-form-item>

      <el-form-item prop="label" label="字典标签">
        <el-input v-model="form.label" autocomplete="off"/>
      </el-form-item>

      <el-form-item prop="value" label="字典键值">
        <el-input v-model="form.value" autocomplete="off"/>
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
import {DictDataFormRule, DictDataFormData} from "../options";
import {deepClone} from "@/utils";
import {dictTypeAll} from "@/api/tool/dict_type";

export default {
  name: "AddForm",
  components: {},
  props: {
    rowData: {
      type: Object,
      default: function () {
        return {}
      }
    },
    alias: {
      type: String,
      default: null
    }
  },
  data() {
    return {
      form: {},
      rules: DictDataFormRule,
      aliasList: []
    }
  },

  created() {
    this.getDictTypeList()
    if (!this.rowData.id) {
      this.form = deepClone(DictDataFormData)
    } else {
      this.form = deepClone(this.rowData)
    }
    this.form.alias = this.alias
  },

  methods: {
    getDictTypeList() {
      dictTypeAll().then(res => {
        this.aliasList = res.data.list
      })
    },
  }
}
</script>


<style>

</style>
