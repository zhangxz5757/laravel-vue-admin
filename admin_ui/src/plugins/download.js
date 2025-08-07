import axios from 'axios'
import {Loading, Message} from 'element-ui'
import { saveAs } from 'file-saver'
import { getToken } from '@/utils/auth'
import { blobValidate } from "@/utils/validate"

const baseURL = process.env.VUE_APP_BASE_API
let downloadLoadingInstance

export default {
  name(name, isDelete = true) {
    var url = baseURL + "/common/download?fileName=" + encodeURIComponent(name) + "&delete=" + isDelete
    axios({
      method: 'get',
      url: url,
      responseType: 'blob',
      headers: { 'Authorization': 'Bearer ' + getToken() }
    }).then((res) => {
      const isBlob = blobValidate(res.data)
      if (isBlob) {
        const blob = new Blob([res.data])
        this.saveAs(blob, decodeURIComponent(res.headers['download-filename']))
      } else {
        this.printErrMsg(res.data)
      }
    })
  },
  resource(resource) {
    var url = baseURL + "/common/download/resource?resource=" + encodeURIComponent(resource)
    axios({
      method: 'get',
      url: url,
      responseType: 'blob',
      headers: { 'Authorization': 'Bearer ' + getToken() }
    }).then((res) => {
      const isBlob = blobValidate(res.data)
      if (isBlob) {
        const blob = new Blob([res.data])
        this.saveAs(blob, decodeURIComponent(res.headers['download-filename']))
      } else {
        this.printErrMsg(res.data)
      }
    })
  },
  zip(url, name) {
    var url = baseURL + url
    downloadLoadingInstance = Loading.service({ text: "正在下载数据，请稍候", spinner: "el-icon-loading", background: "rgba(0, 0, 0, 0.7)", })
    axios({
      method: 'get',
      url: url,
      responseType: 'blob',
      headers: { 'Authorization': 'Bearer ' + getToken() }
    }).then((res) => {
      const isBlob = blobValidate(res.data)
      if (isBlob) {
        const blob = new Blob([res.data], { type: 'application/zip' })
        this.saveAs(blob, name)
      } else {
        this.printErrMsg(res.data)
      }
      downloadLoadingInstance.close()
    }).catch((r) => {
      console.error(r)
      Message.error('下载文件出现错误，请联系管理员！')
      downloadLoadingInstance.close()
    })
  },
  saveAs(text, name, opts) {
    saveAs(text, name, opts)
  },
  async printErrMsg(data) {
    const resText = await data.text()
    const rspObj = JSON.parse(resText)
    const errMsg = rspObj.msg || "系统错误"
    Message.error(errMsg)
  }
}

