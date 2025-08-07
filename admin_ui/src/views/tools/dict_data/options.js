export const DictDataFormData = {
  alias: '',
  label: '',
  value: '',
  sort_id: null,
  status: 1,
}


export const DictDataFormRule = {
  alias: {
    required: true,
    message: '请选择字典',
    trigger: 'blur'
  },
  label: {
    required: true,
    message: '请输入字典标签',
    trigger: 'blur'
  },
  value: {
    required: true,
    message: '请输入字典键值',
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
