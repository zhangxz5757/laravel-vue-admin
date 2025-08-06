<template>
  <div>
    <el-form size="mini" ref="addFormComponent" :model="form" :rules="rules" label-width="100px">
      <el-form-item prop="title" label="角色名称">
        <el-input v-model="form.title" autocomplete="off" placeholder="请输入角色名称"/>
      </el-form-item>

      <el-form-item prop="description" label="角色描述">
        <el-input type="textarea" v-model="form.description" autocomplete="off" placeholder="请输入角色描述"/>
      </el-form-item>

      <el-form-item prop="status" label="状态">
        <el-radio v-model="form.status" :label="1">启用</el-radio>
        <el-radio v-model="form.status" :label="0">禁用</el-radio>
      </el-form-item>

      <el-form-item prop="menu_ids" label="权限设置">
        <el-tree
          ref="roleMenuTree"
          :data="menus"
          show-checkbox
          node-key="id"
          :props="menuAuthProps"
          default-expand-all
          :default-checked-keys="form.menu_ids"
          :check-strictly="true"
          @check-change="handleNodeClick"
        ></el-tree>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import {RoleFormData, RoleFormRule} from "../options";
import {deepClone} from "@/utils";
import {getMenuTree} from "@/api/system/menu";

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
      rules: RoleFormRule,
      typeList: [],

      menus: [],
      // 菜单设置
      menuAuthProps: {
        label: 'title',
        children: 'child'
      },
    }
  },

  created() {
    this.form = !this.rowData.id ? deepClone(RoleFormData) : deepClone(this.rowData)

    this.listMenus()
  },

  methods: {
    listMenus() {
      getMenuTree().then(res => {
        this.menus = res.data
      })
    },

    handleNodeClick(data) {
      this.form.menu_ids = this.$refs.roleMenuTree.getCheckedKeys()
    }
  }
}
</script>


<style>

</style>
