<template>
  <div
    :class="{ fullscreen: fullscreen }"
    class="tinymce-container"
    :style="{ width: containerWidth }"
  >
    <textarea :id="tinymceId" class="tinymce-textarea" />

  </div>
</template>

<script>
/**
 * docs:
 * https://panjiachen.github.io/vue-element-admin-site/feature/component/rich-editor.html#tinymce
 */
import editorImage from './components/EditorImage'
import plugins from './plugins'
import toolbar from './toolbar'
import load from './dynamicLoadScript'
import { getToken } from '@/utils/auth' // get token from cookie


// why use this cdn, detail see https://github.com/PanJiaChen/tinymce-all-in-one
const tinymceCDN = '/plugin/tinymce/tinymce.min.js'

export default {
  name: 'Tinymce',
  components: { editorImage },
  props: {
    id: {
      type: String,
      default: function() {
        return 'vue-tinymce-' + +new Date() + ((Math.random() * 1000).toFixed(0) + '')
      }
    },
    value: {
      type: String,
      default: ''
    },
    toolbar: {
      type: Array,
      required: false,
      default() {
        return []
      }
    },
    menubar: {
      type: String,
      default: 'file edit insert view format table'
    },
    height: {
      type: [Number, String],
      required: false,
      default: 360
    },
    width: {
      type: [Number, String],
      required: false,
      default: 'auto'
    }
  },
  data() {
    return {
      hasChange: false,
      hasInit: false,
      tinymceId: this.id,
      fullscreen: false,
      languageTypeList: {
        en: 'en',
        zh: 'zh-Hans',
        es: 'es_MX',
        ja: 'ja'
      }
    }
  },
  computed: {
    containerWidth() {
      const width = this.width
      if (/^[\d]+(\.[\d]+)?$/.test(width)) {
        // matches `100`, `'100'`
        return `${width}px`
      }
      return width
    }
  },
  watch: {
    value(val) {
      if (!this.hasChange && this.hasInit) {
        this.$nextTick(() => window.tinymce.get(this.tinymceId).setContent(val || ''))
      }
    }
  },
  mounted() {
    this.init()
  },
  activated() {
    if (window.tinymce) {
      this.initTinymce()
    }
  },
  deactivated() {
    this.destroyTinymce()
  },
  beforeDestroy() {
    this.destroyTinymce()
  },
  destroyed() {
    this.destroyTinymce()
  },
  methods: {
    init() {
      // dynamic load tinymce from cdn
      load(tinymceCDN, err => {
        if (err) {
          this.$message.error(err.message)
          return
        }
        this.initTinymce()
      })
    },
    initTinymce() {
      const _this = this
      window.tinymce.init({
        selector: `#${this.tinymceId}`,
        language: this.languageTypeList['zh'],
        language_url: '/plugin/tinymce/langs/zh-Hans.js',
        height: this.height,
        body_class: 'panel-body ',
        object_resizing: false,
        toolbar: this.toolbar.length > 0 ? this.toolbar : toolbar,
        menubar: this.menubar,
        plugins: plugins,
        end_container_on_empty_block: true,
        powerpaste_word_import: 'clean',
        code_dialog_height: 450,
        code_dialog_width: 1000,
        advlist_bullet_styles: 'square',
        advlist_number_styles: 'default',
        // image_advtab: true,
        // 图片读取前缀路径
        // images_upload_base_path: this.picUrl,
        // 图片本地上传方法  点击上传后执行的事件
        images_upload_url: '/admin/uploads',
        images_upload_handler: (blobInfo, success, failure) => {
          this.handleImgUpload(blobInfo, success, failure)
        },
        default_link_target: '_blank',
        link_title: false,
        nonbreaking_force_tab: true, // inserting nonbreaking space &nbsp; need Nonbreaking Space Plugin
        init_instance_callback: editor => {
          if (_this.value) {
            editor.setContent(_this.value)
          }
          _this.hasInit = true
          editor.on('NodeChange Change KeyUp SetContent', () => {
            this.hasChange = true
            this.$emit('input', editor.getContent())
          })
        },
        setup(editor) {
          editor.on('FullscreenStateChanged', e => {
            _this.fullscreen = e.state
          })
        },
        // it will try to keep these URLs intact
        // https://www.tiny.cloud/docs-3x/reference/configuration/Configuration3x@convert_urls/
        // https://stackoverflow.com/questions/5196205/disable-tinymce-absolute-to-relative-url-conversions
        convert_urls: false,
          style_formats: [
              {
                  title: '行高',
                  items: [
                      { title: '1', block: 'p', styles: { 'line-height': '1.0' } },
                      { title: '1.1', block: 'p', styles: { 'line-height': '1.1' } },
                      { title: '1.2', block: 'p', styles: { 'line-height': '1.2' } },
                      { title: '1.3', block: 'p', styles: { 'line-height': '1.3' } },
                      { title: '1.4', block: 'p', styles: { 'line-height': '1.4' } },
                      { title: '1.5', block: 'p', styles: { 'line-height': '1.5' } },
                      { title: '1.6', block: 'p', styles: { 'line-height': '1.6' } },
                      { title: '1.7', block: 'p', styles: { 'line-height': '1.7' } },
                      { title: '1.8', block: 'p', styles: { 'line-height': '1.8' } },
                      { title: '1.9', block: 'p', styles: { 'line-height': '1.9' } },
                      { title: '2', block: 'p', styles: { 'line-height': '2' } },
                      { title: '2.5', block: 'p', styles: { 'line-height': '2.5' } },
                      { title: '3', block: 'p', styles: { 'line-height': '3' } },
                      { title: '3.5', block: 'p', styles: { 'line-height': '3.5' } },
                      { title: '4', block: 'p', styles: { 'line-height': '4' } },
                      { title: '4.5', block: 'p', styles: { 'line-height': '4.5' } },
                      { title: '5', block: 'p', styles: { 'line-height': '5' } },
                      { title: '5.5', block: 'p', styles: { 'line-height': '5.5' } },
                      { title: '6', block: 'p', styles: { 'line-height': '6' } },
                  ]
              }
          ],
          style_formats_merge: true,
          style_formats_autohide: true,
          font_formats:
              '微软雅黑=Microsoft YaHei,Helvetica Neue,PingFangSC,sans-serif;' +
              '苹果苹方=PingFang Sc,Microsoft YaHei,sans-serif;' +
              '宋体=simsun,serif;' +
              '仿宋体=FangSong,serif;' +
              '新宋体=NSimSun;' +
              '楷体=KaiTi,sans-serif;' +
              '方正黑体=FZHei,sans-serif;' +
              '黑体=SimHei,sans-serif;' +
              '隶书=LiSu',
        file_picker_types: 'media',
        file_picker_callback: (cb, value, meta) => {
          this.handleVideoUpload(cb, value, meta)
        },
        media_url_resolver: function(data, resolve) {
          try {
            let embedHtml = `
              <p>
                <span
                  data-mce-selected="1"
                  data-mce-object="video"
                  data-mce-p-controls="controls"
                  data-mce-p-allowfullscreen="true"
                  style="width: 200px;height: 120px;display: block"
                  data-mce-p-src="${ data.url }"
                >
                <video src="${ data.url }" width="100%" height="100%" controls="controls" controlslist="nodownload"></video>
                </span>
              </p>
              <p style="text-align: left;"></p>
            `
            resolve({ html: embedHtml })
          } catch (e) {
            resolve({ html: "" })
          }
          console.log(data)
        },
        media_live_embeds: true
        // 整合七牛上传
        // images_dataimg_filter(img) {
        //   setTimeout(() => {
        //     const $image = $(img);
        //     $image.removeAttr('width');
        //     $image.removeAttr('height');
        //     if ($image[0].height && $image[0].width) {
        //       $image.attr('data-wscntype', 'image');
        //       $image.attr('data-wscnh', $image[0].height);
        //       $image.attr('data-wscnw', $image[0].width);
        //       $image.addClass('wscnph');
        //     }
        //   }, 0);
        //   return img
        // },
        // images_upload_handler(blobInfo, success, failure, progress) {
        //   progress(0);
        //   const token = _this.$store.getters.token;
        //   getToken(token).then(response => {
        //     const url = response.data.qiniu_url;
        //     const formData = new FormData();
        //     formData.append('token', response.data.qiniu_token);
        //     formData.append('key', response.data.qiniu_key);
        //     formData.append('file', blobInfo.blob(), url);
        //     upload(formData).then(() => {
        //       success(url);
        //       progress(100);
        //     })
        //   }).catch(err => {
        //     failure('出现未知问题，刷新页面，或者联系程序员')
        //     console.log(err);
        //   });
        // },
      })
    },
    // 自定义上传视频回调
    handleVideoUpload(cb, value, meta) {
      let _this = this
      if (meta.filetype == 'media') {
        let input = document.createElement('input');
        input.setAttribute('type', 'file')
        input.onchange = function() {
          document.querySelector('#mce-modal-block').style.zIndex = 2000
          let file = this.files[0]
          let name = file.name.replace(/.*\.([^.]+)$/, "$1").toLowerCase()
          if (name !== 'mp4') {
            _this.$message.error('只支持mp4格式视频');
            return
          }
          if (file.size / 1024 / 1024 > 256) {
            _this.$message.error('视频大小限制为256M');
            return
          }
          let loading = _this.$loading({
            lock: true,
            text: '上传中',
            spinner: 'el-icon-loading',
            background: 'rgba(0, 0, 0, 0.7)',
          })
          document.querySelector('.el-loading-mask').style.zIndex = 65537
          let xhr, formData
          xhr = new XMLHttpRequest()
          xhr.open('POST', '/api/admin/uploads')
          xhr.setRequestHeader('Authorization', getToken())
          xhr.onload = function() {
            let json
            if (xhr.status < 200 || xhr.status >= 300) {
              loading.close()
              console.log('HTTP 错误' + xhr.status)
              return
            }
            json = JSON.parse(xhr.responseText)
            if (json.status == 0) {
              let mediaLocation = json.data.location
              cb(mediaLocation, { title: file.name })
            } else {
              loading.close()
              cb(json.data.img)
              return
            }
          }
          formData = new FormData()
          formData.append('file', file)
          xhr.send(formData)
        }
        input.click()
      }
    },
    // 自定义上传图片回调
    handleImgUpload(blobInfo, success, failure) {
      var xhr, formData
      xhr = new XMLHttpRequest()
      xhr.open('POST', '/api/admin/uploads')
      xhr.setRequestHeader('Authorization', getToken())
      xhr.onload = function() {
        var json
        if (xhr.status != 200) {
          failure('HTTP Error: ' + xhr.status)
          return
        }
        json = JSON.parse(xhr.responseText)
        success(json.data.img)
      }

      formData = new FormData()
      formData.append('file', blobInfo.blob(), blobInfo.filename())

      xhr.send(formData)
    },

    destroyTinymce() {
      const tinymce = window.tinymce.get(this.tinymceId)
      if (this.fullscreen) {
        tinymce.execCommand('mceFullScreen')
      }

      if (tinymce) {
        tinymce.destroy()
      }
    },
    setContent(value) {
      window.tinymce.get(this.tinymceId).setContent(value)
    },
    getContent() {
      window.tinymce.get(this.tinymceId).getContent()
    },
    imageSuccessCBK(arr) {
      arr.forEach(v =>
        window.tinymce.get(this.tinymceId).insertContent(`<img class="wscnph" src="${v.url}" >`)
      )
    }
  },
}
</script>

<style lang="scss" scoped>
.tinymce-container {
  position: relative;
  line-height: normal;
}

.tinymce-container {
  ::v-deep {
    .mce-fullscreen {
      z-index: 10000;
    }
  }
}

.tinymce-textarea {
  visibility: hidden;
  z-index: -1;
}

.editor-custom-btn-container {
  position: absolute;
  right: 4px;
  top: 4px;
  /*z-index: 2005;*/
}

.fullscreen .editor-custom-btn-container {
  z-index: 10000;
  position: fixed;
}

.editor-upload-btn {
  display: inline-block;
}
</style>
