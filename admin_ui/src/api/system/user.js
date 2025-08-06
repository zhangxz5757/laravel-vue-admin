
import request from '@/utils/request'

export function getSysUserIndex(data) {
  return request({
    url: '/admin/sys_user/index',
    method: 'get',
    params: data
  })
}
export function sysUserInfo(query) {
  return request({
    url: '/admin/sys_user/info',
    method: 'get',
    params: query
  })
}

export function storeSysUser(data) {
  return request({
    url: '/admin/sys_user/store',
    method: 'post',
    data
  })
}

export function deleteSysUser(data) {
  return request({
    url: '/admin/sys_user/delete',
    method: 'post',
    data
  })
}

// 人员-所有用户
export function getAllSysUser(dept_id) {
  return request({
    url: `/admin/sys_user/all`,
    method: 'get',
  })
}
