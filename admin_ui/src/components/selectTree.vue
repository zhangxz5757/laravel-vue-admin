<template>
  <div class="selectTree">
    <el-select class="main-select-tree" ref="selectTree" multiple v-model="transitValue" @change="changeVal">
      <el-option v-for="item in selectOptions" :key="item.id" :label="item.title" :value="item.id" style="display: none;" />
      <el-tree class="main-select-el-tree" ref="selecteltree" :check-strictly="true" @check-change="handleCheckChange" show-checkbox :highlight-current="true" :data="dataArray" :props="defaultProps" :expand-on-click-node="false" node-key="id" @node-click="handleNodeClick" :current-node-key="currentKey" :default-expanded-keys="[value]" />
    </el-select>
  </div>
</template>
<script>
export default {
  name: 'selectTree',
  props: {
    dataArray: Array,
    value: [Number, String]
  },
  data () {
    return {
      transitValue: '',
      selectOptions: [],
      currentKey: null,
      defaultProps: {
        label: 'title',
        children: 'child'
      }
    }
  },
  computed: {
    formatData () {
      let result = []
      function getChild (item) {
        item.forEach((i, x) => {
          if (Array.isArray(i['children'])) {
            result.push(i)
            getChild(i['children'])
          } else {
            result.push(i)
          }
        })
      }
      getChild(this.dataArray)
      return result
    }
  },
  methods: {
    changeVal(val) {
      this.$emit('treeChange', val)
    },
    handleCheckChange(data, checked, indeterminate) {
      let arr = this.$refs.selecteltree.getCheckedNodes()
      let ids = []
      if (arr.length) {
        arr.map(item => {
          ids.push(item.id)
        })
      }
      this.transitValue = ids
      this.$emit('treeChange', ids)
    },
    handleNodeClick (node) {
      /*this.$emit('treeChange', node)
      console.log(node)
      this.transitValue = node.title
      this.$refs.selectTree.blur()*/
    }
  },
  watch: {
    formatData (n) {
      if (n.length > 0) {
        let arr = []
        let checkChild = (child) => {
          if (child.length) {
            child.map(item => {
              if (item.child) {
                checkChild(item.child)
              }
              arr.push(item)
            })
          }
        }
        n.map(item => {
          arr.push(item)
          if (item.child) {
            checkChild(item.child)
          }
        })
        this.selectOptions = arr
      } else {
        this.selectOptions = []
      }
    },
    value: {
      //   immediate: true,
      //   deep: true,
      handler: function (n) {
        if (n.toString()) {
          this.$nextTick(() => {
            this.transitValue = n
            this.currentKey = this.value
            this.$refs['selecteltree'].setCurrentKey(this.currentKey)
          })
        } else {
          this.$nextTick(() => {
            this.transitValue = n
            this.currentKey = this.value
            this.$refs['selecteltree'].setCurrentKey(null)
          })
        }
      }
    }
  }
}
</script>
<style lang="scss" scoped>
.el-tree-node.is-current>.el-tree-node__content {
  color: #409EFF;
}
</style>
