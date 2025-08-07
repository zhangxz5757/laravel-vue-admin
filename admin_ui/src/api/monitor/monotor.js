import request from '@/utils/request'

export function serverInfo() {
  return request({
    url: '/admin/monitor/server',
    method: 'get'
  })
}
