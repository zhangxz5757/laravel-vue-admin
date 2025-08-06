import request from "@/utils/request";

export function login(data) {
  return request({
    url: "/admin/login",
    method: "post",
    data,
  });
}

export function getInfo(token) {
  return request({
    url: "/admin/auth/info",
    method: "get",
    params: { token },
  });
}

export function logout(token) {
  return request({
    url: "/admin/logout",
    method: "get",
    // params: { token },
  });
}

export function clean() {
  return request({
    url: "/admin/clean",
    method: "get",
  });
}

export function getMenuInfo(data) {
  return request({
    url: '/admin/home/index',
    method: 'post',
    data
  })
}

export function saveCurrentSysUser(data) {
  return request({
    url: '/admin/sys_user/saveMy',
    method: 'post',
    data
  })
}

export function getMyInfo() {
  return request({
    url: '/admin/sys_user/my',
    method: 'GET',
  })
}
