<template>
  <div>
    <el-form ref="addFormComponent" :model="form" :rules="rules" label-width="100px">
      <el-form-item prop="nickname" label="姓名">
        <el-input v-model="form.nickname" placeholder="请输入姓名" autocomplete="off"/>
      </el-form-item>
      <el-form-item prop="username" label="账号">
        <el-input v-model="form.username" placeholder="请输入账号" autocomplete="off"/>
      </el-form-item>
      <el-form-item prop="password" label="密码">
        <el-input v-model="form.password" type="password" placeholder="密码" autocomplete="off"/>
      </el-form-item>
      <el-form-item prop="mobile" label="手机号">
        <el-input v-model="form.mobile" placeholder="手机号" autocomplete="off"/>
      </el-form-item>
      <el-form-item prop="department_id" label="所属部门">
        <sys-dept-tree @changeSelect="changeDept"></sys-dept-tree>
      </el-form-item>
      <el-form-item prop="role_ids" label="角色">
        <el-select v-model="form.role_ids" placeholder="角色" multiple class="wd100">
          <el-option
              v-for="(item, index) in roleList"
              :key="index"
              :label="item.title"
              :value="item.id"
          />
        </el-select>
      </el-form-item>

      <el-form-item prop="status" label="状态" label-width="100px">
        <el-radio v-model="form.status" :label="1">启用</el-radio>
        <el-radio v-model="form.status" :label="0">禁用</el-radio>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import {UserFormData, UserFormRule} from "../options";
import {deepClone} from "@/utils";
import SysDeptTree from "@/views/system/department/components/SysDeptTree.vue";
import {getAllRole} from "@/api/system/role";

export default {
  name: "AddForm",
  components: {SysDeptTree},
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
      rules: UserFormRule,
      roleList: [],

      deptTree: [],
      // 菜单设置
      fatherDeptPropsSelect: {
        checkStrictly: true,
        value: 'id',
        label: 'title',
        children: 'children',
        emitPath: false,
      },
    }
  },

  created() {
    this.form = !this.rowData.id ? deepClone(UserFormData) : deepClone(this.rowData)

    this.getRoleList()
  },

  methods: {
    getRoleList() {
      getAllRole().then(res => {
        this.roleList = res.data
      })
    },

    changeDept(departmentId) {
      this.form.department_id = departmentId
    }
  }
}
</script>


<style>

</style>
