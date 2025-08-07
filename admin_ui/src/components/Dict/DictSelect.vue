<template>
  <div>
    <el-select style="width: 100%" v-model="selectValue" @change="changeValue" :multiple="multiple" clearable>
      <el-option v-for="(item, index) in selectData" :key="index" :label="item.label" :value="item.value"></el-option>
    </el-select>
  </div>
</template>

<script>
import {getCodeDataList} from "@/api/tool/dict_type";

export default {
  name: "DictSelect",
  props: {
    alias: {
      type: String,
      default: ""
    },
    multiple: {
      type: Boolean,
      default: false
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
