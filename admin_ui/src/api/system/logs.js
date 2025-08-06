import request from "@/utils/request";

export function getLogList(data) {
    return request({
        url: '/admin/sys_log/index',
        method: 'post',
        data
    })
}

export function getLogParams(data) {
    return request({
        url: '/admin/sys_log/params',
        method: 'post',
        data
    })
}
