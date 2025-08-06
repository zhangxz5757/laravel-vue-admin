<template>
  <div class="container">
    <div class='handle-btns flex flex-sb'>
      <div>
        <el-button type="primary" icon="el-icon-plus" @click="showDialog('add')">新增</el-button>
      </div>
      <el-form :inline="true" :model="listQuery" class="demo-form-inline" label-width='100px'>
        <el-form-item label="字典名称">
          <el-input v-model="listQuery.keyword" placeholder="字典名称" style='width: 200px' clearable></el-input>
        </el-form-item>
        <el-form-item label="字典编码">
          <el-input v-model="listQuery.alias" placeholder="字典编码" style='width: 200px' clearable></el-input>
        </el-form-item>
        <el-form-item>
          <el-button type="success" icon="el-icon-search" @click="onSearch">搜索</el-button>
        </el-form-item>
        <el-form-item>
          <el-button type="warning" icon="el-icon-refresh" @click="onReset">重置</el-button>
        </el-form-item>
      </el-form>
    </div>
    <base-table :customColumnTableHead="false" :table-head="headTable" :table-data="tableData" :paging-data="pageInfo" :row-formatter="colFormatter" @handleSizeChange="handleSizeChange" @handleCurrentChange="handleCurrentChange" @handleTableHead="handleTableHead">
      <template slot="handles">
        <el-table-column label="操作" width='120' align="center" fixed="right">
          <template slot-scope="scope">
            <el-button
              type="text"
              size="small"
              @click="goToDetail(scope.row)"
            >详情</el-button>
            <el-button
              type="text"
              size="small"
              @click="showDialog('edit', scope.row)"
            >编辑</el-button>
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

import { getCodeList, saveCode } from '@/api/tool/dict_type'
import { codeHeader, statusOptions } from '@/views/tools/dict/options'
import CodeForm from './components/code_form.vue'
import moment from 'moment'

export default {
  name: "codeManage",
  components: { CodeForm },
  data() {
    return {
      listQuery: {
        keyword: '',
        alias: ''
      },
      // 分页信息
      pageInfo: {
        page: 1,
        per_page: 20,
        total: 0,
        currentPage: 1 // 当前选择的页码
      },
      headTable: codeHeader,
      tableData: [],
      formDialog: {
        show: false,
        title: '新增',
        rowData: {},
        type: 'add',
        formType: 1
      },
    }
  },
  created() {
    this.getList()
  },
  methods: {
    goToDetail(row) {
      this.$router.push({
        path: '/dict/detail',
        query: {
          alias: row.alias
        }
      })
    },
    handleSubmit() {
      let codeForm = this.$refs.codeForm
      let { type } = this.formDialog
      codeForm.$refs.ruleForm.validate(valid => {
        if (valid) {
          let data = { ...codeForm.ruleForm }
          if (type === 'edit') data.id = this.formDialog.rowData.id
          saveCode(data)
            .then(res => {
              this.formDialog.show = false
              this.onSearch()
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
      }
    },
    onReset() {
      this.listQuery = {
        keyword: '',
        alias: '',
      }
      this.pageInfo.page = 1
      this.getList()
    },
    onSearch() {
      this.pageInfo.page = 1
      this.getList()
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
    handleTableHead(e) {
      this.headtable = e
    },
    // 跳转分页
    handleCurrentChange(val) {
      this.pageInfo.page = val
      this.getList()
    },
    // 修改每页数据量
    handleSizeChange(val) {
      this.pageInfo.per_page = val
      this.pageInfo.page = 1
      this.getList()
    },
    getList() {
      let data = { ...this.pageInfo, ...this.listQuery }
      getCodeList(data)
        .then(res => {
          console.log(res)
          this.tableData = res.data.list
          this.pageInfo.total = res.data.total
        })
    }
  }
}
</script>

<style scoped lang="scss">
.container{
  margin: 0 10px;
}
.handle-btns{
  margin:10px 0;
  padding:20px;
  background:#fff;
  border-radius:6px 6px 0 0;
}
.base-table{
  background:#fff;
  border-radius:  0 0 6px 6px;
}
.el-form-item{
  margin-bottom:0;
}
</style>
