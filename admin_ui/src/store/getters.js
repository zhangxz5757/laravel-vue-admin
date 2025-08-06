const getters = {
  sidebar: state => state.app.sidebar,
  size: state => state.app.size,
  device: state => state.app.device,
  visitedViews: state => state.tagsView.visitedViews,
  cachedViews: state => state.tagsView.cachedViews,
  token: state => state.user.token,
  avatar: state => state.user.avatar,
  username: state => state.user.username,
  nickname: state => state.user.nickname,
  is_super: state => state.user.is_super,
  userInfo: state => state.user.userInfo,
  permission_routes: state => state.permission.routes,
  errorLogs: state => state.errorLog.logs,
  appMainH: state => state.app.appMainH
}
export default getters
