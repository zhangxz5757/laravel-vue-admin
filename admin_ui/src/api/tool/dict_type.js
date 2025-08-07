import request from '@/utils/request'

export function getDictTypeList(data) {
  return request({
    url: '/admin/dict_type/index',
    method: 'post',
    data
  })
}

export function dictTypeStore(data) {
  return request({
    url: '/admin/dict_type/store',
    method: 'post',
    data
  })
}

export function dictTypeDel(data) {
  return request({
    url: '/admin/dict_type/delete',
    method: 'post',
    data
  })
}

export function dictTypeInfo(data) {
  return request({
    url: '/admin/dict_type/info',
    method: 'get',
    params: data
  })
}

export function dictTypeMaxSort() {
  return request({
    url: '/admin/dict_type/max-sort',
    method: 'get',
  })
}

export function dictTypeAll() {
  return request({
    url: '/admin/dict_type/all',
    method: 'get',
  })
}

