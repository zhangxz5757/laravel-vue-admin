<!--
 * @Description: 新增/编辑表单
 * @version:
 * @Author: smy
 * @Date: 2023-10-08 08:47:25
 * @LastEditors: smy
 * @LastEditTime: 2023-10-17 09:08:31
-->
<template>
  <div>
    <el-form :model="form" :rules="rules" ref="addFormComponent" label-width="80px">
      <el-form-item prop="title" label="部门名称">
        <el-input v-model="form.title" placeholder="请输入部门名称" autocomplete="off"></el-input>
      </el-form-item>
      <el-form-item prop="pid" label="上级部门">
        <el-cascader
            class="wd100"
            v-if="initDeptTree.length > 0"
            v-model="form.pid"
            :options="initDeptTree"
            :props="fatherDeptProps"
            clearable
            :show-all-levels="false"
        ></el-cascader>
      </el-form-item>
      <el-form-item prop="status" label="状态">
        <el-radio v-model="form.status" :label="1">启用</el-radio>
        <el-radio v-model="form.status" :label="0">禁用</el-radio>
      </el-form-item>
      <el-form-item prop="sort_id" label="排序">
        <el-input
            v-model.number="form.sort_id"
            placeholder="请输入排序"
            autocomplete="off"
        ></el-input>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import {deepClone} from '@/utils'
import {SysDeptFormRule,SysDeptFormData} from '../options'
import {RoleFormData} from "@/views/system/role/options";
import {getDeptList} from "@/api/system/department";

export default {
  props: {
    rowData: {
      type: Object,
      default() {
        return {}
      }
    },
    deptTree: {
      type: Array,
      default() {
        return []
      }
    }
  },
  data() {
    return {
      form: {},
      initDeptTree: [], // 用于上级企业选择的公司树形(多了一个顶级)
      fatherDeptProps: {
        checkStrictly: true,
        value: 'id',
        label: 'title',
        children: 'children',
        emitPath: false
      },
      // 新增/编辑 表单校验规则
      rules: SysDeptFormRule
    }
  },
  created() {
    this.form = !this.rowData.id ? deepClone(RoleFormData) : deepClone(this.rowData)

    this.initSysDeptTreeData()
  },
  methods: {
    initSysDeptTreeData() {
      getDeptList().then(res => {
        let initDeptTree = deepClone(res.data.list)
        initDeptTree.unshift({
          id: 0,
          title: '顶级'
        })
        this.initDeptTree = initDeptTree
      })
    }
  }


}
</script>

<style lang="scss" scoped>
</style>
