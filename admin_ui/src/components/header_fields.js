
// 资产表头
export const assetTableHead = [
  {label: "资产名称", key: "title"},
  {label: "资产编码", key: "asset_sn"},
  {label: "所属公司", key: "corp_title"},
  {label: "资产类型", key: "type_title"},
  {label: "资产价值", key: "amount"},
  {label: "资产归属", key: "owner_type", formatter: true, width: 120},
  {label: "联系人", key: "contact_user"},
  {label: "联系人电话", key: "contact_mobile", width: 120},
  {label: "处置方式", key: "status", formatter: true, width: 120}, // 0-合资合作、1-出租、2-出售、3-合作加出租、4-合作加出售、5出租加出售、6合作加出租加出售
  {label: "重点项目", key: "is_important", formatter: true,}, // 1/0
  {label: "用途", key: "use_to"}
]


export const staffTableHead = [
  {label: "姓名", key: "username"},
  {label: "所属部门", key: "dept_title"},
  {label: "所属企业", key: "corp_title"},
  {label: "手机号", key: "mobile"},
]
