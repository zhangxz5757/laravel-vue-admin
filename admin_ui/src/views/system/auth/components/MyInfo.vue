<template>
  <el-form ref="addForm" :model="form" :rules="rules">
    <el-form-item prop="nickname" label="姓名" label-width="100px">
      <el-input v-model="form.nickname" placeholder="请输入姓名" autocomplete="off" />
    </el-form-item>
    <el-form-item prop="username" label="账号" label-width="100px">
      <el-input v-model="form.username" placeholder="请输入账号" autocomplete="off" :disabled="true"/>
    </el-form-item>
    <el-form-item prop="password" label="密码" label-width="100px">
      <el-input v-model="form.password" type="password" placeholder="密码" autocomplete="off" />
    </el-form-item>
    <el-form-item prop="mobile" label="手机号" label-width="100px">
      <el-input v-model="form.mobile" placeholder="手机号" autocomplete="off" />
    </el-form-item>
    <el-form-item prop="dept_id" label="所属部门" label-width="100px">
      <el-cascader
        v-if="deptTree.length > 0"
        v-model="form.dept_id"
        :options="deptTree"
        :props="fatherDeptProps"
        clearable
        :show-all-levels="false"
        :disabled="true"
      ></el-cascader>
    </el-form-item>
    <el-form-item prop="group_id" label="角色" label-width="100px">
      <el-select v-model="form.group_id" placeholder="角色" multiple  :disabled="true">
        <el-option
          v-for="item in allGroup"
          :key="'group_' + item.id"
          :label="item.title"
          :value="item.id"
        />
      </el-select>
    </el-form-item>

    <el-form-item prop="status" label="状态" label-width="100px">
      <el-radio v-model="form.status" :label="1" :disabled="true">启用</el-radio>
      <el-radio v-model="form.status" :label="0" :disabled="true">禁用</el-radio>
    </el-form-item>

    <el-form-item align="center">
      <el-button type="primary" @click="saveInfo()">提交</el-button>
    </el-form-item>
  </el-form>
</template>

<script>
import { MyFormRole } from '../options'
import {getAllRole} from "@/api/system/role";
import {getMyInfo, saveCurrentSysUser} from "@/api/auth";

export default {
  data() {
    return {
      form: {},
      inputDisabled: true,
      rules: MyFormRole, // 表单校验规则
      fatherDeptProps: {
        checkStrictly: true,
        value: 'id',
        label: 'title',
        children: 'children',
        emitPath: false
      },
      deptTree: [],
      allGroup: []
    }
  },
  created() {
    getAllRole().then(res => {
      this.allGroup = res.data
    })

    getMyInfo().then(res => {
      this.form = res.data
    })
  },
  methods: {
    saveInfo() {
      this.$refs.addForm.validate(valid => {
        if (valid) {
          saveCurrentSysUser(this.form).then(res => {
            if (res.code == 200) {
              this.$message.success("修改成功")
              this.$emit('closeDialog')
            }
          })
        }
      })
    }
  }
}
</script>

<style lang="scss" scoped>
.el-select,
.el-cascader {
  width: 100%;
}

::v-deep .avatar-uploader {
  .el-upload {
    border: 1px dashed #d9d9d9;
    border-radius: 6px;
    cursor: pointer;
    position: relative;
    overflow: hidden;
    display: block;
  }
  .el-upload:hover {
    border-color: #409eff;
  }
  .avatar-uploader-icon {
    font-size: 28px;
    color: #8c939d;
    width: 178px;
    height: 178px;
    line-height: 178px;
    text-align: center;
  }
}
.avatar {
  width: 178px;
  height: 178px;
  overflow: hidden;
  background-color: #fff;
  border: 1px solid #c0ccda;
  border-radius: 6px;
  box-sizing: border-box;
  margin-right: 15px;
  position: relative;
}
.form{
  flex-wrap:wrap;
  .el-form-item{
    width:50%;
  }
}
</style>
