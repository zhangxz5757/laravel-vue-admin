import request from '@/utils/request'

// 部门-tree
export function getSysDeptTree() {
  return request({
    url: '/admin/sys_dept/tree',
    method: 'post'
  })
}

export function getDeptList(query) {
  return request({
    url: '/admin/sys_dept/index',
    method: 'get',
    params: query
  })
}

export function sysDeptInfo(query) {
  return request({
    url: '/admin/sys_dept/info',
    method: 'get',
    params: query
  })
}

export function storeSysDept(data) {
  return request({
    url: '/admin/sys_dept/store',
    method: 'post',
    data
  })
}

// 部门-删除
export function deleteSysDept(id) {
  return request({
    url: `/admin/sys_dept/delete`,
    method: 'post',
    data: {
      id
    }
  })
}
