import request from '@/utils/request'


export function getRolesList(data) {
  return request({
    url: '/admin/sys_role/index',
    method: 'get',
    params: data
  })
}

export function storeRole(data) {
  return request({
    url: '/admin/sys_role/store',
    method: 'post',
    data
  })
}

export function roleInfo(query) {
  return request({
    url: '/admin/sys_role/info',
    method: 'get',
    params: query
  })
}

export function deleteRole(id) {
  return request({
    url: `/admin/sys_role/delete`,
    method: 'post',
    data: {
      id
    }
  })
}

export function getRoleMenu(data) {
  return request({
    url: '/admin/sys_role/menu',
    method: 'get',
    params: data
  })
}


/**
 * @description: 角色组-保存菜单权限
 * @param {*} data
 * @param { String } data.group_id - 角色组id
 * @param { String } data.menu_ids	菜单id数组
 * @return {*}
 */
export function storeMenu(data) {
  return request({
    url: `/admin/sys_role/menu`,
    method: 'post',
    data
  })
}

// 角色组-全部角色
export function getAllRole() {
  return request({
    url: `/admin/sys_role/all`,
    method: 'post'
  })
}
