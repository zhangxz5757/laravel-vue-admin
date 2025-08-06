/*
 * @Description:
 * @version:
 * @Author: smy
 * @Date: 2023-09-26 10:28:23
 * @LastEditors: smy
 * @LastEditTime: 2023-10-23 10:24:37
 */
import axios from 'axios'
import { MessageBox, Message, Loading } from 'element-ui'
import _ from 'lodash'
import store from '@/store'
import { getToken } from '@/utils/auth'
import { reject } from 'lodash/collection'
//loading对象
let loading

//当前正在请求的数量
let needLoadingRequestCount = 0

//显示loading
function showLoading(target) {
  // 后面这个判断很重要，因为关闭时加了抖动，此时loading对象可能还存在，
  // 但needLoadingRequestCount已经变成0.避免这种情况下会重新创建个loading
  if (needLoadingRequestCount === 0 && !loading) {
    loading = Loading.service({
      lock: true,
      text: '加载中...',
      background: 'rgba(255, 255, 255, 0.5)',
      target: target || 'body'
    })
  }
  needLoadingRequestCount++
}

//隐藏loading
function hideLoading() {
  needLoadingRequestCount--
  needLoadingRequestCount = Math.max(needLoadingRequestCount, 0) //做个保护
  if (needLoadingRequestCount === 0) {
    //关闭loading
    toHideLoading()
  }
}

//防抖：将 300ms 间隔内的关闭 loading 便合并为一次。防止连续请求时， loading闪烁的问题。
var toHideLoading = _.debounce(() => {
  if (loading) {
    loading.close()
    loading = null
  }
}, 300)

// create an axios instance
const service = axios.create({
  baseURL: process.env.VUE_APP_BASE_API, // url = base url + request url
  // withCredentials: true, // send cookies when cross-domain requests
  timeout: 60000 // request timeout 1分钟
})

// request interceptor
service.interceptors.request.use(
  config => {
    if (store.getters.token) {
      config.headers['Authorization'] = getToken()
    }
    //判断当前请求是否设置了不显示Loading
    if (config.headers.showLoading !== false) {
      showLoading(config.headers.loadingTarget)
    }
    return config
  },
  error => {
    console.log(error) // for debug
    //判断当前请求是否设置了不显示Loading
    if (config.headers.showLoading !== false) {
      hideLoading()
    }
    // Message.error('请求超时!')
    return Promise.reject(error)
  }
)

// response interceptor
service.interceptors.response.use(
  response => {
    //判断当前请求是否设置了不显示Loading（不显示自然无需隐藏）
    if (response.config.headers.showLoading !== false) {
      hideLoading()
    }
    const res = response.data
    if (res.code !== 200) {
      // 获取菜单列表没有状态码
      if (!res.code) {
        return res
      }
      Message({
        message: res.msg || '请求错误',
        type: 'error',
        duration: 2 * 1000
      })

      if (res.code === 403) {
        // to re-login
        MessageBox.confirm('登录过期,请重新登录', {
          confirmButtonText: '重新登录',
          cancelButtonText: '取消',
          type: 'warning'
        }).then(() => {
          store.dispatch('user/resetToken').then(() => {
            location.reload()
          })
        })
      }

      if (res.code === 422) {
        return res
      }

      return Promise.reject(new Error(res.msg || 'Error'))
    } else {
      return res
    }
  },
  error => {
    //判断当前请求是否设置了不显示Loading（不显示自然无需隐藏）
    if (error.config.headers.showLoading !== false) {
      hideLoading()
    }
    console.log('err: ', error) // for debug
    Message({
      message: error.msg || '请求失败',
      type: 'error',
      duration: 5 * 1000
    })
    return Promise.reject(error)
  }
)

export default service
