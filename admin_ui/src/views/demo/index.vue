<template>
  <div class="divBox">
    <div class="selCard">
      <el-form :model="query" :inline="true">
        <el-form-item prop="keyword" label="关键字">
          <el-input v-model="query.keyword" placeholder="请输入关键字" clearable></el-input>
        </el-form-item>

        <el-form-item prop="type_id" label="分类">
          <el-select v-model="query.type_id">
            <el-option :key="item.id" v-for="item in typeList" :value="item.id" :label="item.title" clearable></el-option>
          </el-select>
        </el-form-item>

        <el-form-item prop="keyword" label="时间">
          <el-date-picker
            size="mini"
            v-model="query.range"
            type="daterange"
            value-format="yyyy-MM-dd"
            range-separator="至"
            start-placeholder="开始日期"
            end-placeholder="结束日期">
          </el-date-picker>
        </el-form-item>
        <el-button size="mini" type="primary" @click="search">搜索</el-button>
      </el-form>
    </div>

    <el-card class="mt12">
      <div class="mb20">
        <el-button type="primary" icon="el-icon-plus" @click="showAddDialog" plain>添加</el-button>
        <el-button type="info" icon="el-icon-upload2" plain>导入</el-button>
        <el-button type="warning" icon="el-icon-download" plain>导出</el-button>
        <el-button type="danger" icon="el-icon-delete" plain>删除</el-button>
      </div>
      <el-table :data="tableData" highlight-current-row header-cell-class-name="table-header">
        <el-table-column label="序号" width="75" align="center">
          <template v-slot="scope">
            <span>{{ scope.$index+(paginate.page - 1) * paginate.per_page + 1 }}</span>
          </template>
        </el-table-column>
        <el-table-column prop="title" label="名称"/>
        <el-table-column prop="cover_url" label="封面">
          <template v-slot="scope">
            <el-image :src="scope.row.cover_url"  class="table-img" :preview-src-list="[scope.row.cover_url]"></el-image>
          </template>
        </el-table-column>
        <el-table-column prop="created_at" label="添加时间" :formatter="createTimeFormat"/>
        <el-table-column label="操作" min-width="100" fixed="right">
          <template v-slot="scope">
            <el-button type="text" @click="showEditDialog(scope.row.id)">编辑</el-button>
            <el-button type="text" style="color: #F56C6C" @click="delRow(scope.row.id)">删除</el-button>
          </template>
        </el-table-column>
      </el-table>

      <div class="block">
        <el-pagination background :page-size="paginate.per_page" :current-page="paginate.page" layout="total, sizes, prev, pager, next, jumper" :total="paginate.total" @size-change="handleSizeChange" @current-change="pageChange" />
      </div>
    </el-card>

    <el-dialog
      title="管理"
      :visible.sync="addDialog.show"
      width="800px"
      :close-on-click-modal="false"
      :close-on-press-escape="false"
    >
      <add-form ref="addForm" :id="addDialog.id" :row-data="addDialog.form" v-if="addDialog.show"></add-form>
      <div slot="footer" class="dialog-footer" align="center">
        <el-button @click="addDialog.show = false">取 消</el-button>
        <el-button type="primary" @click="saveApi()">确 定</el-button>
      </div>
    </el-dialog>
  </div>
</template>
<script>

import { demoDel, demoIndex, demoInfo, demoStore, demoType} from "@/api/demo";
import AddForm from "./component/AddForm";
import {deepClone} from "@/utils";
import {TimeFormat} from "@/utils/common";

export default {
  name: "DemoIndex",
  components: {AddForm},

  data() {
    return {
      loading: false,
      // 查询参数
      query: {
        keyword: "",
        type_id: null,
        range: [],
      },
      // 分页参数
      paginate: {
        per_page: 10,
        page: 1,
        total: 0
      },
      // 表格数据
      tableData: [],
      // 下拉选项
      typeList: [],
      // 添加dialog
      addDialog: {
        show: false,
        form: {},
      }
    }
  },

  created() {
    // 查询搜索条件下拉值
    this.getTypeOption()

    // 查询列表数据
    this.getList()
  },

  methods: {
    search() {
      this.paginate.page = 1
      this.getList()
    },

    getList() {
      let obj = Object.assign(this.query, {
        page: this.paginate.page,
        per_page: this.paginate.per_page
      })
      demoIndex(obj).then(res => {
        this.tableData = res.data.list
        this.paginate.total = res.data.total
      })
    },

    // 查询下拉选项值
    getTypeOption() {
      demoType().then(res => {
        this.query.type_list = res.data.list
      })
    },

    pageChange(page) {
      this.paginate.page = page
      this.getList()
    },

    handleSizeChange(val) {
      this.paginate.per_page = val
      this.getList()
    },

    showAddDialog() {
      this.addDialog.form = {}
      this.addDialog.show = true
    },

    showEditDialog(id) {
      demoInfo({id}).then(res => {
        this.addDialog.form = res.data
        this.addDialog.show = true
      }).catch(e => {
        this.$message.error(e.message || '操作失败')
      })
    },
    // 保存
    saveApi() {
      const AddEditForm = this.$refs.addForm

      AddEditForm.$refs.addFormComponent.validate(valid => {
        if (valid) {
          const obj = deepClone(AddEditForm.form)
          if (this.addDialog.id) {
            obj.id = this.addDialog.rowData.id
          }
          demoStore(obj).then(res => {
            this.addDialog.show = false
            this.$message.success(res.msg || '请求成功')
            this.getList()
          }).catch(res => {
            this.$message.error(res.message || '请求出错')
          })
        }
      })
    },

    delRow(id) {
      this.$modalSure('确认删除此行数据吗').then(() => {
        demoDel({id}).then(res => {
          this.$message.success("删除成功")
          this.getList()
        }).catch(e => {
          this.$message.error(e.message || '操作失败')
        })
      })
    },

    createTimeFormat(row, column, cellValue, index) {
      return TimeFormat(cellValue)
    }
  }
}
</script>
