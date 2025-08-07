import request from '@/utils/request'

export function getDictDataIndex(data) {
  return request({
    url: '/admin/dict_data/index',
    method: 'post',
    data
  })
}

export function dictDataStore(data) {
  return request({
    url: '/admin/dict_data/store',
    method: 'post',
    data
  })
}

export function dictDataDel(data) {
  return request({
    url: '/admin/dict_data/delete',
    method: 'post',
    data
  })
}

export function dictDataInfo(data) {
  return request({
    url: '/admin/dict_data/info',
    method: 'get',
    params: data
  })
}












export function codeQuery(data) {
  return request({
    url: '/admin/dict',
    method: 'get',
    params: data
  })
}
