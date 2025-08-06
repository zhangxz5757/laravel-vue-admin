import { jsonp } from '@/utils/jsonp'

const AK = "XUKBZ-U2RYD-W2L4D-PECA6-66JFZ-JVBYS";
const TMap_URL = "https://map.qq.com/api/js?v=2.exp&key=" + AK +"&callback=onMapCallback"
const Api_URL = "https://apis.map.qq.com"
export default {
  AK,
  init: function (){
    return new Promise((resolve, reject) => {
      // 如果已加载直接返回
      if(typeof qq !== "undefined") {
        resolve(qq);
        return true;
      }
      // 地图异步加载回调处理
      window.onMapCallback = function () {
        resolve(qq);
      };

      // 插入script脚本
      let scriptNode = document.createElement("script");
      scriptNode.setAttribute("type", "text/javascript");
      scriptNode.setAttribute("src", TMap_URL);
      document.body.appendChild(scriptNode);
    });
  },
  getLocation: function() {
    return new Promise( (resolve, reject) => {
      let url = Api_URL + '/ws/location/v1/ip'
      jsonp(url, { key: AK, output: 'jsonp' })
        .then(res => {
          if (res.status == 0) {
            resolve(res.result)
          }
        })
    })
  },
  searchLocation: function(keyword) {
    return new Promise( (resolve, reject) => {
      let url = Api_URL + '/ws/geocoder/v1/?address=' + keyword
      jsonp(url, { key: AK, output: 'jsonp'})
        .then(res => {
          if (res.status == 0) {
            resolve(res.result)
          }
        })
    })
  }
}

