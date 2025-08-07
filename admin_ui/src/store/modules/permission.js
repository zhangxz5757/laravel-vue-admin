/*
 * @Description:
 * @version:
 * @Author: smy
 * @Date: 2023-09-26 10:28:23
 * @LastEditors: smy
 * @LastEditTime: 2023-10-17 08:59:24
 */
import { constantRoutes } from '@/router'
import { getMenuInfo } from '@/api/auth'
import Layout from '@/layout'
let myMenus
let btnList = []
/**
 * 处理服务端获取的menus数组
 * @param routes asyncRoutes
 * @param roles
 */
export function formatRoutes(routes) {
  routes.forEach((route, i) => {
    if (route.show != '0' && route.type == 0) {
      let url
      if (route.pid != '0') {
        let str = route.component.replace('/views', '')
        url = str + '/index'
      }
      let obj = {
        // redirect: "noRedirect", //当设置 noRedirect 的时候该路由在面包屑导航中不可被点击
        name: route.name,
        path: route.pid == '0' ? route.path !== '/' ? `${'/' + route.path}` : '' : `${route.path}`,
        component: route.pid == '0' ? Layout : resolve => require([`@/views${route.component.trim()}.vue`], resolve),
        // ..\\..${route.component.trim()}/index.vue
        meta: {
          title: route.title,
          // icon: 'el-icon-cold-drink'
          icon: route.icon,
          affix: route.affix ? false : true
        },
        id: route.id,
        pid: route.pid,
        hidden: route.hidden ? true : false,
        sort_id: route.sort_id
      }
      if (route.children && route.children.length > 0 && route.children[0].show != '0') {
        formatRoutes(route.children)
        obj.hasChild = true
        obj.alwaysShow = true
        obj.children = []
      } else if (route.children && route.children.length > 0 && route.children[0].show == '0') {
        obj.handles = route.children
      }
      if (route.hasOwnProperty('target')) {
        if (Number(route.target) === 0) {
          obj.target = false
        } else {
          obj.target = true
        }
      } else {
        obj.target = false
      }
      obj.target = route.target === 0 ? false : isNaN(route.target) ? false : true
      myMenus.push(obj)
      if (route.show != '0' && route.type != 0) {
        btnList.push(item)
      }
    }
  })
}

// 把menu数组处理成树形结构
export function treeMenus(menus) {
  menus.forEach((item, i) => {
    if (item.hasChild) {
      menus.forEach(deep_item => {
        if (deep_item.pid === item.id) {
          item.children.push(deep_item)
        }
      })
    }
  })
  return menus.filter(item => item.pid === '0')
}

function menusSort(menus) {
  if (menus.length) {
    menus.map(item => {
      if (item.children) {
        menusSort(item.children)
      }
    })
    menus.sort(function(a, b) {
      return a.sort_id - b.sort_id
    })
  }
}

function filterPidTree(arrList) {
  const toTree=(id)=> {
    let childList = []
    let marchArr = arrList.filter(item=> {
      return item.pid == id;
    })
    marchArr.forEach(item=> {
      item.children = toTree(item.id);
      childList.push(item);
    })
    return childList;
  }
  return toTree(0);
}

const state = {
  routes: [],
  addRoutes: []
}

const mutations = {
  SET_ROUTES: (state, routes) => {
    state.addRoutes = routes
    state.routes = constantRoutes.concat(routes)
  },
  GET_ROUTES: (state) => {
    return state.routes
  }
}

const actions = {
  generateRoutes({ commit }, roles) {
    return new Promise(async resolve => {
      myMenus = []
      let ignoreMenus = ['/', '/404', '/401', '/redirect']
      getMenuInfo()
        .then(res => {
          // myMenus = checkRoutes(constantRoutes, res.data.menu)
          formatRoutes(res.data.menu)
          let newArr = filterPidTree(myMenus)
          newArr.push({ path: '*', redirect: '/404', hidden: true })
          newArr.map((item, index) => {
            if (index === 0) {
              let path = item.path
              if (item.children && item.children.length) {
                path = path + '/' + item.children[0].path
              }
            }
          })
          menusSort(newArr)
          newArr.unshift({
            path: '/',
            component: Layout,
            redirect: '/homepage',
            name: 'HomePage',
            meta: { title: '主页', icon: 'el-icon-s-home'},
            children: [
              {
                path: 'homepage',
                component: () => import('@/views/home/index'),
                name: 'homepage',
                meta: { title: '主页', icon: 'el-icon-s-home', affix: true },
              },
            ]
          })
          commit('SET_ROUTES', newArr)
          resolve(newArr)
        })
        .catch(err => {
          // 本地测试用的
          commit('SET_ROUTES', [{ path: '*', redirect: '/404', hidden: true }])
          resolve([{ path: '*', redirect: '/404', hidden: true }])
        })
    })
  }
}

export default {
  namespaced: true,
  state,
  mutations,
  actions
}
