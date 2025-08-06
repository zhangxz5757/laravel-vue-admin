<template>
  <div>
    <el-form ref='ruleForm' size="mini" :model='ruleForm' :rules='rules' label-width='100px' class='demo-ruleForm'>
      <el-form-item label='菜单标题' prop='title'>
        <el-input v-model='ruleForm.title' placeholder='请输入菜单标题' />
      </el-form-item>
      <el-form-item label='路由名称' prop='name'>
        <el-input v-model='ruleForm.name' placeholder='请输入名称'/>
      </el-form-item>
      <el-form-item label='组件' prop='component'>
        <el-input v-model='ruleForm.component' placeholder='请输入组件路径'/>
      </el-form-item>
      <el-form-item label='路径' prop='path'>
        <el-input v-model='ruleForm.path' placeholder='请输入路径'/>
      </el-form-item>
      <el-form-item label='类型' prop='type'>
        <el-select v-model='ruleForm.type' placeholder='类型'>
          <el-option v-for='(item, index) in typeOptions' :key="index" :label='item.label' :value='item.value'/>
        </el-select>
      </el-form-item>
      <el-form-item label='是否隐藏' prop='hidden'>
        <el-select v-model='ruleForm.hidden' placeholder='是否隐藏'>
          <el-option v-for='(item, index) in hiddenOptions' :key="index" :label='item.label' :value='item.value'/>
        </el-select>
      </el-form-item>
      <el-form-item label='是否可关闭' prop='affix'>
        <el-select v-model='ruleForm.affix' placeholder='是否可关闭'>
          <el-option v-for='(item, index) in affixOptions' :key="index" :label='item.label' :value='item.value'/>
        </el-select>
      </el-form-item>
      <el-form-item label='排序' prop='sort_id'>
        <el-input v-model.number='ruleForm.sort_id' placeholder='请输入排序'/>
      </el-form-item>
      <el-form-item label="图标" prop="icon">
        <el-popover
          popper-class="icon"
          placement="bottom"
          width="600"
          height="600"
          trigger="focus"
        >
          <ul class="ul-icon">
            <li
              v-for="(icon, index) in elementIcons"
              :key="icon"
              class="li-icon"
              @click="getIcon(icon, index)"
            >
              <i class="i-icon-choose" :class="'el-icon-' + icon"/>
            </li>
          </ul>
          <el-input
            slot="reference"
            v-model="ruleForm.icon"
            clearable
          >
            <i
              slot="prefix"
              :class="ruleForm.icon"
            ></i>
          </el-input>
        </el-popover>
      </el-form-item>

      <el-form-item label='父级' prop='pid'>
        <el-tree
          ref="tree"
          v-if="treeData.length > 0"
          node-key="id"
          :data="treeData"
          :props="treeProps"
          :expand-on-click-node="false"
          :default-expand-all="false"
          :highlight-current="true"
          :show-checkbox="true"
          :check-strictly="true"
          @check="treeCheck"
        ></el-tree>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import {affixOptions, elementIcons, hiddenOptions, targetOptions, typeOptions} from '@/views/system/menu/options'

export default {
  name: "menuForm",
  props: {
    treeData: {
      type: Array,
      default() {
        return []
      }
    },
    rowData: {
      type: Object,
      default() {
        return {}
      }
    },
    type: {
      type: String,
      default: 'add'
    }
  },
  data() {
    return {
      targetOptions,
      hiddenOptions,
      affixOptions,
      typeOptions,
      elementIcons,
      ruleForm: {
        title: '',
        name: '',
        path: '',
        component: '',
        hidden: 0,
        affix: 1,
        icon: '',
        type: 0,
        sort_id: '',
        pid: '0',
        target: 0
      },
      rules: {
        title: [
          {required: true, message: '请输入菜单标题', trigger: 'blur'}
        ],
        name: [
          {required: true, message: '请输入路由名称', trigger: 'blur'}
        ],
        component: [
          {required: false, message: '请输入组件路径', trigger: 'blur'}
        ],
        path: [
          {required: false, message: '请输入路径', trigger: 'blur'}
        ],
        type: [
          {required: true, message: '请选择类型', trigger: 'blur'}
        ],
        hidden: [
          {required: false, message: '请选择是否隐藏', trigger: 'blur'}
        ],
        affix: [
          {required: false, message: '请选择是否可关闭', trigger: 'blur'}
        ],
        sort_id: [
          {required: false, message: '请输入排序', trigger: 'blur'}
        ],
        icon: [
          {required: false, message: '请选择图标', trigger: 'change'}
        ],
        pid: [
          {required: false, message: '请选择父级', trigger: 'blur'}
        ],
      },
      treeProps: {
        children: 'child',
        hasChildren: 'hasChildren',
        label: 'title'
      },
    }
  },
  created() {
    if (this.type == 'edit') {
      Object.keys(this.ruleForm).forEach(key => (this.ruleForm[key] = this.rowData[key]))
    }
  },
  mounted() {
    if (this.rowData.pid) {
      this.$refs.tree.setCheckedKeys([this.rowData.pid])
    }
  },
  methods: {
    treeCheck(node, list) {
      this.ruleForm.pid = node.id
      if (list.checkedKeys.length == 2) {
        //单选实现
        this.$refs.tree.setCheckedKeys([node.id])
      }
    },
    getIcon(icon, index) {
      this.ruleForm.icon = 'el-icon-' + icon;
    },
  }
}
</script>

<style scoped lang="scss">
li {
  list-style: none;
}

.choose-icon {
  text-align: center;
  font-size: 16px;
  line-height: 16px;
  color: #17163c;
  margin-top: 5px;
  margin-bottom: 5px;
}

.ul-icon {
  display: flex;
  flex-wrap: wrap;
  padding: 5px 0;
  margin: 0;
  justify-content: center;
  overflow-y: auto;
  max-height: 400px;
}

.ul-icon {
  .li-icon {
    width: 44px;
    height: 44px;
    text-align: center;
    line-height: 44px;

    i {
      font-size: 18px;
    }
  }

  .li-icon:hover {
    background-color: #f1f3f4;
  }
}
</style>
