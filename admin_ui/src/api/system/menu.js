import request from "@/utils/request";

export function getMenuList(data) {
  return request({
    url: '/admin/sys_menu/tree',
    method: 'post',
    data
  })
}

export function saveMenu(data) {
  return request({
    url: '/admin/sys_menu/store',
    method: 'post',
    data
  })
}

export function deleteMenu(data) {
  return request({
    url: '/admin/sys_menu/delete',
    method: 'post',
    data
  })
}


export function getMenuTree() {
  return request({
    url: '/admin/sys_menu/tree',
    method: 'get',
  })
}
