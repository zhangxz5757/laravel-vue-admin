import request from '@/utils/request'

export function getCodeList(data) {
  return request({
    url: '/admin/dict_type/index',
    method: 'post',
    data
  })
}

export function saveCode(data) {
  return request({
    url: '/admin/dict_type/store',
    method: 'post',
    data
  })
}

export function getCodeDataList(data) {
  return request({
    url: '/admin/dict_data/index',
    method: 'post',
    data
  })
}

export function saveCodeData(data) {
  return request({
    url: '/admin/dict_data/store',
    method: 'post',
    data
  })
}

export function deleteCodeData(data) {
  return request({
    url: '/admin/dict_data/delete',
    method: 'post',
    data
  })
}

export function codeQuery(data) {
  return request({
    url: '/admin/dict',
    method: 'get',
    params: data
  })
}