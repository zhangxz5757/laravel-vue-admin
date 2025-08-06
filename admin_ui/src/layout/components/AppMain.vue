<!--
 * @Description:
 * @version:
 * @Author: smy
 * @Date: 2023-09-26 10:28:23
 * @LastEditors: smy
 * @LastEditTime: 2023-10-07 16:26:30
-->
<template>
  <section :class="cName">
    <transition name="fade-transform" mode="out-in">
      <keep-alive :include="cachedViews">
        <router-view :key="key" />
      </keep-alive>
    </transition>
  </section>
</template>

<script>
export default {
  name: 'AppMain',
  props: {
    cName: {
      type: String,
      default: 'app-main'
    }
  },
  computed: {
    cachedViews() {
      return this.$store.state.tagsView.cachedViews
    },
    key() {
      return this.$route.path
    }
  }
}
</script>

<style lang="scss" scoped>
.app-main {
  /* 50:navbar; 34: tabs-scroll   */
  height: calc(100vh - 84px);
  width: 100%;
  position: relative;
  overflow: hidden;
  // padding: 20px;
  box-sizing: border-box;
  background:#F3F3F4;
}
.app-main-side {
  height: 100vh;
  width: 100%;
  position: relative;
  overflow: hidden;
  box-sizing: border-box;
  background:#F3F3F4;
}
.fixed-header + .app-main {
  padding-top: 50px;
}

.hasTagsView {
  .app-main {
    /* 84 = navbar + tags-view = 50 + 34 */
    min-height: calc(100vh - 84px);
  }

  .fixed-header + .app-main {
    padding-top: 84px;
  }
}
</style>

<style lang="scss">
// fix css style bug in open el-dialog
.el-popup-parent--hidden {
  .fixed-header {
    padding-right: 15px;
  }
}
</style>
