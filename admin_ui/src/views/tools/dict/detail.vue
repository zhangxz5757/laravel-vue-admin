<template>
  <div class="container">
    <div class='handle-btns'>
        <el-button size="mini" type="primary" @click="showDialog('add')">新增</el-button>
    </div>

    <base-table :table-head="headTable" :table-data="tableData" :show-pagination="false" :row-formatter="colFormatter" >
      <template slot="handles">
        <el-table-column label="操作" width='120' align="center" fixed="right">
          <template slot-scope="scope">
            <el-button
              type="text"
              size="small"
              @click="showDialog('edit', scope.row)"
            >编辑</el-button>
            <el-popconfirm title="确定要删除此行么？" @confirm="showDialog('del', scope.row)">
              <el-button
                slot="reference"
                type="text"
                style="color: #f56c6c; margin-left: 10px"
                size="small"
              >删除</el-button>
            </el-popconfirm>
          </template>
        </el-table-column>
      </template>
    </base-table>
    <el-dialog
      width="600px"
      :title="formDialog.title"
      :visible.sync="formDialog.show"
    >
      <code-form ref="codeForm" v-if="formDialog.show" :formType="formDialog.formType" :type="formDialog.type" :row-data="formDialog.rowData"></code-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="formDialog.show = false">取 消</el-button>
        <el-button type="primary" @click="handleSubmit">确 定</el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import CodeForm from './components/code_form.vue'
import { codeDetailHeader, statusOptions } from '@/views/tools/dict/options'
import moment from 'moment/moment'
import { deleteCodeData, getCodeDataList, saveCodeData } from '@/api/tool/dict_type'

export default {
  name: "codeDetail",
  components: { CodeForm },
  data() {
    return {
      currentAlias: '',
      statusOptions,
      headTable: codeDetailHeader,
      tableData: [],
      formDialog: {
        show: false,
        title: '新增',
        rowData: {},
        type: 'add',
        formType: 2
      },
    }
  },
  created() {
    this.currentAlias = this.$route.query.alias
    this.getList()
  },
  methods: {
    handleSubmit() {
      let codeForm = this.$refs.codeForm
      let { type } = this.formDialog
      codeForm.$refs.ruleForm.validate(valid => {
        if (valid) {
          let data = { ...codeForm.ruleForm }
          if (type === 'edit') data.id = this.formDialog.rowData.id
          data.alias = this.currentAlias
          saveCodeData(data)
            .then(res => {
              this.formDialog.show = false
              this.getList()
            })
        }
      })
    },
    showDialog(type, row) {
      this.formDialog.type = type
      switch (type) {
        case 'add':
          this.formDialog.title = '新增'
          this.formDialog.show = true
          break
        case 'edit':
          this.formDialog.title = '编辑'
          this.formDialog.rowData = { ...row }
          this.formDialog.show = true
          break
        case 'del':
          deleteCodeData({ id: row.id })
            .then(res => {
              this.getList()
            })
          break
      }
    },
    colFormatter(row, column, cellValue, index) {
      if (column.property === "status") {
        statusOptions.map(item => {
          if (cellValue == item.value) cellValue = item.label
        })
      }
      if (column.property === "create_time") {
        return cellValue ? moment(cellValue).format('yyyy-MM-DD HH:mm:ss') : cellValue
      }
      return cellValue
    },
    getList() {
      getCodeDataList({ alias: this.currentAlias })
        .then(res => {
          this.tableData = res.data.list
        })
    }
  }
}
</script>

<style scoped lang="scss">

.base-table{
  background:#fff;
  border-radius:  0 0 6px 6px;
}
</style>
