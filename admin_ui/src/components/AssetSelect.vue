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
            <el-input style="width: 100%" v-model="query.keyword" placeholder="请输入资产名称" size="small"></el-input>
          </el-col>
          <el-col :span="5">
            <el-input style="width: 100%" v-model="query.asset_sn" placeholder="请输入资产编号" size="small"></el-input>
          </el-col>
          <el-col :span="2">
            <el-button type="success" icon="el-icon-search" size="small" @click="search">查询</el-button>
          </el-col>
        </el-row>
        <el-row>
          <base-table
            :tableHead="header"
            :tableData="assetList"
            :customColumnTableHead="diy"
            :row-formatter="assetFormatter"
            :tableSize="tableSize"
            :pagingData="pageInfo"
            :showCheckbox="true"
            :showPagination="true"
            :isSingle="isSingle"
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

import BaseTable from "@/components/BaseTable.vue";
import {assetTableHead} from "./header_fields";
import {getAssetList, getAssetTree} from "@/api/asset";
import {left} from "core-js/internals/array-reduce";

export default {
  name: "AssetSelect",
  props: {
    isSingle: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      diy: false,
      query: {
        type_id: "",
        keyword: "",
        asset_sn: ""
      },
      tableSize: "small",
      treeProps: {
        children: 'child',
        label: 'title'
      },
      treeList: [],
      assetList: [],
      header: assetTableHead,
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
    getAssetTree().then(res => {
      this.treeList = [...res.data]
    })
    this.assetIndex()
  },

  methods: {
    left,
    assetIndex() {
      getAssetList(Object.assign({}, this.query,this.pageInfo)).then(res => {
        this.assetList = res.data.list
        this.pageInfo.page = res.data.current_page
        this.pageInfo.total = res.data.total
      })
    },

    search() {
      this.pageInfo.page = 1
      this.assetIndex()
    },
    handleAssetTypeClick(data, node, item) {
      this.query.type_id = this.$refs.typeTree.getCheckedKeys().join(',')
    },

    handleAssetSize(per_page) {
      this.pageInfo.per_page = per_page
      this.assetIndex()
    },

    handleAssetCurrentSize(pageIndex) {
      this.pageInfo.page = pageIndex
      this.assetIndex()
    },

    handleSelectionChange(selectObj) {
      if (!this.isSingle) {
        this.selectIds = []
        selectObj.forEach((item, index, arr) => {
          this.selectIds.push(item.id)
        })

        this.$emit("handleSelectionChange", this.selectIds)
      } else {
        this.$emit("handleSelectionChange", selectObj)
      }
    },

    // 格式化
    assetFormatter(row, column, cellValue, index)
    {
      // 资产归属类型 1/2/3/4 ///企业单位资产
      if (typeof cellValue === 'undefined') return

      if (column.property == 'owner_type') {
        if (cellValue == 1) {
          return (<el-tag type="success">自有资产</el-tag>);
        }
        if (cellValue == 2) {
          return (<el-tag type="info">托管资产</el-tag>);
        }
        if (cellValue == 3) {
          return (<el-tag type="warning">事业单位资产</el-tag>);
        }

        return (<el-tag>事业单位资产</el-tag>);
      }

      // 处置方式 status 0-合资合作、1-出租、2-出售、3-合作加出租、4-合作加出售、5出租加出售、6合作加出租加出售
      if (column.property == 'status') {
        let statusObj = {
          0: "合资合作",
          1: "出租",
          2: "出售",
          3: "合作加出租",
          4: "合作加出售",
          5: "5出租加出售",
          6: "6合作加出租加出售"
        }

        return statusObj[cellValue];
      }

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
