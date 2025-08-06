<!--
 * @Description: 字典管理
 * @version:
 * @Author: smy
 * @Date: 2023-10-10 08:30:57
 * @LastEditors: smy
 * @LastEditTime: 2023-10-10 08:49:20
-->
<template>
  <div class="container flex flex-sb">
    <div class="left-tree"></div>
    <!-- <div class="right-content">
      <div class="handle-btns flex flex-sb">
        <div>
          <el-button type="primary" @click="showChangeDialog('add')">新增</el-button>
          <el-button type="primary" @click="importTable">导入</el-button>
          <el-button type="primary" @click="exportTable">导出</el-button>
        </div>
        <div class="flex flex-sb">
          <el-input
            style="margin-right: 10px"
            v-model="keyword"
            placeholder="请输入关键字进行过滤"
          ></el-input>
          <el-button type="primary" @click="getDeptList">搜索</el-button>
        </div>
      </div>
      <el-table
        :data="tableData"
        v-if="tableMaxH"
        :max-height="tableMaxH"
        border
        style="width: 100%"
      >
        <el-table-column label="序号" align="center" width="80">
          <template slot-scope="scope">
            <div>{{ (pageInfo.page - 1) * pageInfo.per_page + scope.$index + 1 }}</div>
          </template>
        </el-table-column>
        <el-table-column prop="title" label="部门名称" width="180"></el-table-column>
        <el-table-column prop="sort_id" label="排序" align="center" width="80"></el-table-column>
        <el-table-column prop="owner" label="负责人"></el-table-column>
        <el-table-column prop="status" label="状态" align="center" width="80">
          <template slot-scope="scope">
            <el-tag :type="scope.row.status === 0 ? 'danger' : 'default'" size="medium">{{
              scope.row.status === 0 ? '禁用' : '启用'
            }}</el-tag>
          </template>
        </el-table-column>
        <el-table-column
          prop="create_time"
          label="创建时间"
          align="center"
          width="200"
        ></el-table-column>
        <el-table-column label="操作" align="center" width="150">
          <template slot-scope="scope">
            <el-button type="text" size="small" @click="showChangeDialog('edit', scope.row)"
              >编辑</el-button
            >
            <el-popconfirm title="确定要删除此部门么？" @onConfirm="delDept(scope.row)">
              <el-button
                type="text"
                style="color: #f56c6c; margin-left: 10px"
                size="small"
                slot="reference"
                >删除</el-button
              >
            </el-popconfirm>
          </template>
        </el-table-column>
      </el-table>
      <div class="block">
        <el-pagination
          @size-change="handleSizeChange"
          @current-change="handleCurrentChange"
          :current-page="pageInfo.currentPage"
          :page-sizes="[10, 20, 50, 100]"
          :page-size="10"
          layout="total, sizes, prev, pager, next, jumper"
          :total="pageInfo.total"
        >
        </el-pagination>
      </div>
    </div>
    <el-dialog
      width="500px"
      :title="changeDialog.isAdd ? '新增' : '编辑'"
      :visible.sync="changeDialog.show"
    >
      <add-edit-form
        :isAdd="changeDialog.isAdd"
        :row="changeDialog.rowData"
        ref="AddEditForm"
        v-if="changeDialog.show"
      ></add-edit-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="changeDialog.show = false">取 消</el-button>
        <el-button type="primary" @click="handleDept">确 定</el-button>
      </div>
    </el-dialog> -->
  </div>
</template>

<script>
import { deepClone } from '@/utils'

export default {
  name: 'DeptManage',
  components: {
    // AddEditForm
  },
  data() {
    return {
      tableTypes: {
        qypp: {
          label: '企业品牌',
          id: 1
        },
        zcgg: {
          label: '资产规格',
          id: 1
        },
        zcxh: {
          label: '资产型号',
          id: 1
        },
        zcdw: {
          label: '资产单位',
          id: 1
        },
        zczt: {
          label: '资产状态',
          id: 1
        },
        htgg: {
          label: '合同规格',
          id: 1
        },
        jhlx: {
          label: '计划类型',
          id: 1
        }
      },
      corpTree: [], // 集团树的数据结构
      // 树形结构配置
      treeProps: {
        children: 'child',
        label: 'title'
      },
      currentNodeKey: '', // 当前选中的节点的id
      // 分页信息
      pageInfo: {
        page: 1,
        per_page: 10,
        total: 0,
        currentPage: 1 // 当前选择的页码
      },
      keyword: '', // 搜索关键字
      tableData: [], // 表格数据
      tableMaxH: '', // 表格最大高度
      changeDialog: {
        show: false,
        isAdd: true, // 是否是新增
        rowData: {} // 选择行的数据
      }
    }
  },
  created() {
    // 获取集团树数据
  },
  mounted() {
    // let appMainH = document.getElementsByClassName('app-main')[0].clientHeight
    // let toolsH = document.getElementsByClassName('handle-btns')[0].clientHeight
    // let paginationH = document.getElementsByClassName('block')[0].clientHeight
    // this.tableMaxH = appMainH - 40 - toolsH - 20 - paginationH
  },
  methods: {
    // 点击节点的回调(调用获取列表接口)
    handleCropClick(data, node) {
      if (this.currentNodeKey === node.id) return
      this.currentNodeKey = node.id
      this.getDeptList()
    },
    getDeptList() {
      let { page, per_page } = this.pageInfo
      getDeptList({
        page,
        per_page,
        pid: this.currentNodeKey,
        keyword: this.keyword
      }).then(res => {
        this.pageInfo.total = res.data.total
        this.tableData = [...res.data.list]
      })
    },
    // 显示更改部门弹窗: type = add 新增; type = edit 编辑
    showChangeDialog(type, row) {
      if (!type) return
      if (type === 'add') this.changeDialog.isAdd = true
      else {
        this.changeDialog.isAdd = false
        this.changeDialog.rowData = row
      }
      this.changeDialog.show = true
    },
    // 导入
    importTable() {},
    // 导出
    exportTable() {},
    // 更改部门方法 (新增或者删除)
    handleDept() {
      // console.log(this.$refs.AddEditForm.$refs.addForm)
      let AddEditForm = this.$refs.AddEditForm
      AddEditForm.$refs.addForm.validate(valid => {
        if (valid) {
          console.log(AddEditForm.form)
          let { isAdd } = this.changeDialog
          let obj = deepClone(AddEditForm.form)
          obj.pid = this.currentNodeKey // 设置上级id
          if (!isAdd) obj.id = this.changeDialog.rowData.id
          handleDeptReq(obj).then(res => {
            if (res.code === 200) {
              this.changeDialog.show = false
              this.$message.success(res.msg || '请求成功')
              this.getDeptList()
            }
          })
        } else return false
      })
    },
    // 删除部门
    delDept(row) {
      deleteDept(row.id).then(res => {
        if (res.code === 200) {
          this.$message.success(res.msg || '请求成功')
          this.getDeptList()
        }
      })
    },
    // 修改每页数据量
    handleSizeChange(val) {
      this.pageInfo.per_page = val
      this.pageInfo.page = 1
      this.getDeptList()
    },
    // 跳转分页
    handleCurrentChange(val) {
      this.pageInfo.page = val
      this.getDeptList()
    }
  }
}
</script>

<style lang="scss" scoped>
::v-deep .left-tree {
  .el-tree-node__label {
    font-size: 15px !important;
  }
}
.container {
  height: 100%;
  .left-tree {
    width: 300px;
    margin-right: 20px;
    height: 100%;
    border: 2px solid #ccc;
    padding: 10px;
    border-radius: 8px;
  }
  .right-content {
    height: 100%;
    width: calc(100% - 300px);
  }
}
.block {
  padding: 10px;
  text-align: center;
}
</style>
