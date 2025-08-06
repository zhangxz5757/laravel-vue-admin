<template>
  <div class="container">
    <div class="handle-btns">
      <el-button icon="el-icon-refresh" @click="getList" plain>刷新</el-button>
      <el-button type='primary' size="mini" icon="el-icon-plus" @click="showDialog('add')">添加</el-button>
    </div>

    <base-tree-table :tree-props="treeProps" :table-head="headTable" :table-data="tableData" :row-formatter="colFormatter" :show-pagination='false'>
      <template slot="handles">
        <el-table-column label="操作" align="center" :fixed="'right'">
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
    </base-tree-table>
    <el-dialog
      title="菜单管理"
      :visible.sync="menuForm.show"
      width="800px"
      :close-on-click-modal="false"
      :close-on-press-escape="false"
    >
      <menu-form
        v-if="menuForm.show"
        ref="menuForm"
        :rowData="menuForm.rowData"
        :tree-data="treeData"
        :type="menuForm.type"
      />
      <div slot="footer" class="dialog-footer">
        <el-button @click="menuForm.show = false">取 消</el-button>
        <el-button type="primary" @click="handleSubmit">确 定</el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import { deleteMenu, getMenuList, saveMenu } from '@/api/system/menu'
import BaseTreeTable from '@/components/BaseTreeTable.vue'
import { affixOptions, hiddenOptions, menuTableHeader, typeOptions } from '@/views/system/menu/options'
import MenuForm from '@/views/system/menu/components/AddForm.vue'

export default {
  name: "SysMenu",
  components: { MenuForm, BaseTreeTable },
  data() {
    return {
      tableData: [],
      treeData: [],
      treeProps: {
        children: 'child',
        hasChildren: 'hasChildren'
      },
      headTable: [],
      menuForm: {
        show: false,
        rowData: {}
      }
    }
  },
  created() {
    this.headTable = menuTableHeader
    this.getList()
  },
  methods: {
    colFormatter(row, column, cellValue, index) {
      if (column.property === 'affix') {
        affixOptions.map(item => {
          if (cellValue == item.value) cellValue = item.label
        })
      }
      if (column.property === 'hidden') {
        hiddenOptions.map(item => {
          if (cellValue == item.value) cellValue = item.label
        })
      }
      if (column.property === 'type') {
        typeOptions.map(item => {
          if (cellValue == item.value) cellValue = item.label
        })
      }
      if (column.property === 'icon') {
        return (
          <i class={cellValue} />
        )
      }
      return cellValue
    },

    handleSubmit() {
      let menuForm = this.$refs.menuForm
      let { type } = this.menuForm
      menuForm.$refs.ruleForm.validate(valid => {
        let data = menuForm.ruleForm
        data.component = data.component ? data.component : 'Layout'
        data.pid = data.pid ? data.pid : 0
        if (type == 'edit') data.id = this.menuForm.rowData.id
        saveMenu(data)
          .then(res => {
            this.menuForm.rowData = {}
            this.menuForm.show = false
            this.getList()
          })
      })
    },

    showDialog(type, row) {
      this.menuForm.type = type
      switch (type) {
        case 'add':
          this.menuForm.title = '新增'
          this.menuForm.show = true
          break
        case 'edit':
          this.menuForm.rowData = { ...row }
          this.menuForm.title = '编辑'
          this.menuForm.show = true
          break
        case 'del':
          deleteMenu({ id: row.id })
            .then(res => {
              this.getList()
            })
      }
    },
    dataSort(list) {
      if (list.length) {
        if (list.child) {
          this.dataSort(item.child)
        }
        list.sort(function(a, b) {
          return a.sort_id - b.sort_id
        })
      }
    },
    getList() {
      getMenuList()
        .then(res => {
          if (res.code == 200) {
            this.dataSort(res.data)
            this.tableData = res.data
            this.treeData = res.data
          }
        })
    }
  }
}
</script>

<style scoped lang="scss">
</style>
