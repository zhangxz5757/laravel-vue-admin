<template>
  <div>
    <el-row :gutter="18">
      <el-col :span="4" class="type_box">
        <el-tree ref="typeTree"
                 show-checkbox
                 :data="treeList"
                 @check-change="handleAssetTypeClick"
                 node-key="id"
                 :props="treeProps">
        </el-tree>
      </el-col>

      <el-col :span="20">
        <el-row style="margin-bottom: 12px;" :gutter="8">
          <el-col :span="5">
            <el-input style="width: 100%" v-model="query.keyword" placeholder="请输入人员名称" size="small"></el-input>
          </el-col>
          <el-col :span="2">
            <el-button type="primary" size="small" @click="search">查询</el-button>
          </el-col>
        </el-row>
        <el-row>
          <base-table
            :tableHead="header"
            :tableData="tableData"
            :customColumnTableHead="false"
            :row-formatter="assetFormatter"
            :tableSize="tableSize"
            :pagingData="pageInfo"
            :showCheckbox="true"
            :showPagination="true"
            @handleSizeChange="handleAssetSize" @handleCurrentChange="handleAssetCurrentSize"
            @handleSelectionChange="handleSelectionChange"
          >


          </base-table>

        </el-row>

      </el-col>


    </el-row>

  </div>
</template>

<script>

import {getCorpTree} from "@/api/corp";
import {staffTableHead} from "./header_fields";
import {getList} from "@/api/staff";

export default {
  name: "StaffSelect",
  data() {
    return {
      diy: false,
      query: {
        corp_id: "",
        keyword: "",
        asset_sn: ""
      },
      tableSize: "small",
      treeProps: {
        children: 'child',
        label: 'title'
      },
      treeList: [],
      tableData: [],
      header: staffTableHead,
      pageInfo: {
        page: 1,
        per_page: 15,
        total: 0,
        current_page: 1
      },
      selectIds: []
    };
  },

  created() {
    getCorpTree().then(res => {
      this.treeList = [...res.data]
    })
    this.tableIndex()
  },

  methods: {
    tableIndex() {
      getList('/admin/manager/index', Object.assign({}, this.query,this.pageInfo)).then(res => {
        this.tableData = res.data.list
        this.pageInfo.page = res.data.current_page
        this.pageInfo.total = res.data.total
      })
    },

    search() {
      this.pageInfo.page = 1
      this.tableIndex()
    },
    handleAssetTypeClick(data, node, item) {
      this.query.corp_id = this.$refs.typeTree.getCheckedKeys().join(',')
    },

    handleAssetSize(per_page) {
      this.pageInfo.per_page = per_page
      this.tableIndex()
    },
    handleAssetCurrentSize(pageIndex) {
      this.pageInfo.page = pageIndex
      this.tableIndex()
    },

    handleSelectionChange(selectObj) {
      this.selectIds = []
      selectObj.forEach((item, index, arr) => {
        this.selectIds.push(item.id)
      })

      this.$emit("handleSelectionChange", this.selectIds)
    },

    // 格式化
    assetFormatter(row, column, cellValue, index)
    {
      if (column.property == 'is_important') {
        return cellValue == 1 ? '是' : "否";
      }

      return cellValue
    },
  }
}
</script>


<style scoped lang="scss">

</style>
