/*
 * @Description: 入口文件
 * @version:
 * @Author: smy
 * @Date: 2023-09-26 10:28:23
 * @LastEditors: smy
 * @LastEditTime: 2023-10-11 13:52:01
 */
import Vue from 'vue'

import Cookies from 'js-cookie'

import 'normalize.css/normalize.css' // a modern alternative to CSS resets

import Element from 'element-ui'

import './styles/element-variables.scss'

import '@/styles/index.scss' // global css

import App from './App'
import store from './store'
import router from './router'

import './icons' // icon
import './permission' // permission control
import './utils/error-log' // error log
import * as filters from './filters' // global filters

import { returnPrev } from '@/mixin/returnPrev'

import { modalSure } from "@/libs/public";
import { modalSureDelete } from "@/libs/public";
import VueDnD from 'awe-dnd'

Vue.use(VueDnD)
Vue.mixin(returnPrev)

Vue.use(Element, {
  size: Cookies.get('size') || 'mini' // set element-ui default size
})

// register global utility filters
Object.keys(filters).forEach(key => {
  Vue.filter(key, filters[key])
})

Vue.prototype.$modalSure = modalSure;
Vue.prototype.$modalSureDelete = modalSureDelete;

Vue.config.productionTip = false

new Vue({
  el: '#app',
  router,
  store,
  // beforeCreate中模板未解析，且this是vm
  beforeCreate() {
    Vue.prototype.$bus = this // 安装全局事件总线
  },
  render: h => h(App)
})
