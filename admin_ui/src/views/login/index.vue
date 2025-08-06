<template>
  <div class="login-container">
    <div class="form-container abs-center">
      <h3 class="title">运营管理平台</h3>
      <div class="el-form-wrap">
        <svg-icon class="flash" icon-class="v2_rd3nx5" />
        <svg-icon class="flash" icon-class="v2_rd3nx5" />
        <el-form
          ref="loginForm"
          :model="loginForm"
          :rules="loginRules"
          class="login-form abs-center"
          autocomplete="on"
          label-position="left"
          size="mini"
        >

          <div class="form-top flex flex-sb">
            <div>用户登录</div>
<!--            <div>忘记密码</div>-->
          </div>
          <el-form-item prop="username">
            <el-input
              ref="username"
              v-model="loginForm.username"
              placeholder="请输入用户名"
              name="username"
              type="text"
              tabindex="1"
              autocomplete="off"
            >
              <i
                style="font-size: 16px; color: #409eff"
                slot="prefix"
                class="el-input__icon el-icon-user"
              ></i>
            </el-input>
          </el-form-item>

          <el-tooltip v-model="capsTooltip" content="大小写锁定已开启" placement="right" manual>
            <el-form-item prop="password">
              <el-input
                :key="passwordType"
                ref="password"
                v-model="loginForm.password"
                :type="passwordType"
                placeholder="请输入密码"
                name="password"
                tabindex="2"
                autocomplete="off"
                @keyup.native="checkCapslock"
                @blur="capsTooltip = false"
                @keyup.enter.native="handleLogin"
              >
                <i
                  style="font-size: 16px; color: #409eff"
                  slot="prefix"
                  class="el-input__icon el-icon-lock"
                ></i>
                <div slot="suffix" class="show-pwd" @click="showPwd">
                  <svg-icon :icon-class="passwordType === 'password' ? 'eye' : 'eye-open'" />
                </div>
              </el-input>
            </el-form-item>
          </el-tooltip>

          <el-button
            round
            :loading="loading"
            type="primary"
            style="width: 100%; margin-bottom: 30px"
            @click.native.prevent="handleLogin"
            >登录</el-button
          >
        </el-form>
      </div>
    </div>
  </div>
</template>

<script>
import { validUsername } from '@/utils/validate'
import SocialSign from './components/SocialSignin'

export default {
  name: 'Login',
  components: { SocialSign },
  data() {
    const validateUsername = (rule, value, callback) => {
      if (!value) {
        callback(new Error('请先输入用户名'))
      } else {
        callback()
      }
    }
    const validatePassword = (rule, value, callback) => {
      if (value.length < 6) {
        callback(new Error('密码不能少于6位'))
      } else {
        callback()
      }
    }
    return {
      loginForm: {
        username: '',
        password: ''
      },
      loginRules: {
        username: [{ required: true, message: '请先输入用户名', trigger: 'blur' },],
        password: [
            { required: true, message: '请输入密码', trigger: 'blur' },
            { max: 18, message: '密码不格式错误', trigger: 'blur' },
            { min: 6, message: '密码不能少于6位', trigger: 'blur' }
          ]
        // username: [{ required: true, trigger: 'blur', validator: validateUsername }],
        // password: [{ required: true, trigger: 'blur', validator: validatePassword }]
      },
      passwordType: 'password',
      capsTooltip: false,
      loading: false,
      showDialog: false,
      redirect: undefined,
      otherQuery: {}
    }
  },
  // watch: {
  //   $route: {
  //     handler: function (route) {
  //       console.log(route,'监听')
  //       const query = route.query
  //       if (query) {
  //         this.redirect = query.redirect
  //         this.otherQuery = this.getOtherQuery(query)
  //       }
  //     },
  //     immediate: true
  //   }
  // },
  created() {
    // window.addEventListener('storage', this.afterQRScan)
  },
  mounted() {
    if (this.loginForm.username === '') {
      this.$refs.username.focus()
    } else if (this.loginForm.password === '') {
      this.$refs.password.focus()
    }
  },
  destroyed() {
    // window.removeEventListener('storage', this.afterQRScan)
  },
  methods: {
    checkCapslock(e) {
      const { key } = e
      this.capsTooltip = key && key.length === 1 && key >= 'A' && key <= 'Z'
    },
    showPwd() {
      if (this.passwordType === 'password') {
        this.passwordType = ''
      } else {
        this.passwordType = 'password'
      }
      this.$nextTick(() => {
        this.$refs.password.focus()
      })
    },
    handleLogin() {
      console.log(this.loginForm,'点击11111111')
      this.$refs.loginForm.validate((valid) => {
        console.log(this.redirect,'点击')
        if (valid) {
          this.loading = true
          this.$store
            .dispatch('user/login', this.loginForm)
            .then(() => {
              this.$router.push({
                path: this.redirect || '/',
                query: this.otherQuery
              })
              this.loading = false
            })
            .catch(() => {
              this.loading = false
            })
        } else {
          console.log('error submit!!')
          return false
        }
      })
    },
    getOtherQuery(query) {
      return Object.keys(query).reduce((acc, cur) => {
        if (cur !== 'redirect') {
          acc[cur] = query[cur]
        }
        return acc
      }, {})
    }
    // afterQRScan() {
    //   if (e.key === 'x-admin-oauth-code') {
    //     const code = getQueryObject(e.newValue)
    //     const codeMap = {
    //       wechat: 'code',
    //       tencent: 'code'
    //     }
    //     const type = codeMap[this.auth_type]
    //     const codeName = code[type]
    //     if (codeName) {
    //       this.$store.dispatch('LoginByThirdparty', codeName).then(() => {
    //         this.$router.push({ path: this.redirect || '/' })
    //       })
    //     } else {
    //       alert('第三方登录失败')
    //     }
    //   }
    // }
  }
}
</script>

<style lang="scss">
/* 修复input 背景不协调 和光标变色 */
/* Detail see https://github.com/PanJiaChen/vue-element-admin/pull/927 */

$bg: #283443;
$light_gray: #fff;
$cursor: #fff;

@supports (-webkit-mask: none) and (not (cater-color: $cursor)) {
  .login-container .el-input input {
    color: $cursor;
  }
}

/* reset element-ui css */
.login-container {
  .el-input {
    display: inline-block;
    height: 40px;
    width: 400px;
    input {
      background: transparent;
      border: 0px;
      -webkit-appearance: none;
      border-radius: 0px;
      color: $light_gray;
      height: 40px;
      font-size: 16px;
      line-height: 40px;
      caret-color: $cursor;
      &:-webkit-autofill {
        box-shadow: 0 0 0px 1000px $bg inset !important;
        -webkit-text-fill-color: $cursor !important;
      }
    }

    input:-webkit-autofill,
    textarea:-webkit-autofill,
    select:-webkit-autofill {
      -webkit-text-fill-color: #ededed !important;
      -webkit-box-shadow: 0 0 0px 1000px transparent inset !important;
      background-color: transparent;
      background-image: none;
      transition: background-color 50000s ease-in-out 0s; //背景色透明  生效时长  过渡效果  启用时延迟的时间
    }
  }

  .el-form-item {
    border: 1px solid #409eff;
    background: transparent;
    margin-bottom: 30px;
    border-radius: 5px;
    color: #454545;
    .el-form-item__error {
      padding-top: 5px;
    }
    overflow: hidden;
  }
}
</style>

<style lang="scss" scoped>
$bg: #2d3a4b;
$dark_gray: #889aa4;
$light_gray: #eee;

.login-container {
  min-height: 100%;
  width: 100%;
  background: url('../../assets/image/bg.png');
  background-size: 100% 100%;
  background-repeat: no-repeat;
  overflow: hidden;
  position: relative;
  .form-container {
    .el-form-wrap {
      position: relative;
      width: 680px;
      height: 425px;
      padding: 0 100px;
      box-sizing: border-box;
      background-size: 100% 100%;
      .flash {
        width: 300px;
        height: 340px;
        position: absolute;
        top: 40px;
      }
      .flash:nth-child(1) {
        left: 40px;
      }
      .flash:nth-child(2) {
        left: 340px;
      }
    }
  }
  .title {
    font-size: 48px;
    color: rgb(64, 158, 255);
    font-style: normal;
    line-height: 55px;
    height: 55px;
    margin: 20px 0;
    text-align: center;
  }
  .form-top {
    height: 30px;
    line-height: 30px;
    margin-bottom: 20px;
    div:nth-child(1) {
      color: #409eff;
      font-size: 20px;
    }
    div:nth-child(2) {
      color: #409eff;
      font-size: 16px;
      cursor: pointer;
    }
  }

  .tips {
    font-size: 14px;
    color: #fff;
    margin-bottom: 10px;

    span {
      &:first-of-type {
        margin-right: 16px;
      }
    }
  }

  .svg-container {
    padding: 6px 5px 6px 15px;
    color: $dark_gray;
    vertical-align: middle;
    width: 30px;
    display: inline-block;
  }

  .show-pwd {
    position: absolute;
    right: 10px;
    top: 10px;
    font-size: 16px;
    color: $dark_gray;
    cursor: pointer;
    user-select: none;
  }

  .thirdparty-button {
    position: absolute;
    right: 0;
    bottom: 6px;
  }

  @media only screen and (max-width: 470px) {
    .thirdparty-button {
      display: none;
    }
  }
}
</style>
