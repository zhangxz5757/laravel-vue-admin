
// 自定义验证规则
let validatePassword = (rule, value, callback) => {
    if (!value) callback()
    const regex = new RegExp(/((^(?=.*[a-z])(?=.*[A-Z])(?=.*\W)[\da-zA-Z\W]{8,16}$)|(^(?=.*\d)(?=.*[A-Z])(?=.*\W)[\da-zA-Z\W]{8,16}$)|(^(?=.*\d)(?=.*[a-z])(?=.*\W)[\da-zA-Z\W]{8,16}$)|(^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[\da-zA-Z\W]{8,16}$))/);
    if (!regex.test(value)) {
        callback(new Error('请输入8-16位字符，至少包含数字、大写字母、小写字母、特殊字符中的三种类型'));
    } else {
        callback();
    }
};


export const UserFormRule = {
    username: [
        {
            required: true,
            message: '请输入账号',
            trigger: 'blur'
        },
        { min: 3, message: '账号至少3个字符', trigger: 'blur' }
    ],
    nickname: [
        {
            required: true,
            message: '请输入姓名',
            trigger: 'blur'
        }
    ],
    mobile: [
        {
            required: true,
            message: '请输入手机号',
            trigger: 'blur'
        },
        { min: 11, max: 11, message: '请输入正确手机号', trigger: 'blur' }
    ],
    // group_id: [
    //   {
    //     required: true,
    //     message: '请选择角色',
    //     trigger: ['blur', 'change']
    //   }
    // ],
    status: [
        {
            required: true,
            message: '请选择状态',
            trigger: ['blur', 'change']
        }
    ],
    password: [
        {
            required: false,
            message: '请输入密码',
            trigger: 'blur'
        },
        { validator: validatePassword, trigger: 'blur' }
    ]
}

export const UserFormData = {
    username: "",
    nickmame: "",
    password: "",
    mobile: "",
    status: 1,
    department_id: null,
    role_ids: []
}

export const UserStatusList = [
    {id: 1, title: "启用"},
    {id: 0, title: "禁用"},
]
