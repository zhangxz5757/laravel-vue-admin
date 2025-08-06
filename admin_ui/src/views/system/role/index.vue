<template>
  <div class="divBox">
    <div class="selCard">
      <el-form :model="query" :inline="true" label-width="60px">
        <el-form-item prop="keyword" label="关键字">
          <el-input v-model="query.keyword" placeholder="请输入关键字" clearable></el-input>
        </el-form-item>

        <el-form-item prop="status" label="状态">
          <el-select v-model="query.status" clearable>
            <el-option :key="item.id" v-for="item in statusList" :value="item.id" :label="item.title"></el-option>
          </el-select>
        </el-form-item>
      </el-form>
    </div>

    <el-card class="mt10">
      <div class="mb20">
        <el-button icon="el-icon-search" @click="search" plain>搜索</el-button>
        <el-button type="primary" icon="el-icon-plus" @click="showAddDialog">添加</el-button>
      </div>

      <el-table :data="tableData" highlight-current-row header-cell-class-name="table-header">
        <el-table-column label="序号" width="85" align="center">
          <template v-slot="scope">
            <span>{{ scope.$index+(paginate.page - 1) * paginate.per_page + 1 }}</span>
          </template>
        </el-table-column>
        <el-table-column prop="title" label="角色名称"/>
        <el-table-column prop="description" label="角色描述"/>
        <el-table-column prop="status" label="状态">
          <template v-slot="scope">
            <el-tag v-if="scope.row.status == 1" type="success">启用</el-tag>
            <el-tag v-else type="danger">禁用</el-tag>
          </template>
        </el-table-column>
        <el-table-column prop="created_at" label="添加时间" :formatter="createTimeFormat"/>
        <el-table-column label="操作" min-width="100" fixed="right">
          <template v-slot="scope">
            <el-button type="text" size="small" @click="showEditDialog(scope.row.id)">编辑</el-button>
            <el-button type="text" size="small" style="color: #F56C6C" @click="delRow(scope.row.id)">删除</el-button>
          </template>
        </el-table-column>
      </el-table>

      <div class="block">
        <el-pagination background :page-size="paginate.per_page" :current-page="paginate.page" layout="total, sizes, prev, pager, next, jumper" :total="paginate.total" @size-change="handleSizeChange" @current-change="pageChange" />
      </div>
    </el-card>

    <el-dialog
      title="角色管理"
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
import { getRolesList, storeRole, deleteRole, roleInfo } from '@/api/system/role'
import { deepClone } from '@/utils'
import AddForm from "@/views/system/role/components/AddForm.vue";
import { TimeFormat } from "@/utils/common";
import { RoleStatusList } from "@/views/system/role/options";
export default {
  name: 'SysRole',
  components: {
    AddForm,
  },
  data() {
    return {
      loading: false,
      // 查询参数
      query: {
        keyword: "",
        status: null,
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
      statusList: RoleStatusList,
      // 添加dialog
      addDialog: {
        show: false,
        form: {},
      }
    }
  },
  created() {
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
      getRolesList(obj).then(res => {
        this.tableData = res.data.list
        this.paginate.total = res.data.total
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
      roleInfo({id}).then(res => {
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
          storeRole(obj).then(res => {
            this.$message.success(res.msg || '请求成功')
            this.addDialog.show = false
            this.getList()
          }).catch(res => {
            this.$message.error(res.message || '请求出错')
          })
        }
      })
    },

    delRow(id) {
      this.$modalSure('确认删除此行数据吗').then(() => {
        deleteRole({id}).then(res => {
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

<style lang="scss" scoped>
</style>
