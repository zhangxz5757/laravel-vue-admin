<template>
  <div class="divBox">

    <el-card>
      <div class="mb10">
        <el-button icon="el-icon-refresh" @click="getList" plain>刷新</el-button>
        <el-button type="primary" icon="el-icon-plus" @click="showAddDialog">新增</el-button>
      </div>

      <el-table
          size="mini"
          :data="tableData"
          row-key="id"
          border
          :default-expand-all="true"
          :tree-props="{children: 'children', hasChildren: 'hasChildren'}">
        >
        <el-table-column prop="title" label="部门名称" :show-overflow-tooltip="true"></el-table-column>
        <el-table-column prop="sort_id" label="排序" align="center" :show-overflow-tooltip="true"></el-table-column>
        <el-table-column prop="status" label="状态" align="center">
          <template slot-scope="scope">
            <el-tag v-if="scope.row.status == 1" type="success">启用</el-tag>
            <el-tag v-else type="danger">禁用</el-tag>
          </template>
        </el-table-column>
        <el-table-column
            prop="create_time"
            label="创建时间"
            align="center"
            width="200"
        ></el-table-column>
        <el-table-column label="操作" align="center">
          <template slot-scope="scope">
            <el-button type="text" @click="showEditDialog(scope.row.id)">编辑</el-button>
            <el-button type="text" style="color: #f56c6c; margin-left: 10px" slot="reference" @click="delRow(scope.row.id)">删除</el-button>
          </template>
        </el-table-column>
      </el-table>
    </el-card>

    <el-dialog
        width="600px"
        title="部门管理"
        :visible.sync="addDialog.show"
    >
      <add-form
          ref="addForm"
          :rowData="addDialog.form"
          v-if="addDialog.show"
      ></add-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="addDialog.show = false">取 消</el-button>
        <el-button type="primary" @click="saveApi">确 定</el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import {deepClone} from '@/utils'
import {
  getDeptList,
  sysDeptInfo,
  storeSysDept,
  deleteSysDept
} from '@/api/system/department'
import AddForm from './components/AddForm.vue'

export default {
  name: 'SysDepartment',
  components: {
    AddForm
  },
  data() {
    return {
      // 分页信息
      pageInfo: {
        page: 1,
        per_page: 10,
        total: 0
      },
      query: {
        keyword: "",
      },
      // 搜索关键字
      tableData: [], // 表格数据

      addDialog: {
        show: false,
        form: {} // 选择行的数据
      },
    }
  },
  created() {
    this.getList()
  },
  methods: {

    getList() {
      getDeptList().then(res => {
        this.tableData = res.data.list
      })
    },

    showAddDialog() {
      this.addDialog.form = {}
      this.addDialog.show = true
    },

    showEditDialog(id) {
      sysDeptInfo({id}).then(res => {
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
          storeSysDept(obj).then(res => {
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
        deleteSysDept(id).then(res => {
          this.$message.success("删除成功")
          this.getList()
        }).catch(e => {
          this.$message.error(e.message || '操作失败')
        })
      })
    },
  }
}
</script>

<style lang="scss" scoped>
</style>
