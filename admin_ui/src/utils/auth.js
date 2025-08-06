/*
 * @Description:
 * @version:
 * @Author: smy
 * @Date: 2023-09-26 10:28:23
 * @LastEditors: smy
 * @LastEditTime: 2023-09-26 15:54:47
 */
import Cookies from "js-cookie";

const TokenKey = "AdminAuthorization";

export function getToken() {
  return Cookies.get(TokenKey);
}

export function setToken(token) {
  return Cookies.set(TokenKey, token);
}

export function removeToken() {
  return Cookies.remove(TokenKey);
}
