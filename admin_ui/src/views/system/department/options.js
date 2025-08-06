export const SysDeptFormRule = {
    title: [
        {
            required: true,
            message: '请输入部门名称',
            trigger: 'blur'
        }
    ],
    pid: [
        {
            required: true,
            message: '请选择上级部门',
            trigger: 'blur'
        }
    ],
    status: [
        {
            required: true,
            message: '请输入状态',
            trigger: 'change'
        }
    ],
    sort_id: [
        {
            required: true,
            message: '请输入排序',
            trigger: 'blur'
        },
        {type: 'number', message: '排序必须为数字值'}
    ]
}

export const SysDeptFormData = {

}
