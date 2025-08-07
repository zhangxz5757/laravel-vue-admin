<template>
  <div>
    <el-radio-group  v-model="selectValue" @input="changeValue">
      <el-radio v-for="(item, index) in selectData" :key="index" :label="item.value">{{item.label}}</el-radio>
    </el-radio-group>
  </div>
</template>

<script>
import {getCodeDataList} from "@/api/tool/dict_type";

export default {
  name: "DictRadio",
  props: {
    alias: {
      type: String,
      default: ""
    },
    val: {
      type: String,
      default: null
    }
  },

  data() {
    return {
      selectData: [],
      selectValue: null
    }
  },

  created() {
    getCodeDataList({alias: this.alias}).then(res => {
      if (res.code == 200) {
        this.selectData = res.data.list
      }
    })
    this.selectValue = this.val
  },
  methods: {
    changeValue() {
      this.$emit('changeSelect', this.selectValue)
    }
  }
}
</script>
