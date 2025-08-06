<template>
    <el-cascader
      v-if="treeData.length > 0"
      v-model="department_id"
      :options="treeData"
      :props="fatherDeptProps"
      :placeholder="placeholder"
      clearable
      :show-all-levels="false"
      style="width: 100%"
      :size="size"
      @change="changeSelect"
    ></el-cascader>
</template>


<script>

import { getSysDeptTree } from "@/api/system/department";

export default {
  name: "SysDeptTree",

  props: {
    placeholder: {
      type: String,
      default: "请选择部门"
    },
    size: {
      type: String,
      default: "mini"
    },
    multiple: {
      type: Boolean,
      default: false
    },
  },

  data() {
    return {
      treeData: [],
      fatherDeptProps: {
        checkStrictly: true,
        value: 'id',
        label: 'title',
        children: 'children',
        emitPath: false,
        multiple: false
      },
      department_id: null
    }
  },
  mounted() {
    this.fatherDeptProps.multiple = this.multiple

    getSysDeptTree().then(res => {
      this.treeData = res.data
    })
  },
  methods: {
    changeSelect() {
      this.$emit("changeSelect", this.department_id)
    }
  }
}
</script>
