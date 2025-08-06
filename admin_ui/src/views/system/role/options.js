export const RoleFormRule = {
  title: {
    required: true,
    message: '请输入角色名称',
    trigger: 'blur'
  },
  description: {
    required: true,
    message: '请输入角色描述',
    trigger: 'blur'
  },
  status: {
    required: true,
    message: '请选择角色状态',
    trigger: 'blur'
  },
}


export const RoleFormData = {
  title: "",
  description: "",
  status: 1,
  menu_ids: []
}

export const RoleStatusList = [
  {id: 1, title: "启用"},
  {id: 0, title: "禁用"},
]
