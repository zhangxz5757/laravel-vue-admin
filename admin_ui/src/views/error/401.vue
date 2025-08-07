<template>
  <div class="errPage-container">
    <el-button icon="el-icon-arrow-left" class="pan-back-btn" @click="back">
      返回首页
    </el-button>
    <el-row>
      系统出错了
    </el-row>
  </div>
</template>

<script>

export default {
  name: 'Page401',
  data() {
    return {
    }
  },
  methods: {
    back() {
      if (this.$route.query.noGoBack) {
        this.$router.push({ path: '/dashboard' })
      } else {
        this.$router.go(-1)
      }
    }
  }
}
</script>

<style lang="scss" scoped>
</style>
