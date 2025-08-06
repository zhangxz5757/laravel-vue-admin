import request from '@/utils/request'

export function demoIndex(data) {
  return request({
    url: 'demo/index',
    method: 'get',
    params: data
  })
}

export function demoInfo(data) {
  return request({
    url: 'demo/info',
    method: 'get',
    params: data
  })
}

export function demoType(data) {
  return request({
    url: 'demo/type',
    method: 'get',
    params: data
  })
}

export function demoStore(data) {
  return request({
    url: 'demo/store',
    method: 'post',
    data
  })
}

export function demoDel(data) {
  return request({
    url: 'demo/delete',
    method: 'post',
    data
  })
}
