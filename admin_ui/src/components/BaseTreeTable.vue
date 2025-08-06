/* eslint-disable */
<template>
  <div class="base-table">
    <div v-if="customColumnTableHead" style="margin:5px 0 6px 0;float: right">
      <el-popover
        v-model="customColumnDialogVisible"
        placement="left"
        width="200"
        trigger="manual"
      >
        <el-checkbox-group v-model="columnSelectList">
          <el-checkbox v-for="(item, index) in checkList" :key="index" :checked="item.show !== false " :label="item.label" @change="handleCheckChange">{{ item.label }}</el-checkbox>
        </el-checkbox-group>
        <div class="flex" style="margin-top: 20px">
          <el-button size="mini" @click="customColumnDialogVisible=false">取 消</el-button>
          <el-button size="mini" type="primary" @click="onSubmit">确 定</el-button>
        </div>
        <el-button class="diy" slot="reference" type="text" @click="handleCustom"><i class="el-icon-setting" />自定义展示</el-button>
      </el-popover>
    </div>
    <el-table :ref="dropRef" :max-height="maxHeight" :data="list" border style="width: 100%" :size="tableSize" @selection-change="handleSelectionChange" :row-key="rowKey" :tree-props="treeProps">
      <el-table-column
        v-if="showCheckbox"
        type="selection"
        width="55">
      </el-table-column>

      <el-table-column
        v-if="showIndex"
        :width="indexWidth"
        label="序号"
        type="index"
        :align="align"
        :index="indexMethod"
        :fixed="indexFixed"
      />
      <el-table-column
        v-for="item in customHead"
        :key="item.key + item.label"
        :align="item.align || 'left'"
        :label="item.label"
        :prop="item.key"
        :formatter="item.formatter ? rowFormatter : defaultFormatter"
        :width="item.width || null"
      />
      <slot name="handles" />
    </el-table>
    <div v-if="showPagination" class="pagination">
      <el-pagination
        background
        :class="{ centerLayout: isPagingCenter }"
        :current-page.sync="pagingData.current_page"
        :page-sizes="[10, 15, 20, 50, 100]"
        :page-size="pagingData.per_page"
        layout="total, sizes, prev, pager, next, jumper"
        :total="pagingData.total"
        @size-change="handleSizeChange"
        @current-change="handleCurrentChange"
      />
    </div>
    <!--    <el-dialog title="自定义展示" :visible.sync="customColumnDialogVisible" :show-close="false" :modal-append-to-body="false">-->
    <!--      <el-checkbox-group v-model="columnSelectList">-->
    <!--        <el-checkbox v-for="(item, index) in tableHead" :key="index" :checked="item.show !== false " :label="item.label" @change="handleCheckChange">{{ item.label }}</el-checkbox>-->
    <!--      </el-checkbox-group>-->
    <!--      <span slot="footer" class="dialog-footer">-->
    <!--        <el-button @click="customColumnDialogVisible=false">取 消</el-button>-->
    <!--        <el-button type="primary" @click="onSubmit">确 定</el-button>-->
    <!--      </span>-->
    <!--    </el-dialog>-->
  </div>
</template>

<script>
export default {
  name: 'BaseTreeTable',
  components: {},
  props: {
    changeToTop: {
      type: Boolean,
      default: false
    },
    tableSize: {
      type: String,
      default: "mini"
    },
    dropRef: {
      type: String,
      default: 'el_table'
    },
    customColumnTableHead: {
      // 是否定制列
      type: Boolean,
      default: false
    },
    maxHeight: {
      type: Number,
      default: null
    },
    indexWidth: {
      type: Number,
      default() {
        return 50
      }
    },
    border: {
      // 分割线
      type: Boolean,
      default() {
        return true
      }
    },
    tableHead: {
      // 表头数据
      type: Array,
      default() {
        return []
      }
    },
    tableData: {
      // 表格数据
      type: Array,
      default() {
        return []
      }
    },
    showPagination: {
      // 是否分页
      type: Boolean,
      default: true
    },
    pagingData: {
      // 默认分页
      type: Object,
      default() {
        return {
          current_page: 1, // 当前页码
          per_page: 20, // 每页大小
          pages: 1, // 总页数
          total: 0 // 总记录树
        }
      }
    },
    isPagingCenter: {
      // 分页居中
      type: Boolean,
      default: true
    },
    height: {
      // 表格高度
      type: String,
      default: null
    },
    stripe: {
      // 是否显示斑马纹
      type: Boolean,
      default: false
    },
    showHeader: {
      // 是否显示表头
      type: Boolean,
      default: true
    },
    align: {
      // 表格对齐方式
      type: String,
      default: 'center'
    },
    showCheckbox: {
      type: Boolean,
      default: false
    },
    showIndex: {
      // 是否显示索引
      type: Boolean,
      default: true
    },
    showAction: {
      // 是否显示操作栏
      type: Boolean,
      default: true
    },
    indexFixed: {
      type: Boolean,
      default() {
        return false
      }
    },
    rowFormatter: {
      type: Function,
      default() {
        return {
          children: 'children',
          hasChildren: 'hasChildren'
        }
      }
    },
    treeProps: {
      type: Object,
      default() {}
    },
    rowKey: {
      type: String,
      default: 'id'
    }
  },
  data() {
    return {
      loading: true,
      customData: null,
      customColumnDialogVisible: false,
      columnSelectList: [],
      selectIds : []
      // oldTableHead: this.tableHead
    }
  },
  computed: {
    list() {
      return this.tableData
      /*return [{
        id: 1,
        date: '2016-05-02',
        name: '王小虎',
        address: '上海市普陀区金沙江路 1518 弄'
      }, {
        id: 2,
        date: '2016-05-04',
        name: '王小虎',
        address: '上海市普陀区金沙江路 1517 弄'
      }, {
        id: 3,
        date: '2016-05-01',
        name: '王小虎',
        address: '上海市普陀区金沙江路 1519 弄',
        children: [{
          id: 31,
          date: '2016-05-01',
          name: '王小虎',
          address: '上海市普陀区金沙江路 1519 弄'
        }, {
          id: 32,
          date: '2016-05-01',
          name: '王小虎',
          address: '上海市普陀区金沙江路 1519 弄'
        }]
      }, {
        id: 4,
        date: '2016-05-03',
        name: '王小虎',
        address: '上海市普陀区金沙江路 1516 弄'
      }]*/
    },
    customHead() {
      return this.tableHead.filter(ele => ele.show !== false)
    },
    routerKey() {
      return this.$route.path
    },
    checkList() {
      return this.tableHead
    }
  },
  watch: {
    tableData: {
      deep: true,
      handler: function() {
        this.$refs[this.dropRef].doLayout()
      }
    },
    tableHead: {
      deep: true,
      handler: function(val) {
        this.$refs[this.dropRef].doLayout()
      }
    }
  },
  created() { },
  mounted() {
    this.loading = false
  },
  activated() {
    if (this.$refs[this.dropRef]) this.$refs[this.dropRef].doLayout()
  },
  methods: {
    handleCustom() {
      this.customColumnDialogVisible = true
    },
    onSubmit() {
      // 不展示列表
      const filterList = this.checkList.filter(item => !this.columnSelectList.some(sub => sub === item.label)).map(ele => ele.key)
      const head_list = this.checkList.map(ele => {
        return filterList.includes(ele.key) ? { ...ele, show: false } : { ...ele, show: true }
      })
      this.$emit('handleTableHead', head_list)
      localStorage.setItem(this.routerKey, JSON.stringify({ head: head_list }))
      this.customColumnDialogVisible = false
      // this.tableDoLayout()
    },
    tableDoLayout() {
      if (this.$refs[this.dropRef]) this.$refs[this.dropRef].doLayout()
    },
    defaultFormatter(row, column, cellValue, index) {
      if (typeof cellValue === 'undefined') return
      else {
        if (!cellValue) {
          if (cellValue === 0) return 0
          else return '--'
        } else return cellValue
      }
    },
    handleCheckChange(e) {
      // console.log(e)
    },
    handleSizeChange(pageSize) {
      this.$emit('handleSizeChange', pageSize)
    },

    handleSelectionChange(selectIds) {
      this.$emit('handleSelectionChange', selectIds)
    },
    handleCurrentChange(pageIndex) {
      this.$emit('handleCurrentChange', pageIndex)
      if (this.changeToTop === true) {
        scrollTo(0, 0) // 回到顶部
      }
    },
    indexMethod(index) {
      return index + 1
    }
  }
}
</script>

<style lang="scss" scoped>
.diy{
  margin-right:10px;
}
.base-table {
  ::v-deep .el-table__body .el-table__row.hover-row td {
    background-color:#DCE9FC !important;
  }
  .content-wrap {
    display: inline-flex;
    justify-content: center;
    align-items: center;
    .tag {
      margin-left: 8px;
      padding: 4px 8px;
      border: 1px solid;
      border-radius: 4px;
      font-size: 12px;
      line-height: 12px;
    }
    .copy {
      margin-left: 10px;
      width: 16px;
      height: 16px;
      cursor: pointer;
    }
  }
  .content-div {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  .imageBox {
    .el-image {
      width: 50px;
      height: 50px;
      display: inline-block;
      vertical-align: middle;
    }
  }
  .pagination {
    margin-top: 20px;
    .el-pagination.centerLayout {
      display: flex;
      justify-content: center;
    }
  }
}
</style>
