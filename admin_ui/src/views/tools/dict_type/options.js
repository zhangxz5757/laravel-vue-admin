export const DictTypeFormData = {
  title: '',
  alias: '',
  remark: '',
  sort_id: null,
  status: 1,
}


export const DictTypeFormRule = {
  title: {
    required: true,
    message: '请输入名称',
    trigger: 'blur'
  },
  alias: {
    required: true,
    message: '请输入别名',
    trigger: 'blur'
  },
  remark: {
    required: true,
    message: '请输入名称',
    trigger: 'blur'
  },
  sort_id: {
    required: true,
    message: '请输入排序',
    trigger: 'blur'
  },
  status: {
    required: true,
    message: '请选择状态',
    trigger: 'blur'
  }
}

export const StatusOptions = [
  {label: '启用', value: 1},
  {label: '禁用', value: 0}
]
