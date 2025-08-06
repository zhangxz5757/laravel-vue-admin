<template>
  <div>
    <el-form v-if="formType === 1" ref='ruleForm' :model='ruleForm' :rules='rules' label-width='100px' class='demo-ruleForm'>
      <el-form-item label='字典名称' prop='title'>
        <el-input v-model='ruleForm.title' placeholder='请输入字典名称' :style='{ width: `${width}px`}'/>
      </el-form-item>
      <el-form-item label='字典编码' prop='alias'>
        <el-input v-model='ruleForm.alias' placeholder='请输入字典编码' :style='{ width: `${width}px`}'/>
      </el-form-item>
      <el-form-item label='排序' prop='sort_id'>
        <el-input v-model.number='ruleForm.sort_id' placeholder='请输入排序' :style='{ width: `${width}px`}'></el-input>
      </el-form-item>
      <el-form-item label='状态' prop='status'>
        <el-radio-group v-model="ruleForm.status">
          <el-radio
            v-for="item in statusOptions"
            :key="item.value"
            :label="item.value"
            :value="item.value"
          >
            {{ item.label }}
          </el-radio>
        </el-radio-group>
      </el-form-item>
    </el-form>
    <el-form v-if="formType === 2" ref='ruleForm' :model='ruleForm' :rules='rules' label-width='100px' class='demo-ruleForm'>
      <el-form-item label='字典标签' prop='label'>
        <el-input v-model='ruleForm.label' placeholder='请输入字典标签' :style='{ width: `${width}px`}'/>
      </el-form-item>
      <el-form-item label='字典值' prop='value'>
        <el-input v-model='ruleForm.value' placeholder='请输入字典值' :style='{ width: `${width}px`}'/>
      </el-form-item>
      <el-form-item label='排序' prop='sort_id'>
        <el-input v-model.number='ruleForm.sort_id' placeholder='请输入排序' :style='{ width: `${width}px`}'></el-input>
      </el-form-item>
      <el-form-item label='状态' prop='status'>
        <el-radio-group v-model="ruleForm.status">
          <el-radio
            v-for="item in statusOptions"
            :key="item.value"
            :label="item.value"
            :value="item.value"
          >
            {{ item.label }}
          </el-radio>
        </el-radio-group>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import { statusOptions } from '@/views/tools/dict/options'

export default {
  name: "codeForm",
  props: {
    width: {
      type: Number,
      default: 200
    },
    formType: {
      type: Number,
      default: 1
    },
    rowData: {
      type: Object,
      default() {
        return {}
      }
    },
    type: {
      type: String,
      default: 'add'
    }
  },
  data() {
    return {
      statusOptions,
      ruleForm: {},
      rules: {}
    }
  },
  created() {
    switch (this.formType) {
      case 1:
        this.ruleForm = {
          title: '',
          alias: '',
          sort_id: '',
          status: 1
        }
        this.rules = {
          title: [
            { required: true, message: '请输入字典名称', trigger: 'blur' },
          ],
          alias: [
            { required: true, message: '请输入字典编码', trigger: 'blur' },
          ],
          sort_id: [
            { required: true, message: '请输入排序', trigger: 'blur' },
          ],
          status: [
            { required: true, message: '请选择状态', trigger: 'change' },
          ],
        }
        break
      case 2:
        this.ruleForm = {
          label: '',
          value: '',
          sort_id: '',
          status: 1
        }
        this.rules = {
          label: [
            { required: true, message: '请输入字典标签', trigger: 'blur' },
          ],
          value: [
            { required: true, message: '请输入字典值', trigger: 'blur' },
          ],
          sort_id: [
            { required: true, message: '请输入排序', trigger: 'blur' },
          ],
          status: [
            { required: true, message: '请选择状态', trigger: 'change' },
          ],
        }
        break
    }
    if (this.type == 'edit') {
      Object.keys(this.ruleForm).forEach(key => (this.ruleForm[key] = this.rowData[key]))
    }
  }
}
</script>

<style scoped lang="scss">

</style>
