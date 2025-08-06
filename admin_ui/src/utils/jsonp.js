export const jsonp = function (url, data) {
  return new Promise((resolve, reject) => {
    // 1.初始化url
    let dataString = url.indexOf('?') === -1 ? '?' : '&'
    let callbackName = `jsonpCB_${Date.now()}`;
    url += `${dataString}callback=${callbackName}`
    if (data) {

      // 2.有请求参数，依次添加到url
      for (let k in data) {
        url += `&${k}=${data[k]}`
      }
    }

    let scriptNode = document.createElement('script');
    scriptNode.src = url;

    // 3. callback
    window[callbackName] = (result) => {
      result ? resolve(result) : reject('没有返回数据');
      delete window[callbackName];
      document.body.removeChild(scriptNode);
    }

    // 4. 异常情况
    scriptNode.addEventListener('error', () => {
      reject('接口返回数据失败');
      delete window[callbackName];
      document.body.removeChild(scriptNode);
    }, false)

    // 5. 开始请求
    document.body.appendChild(scriptNode)
  })
}