<template>
  <div :class="classObj" class="app-wrapper" v-if="showSide">
    <div
      v-if="device === 'mobile' && sidebar.opened"
      class="drawer-bg"
      @click="handleClickOutside"
    />
    <sidebar class="sidebar-container" />
    <div :class="{ hasTagsView: needTagsView }" class="main-container">
      <div>
        <navbar />
        <tags-view v-if="needTagsView" />
      </div>
      <app-main cName="app-main"/>
    </div>
  </div>
  <div v-else>
    <app-main cName="app-main-side"/>
  </div>
</template>

<script>
import { AppMain, Navbar, Sidebar, TagsView } from './components'
import ResizeMixin from './mixin/ResizeHandler'
import { mapState, mapGetters } from 'vuex'
import { debounce } from '@/utils'

export default {
  name: 'Layout',
  components: {
    AppMain,
    Navbar,
    Sidebar,
    TagsView
  },
  mixins: [ResizeMixin],
  computed: {
    ...mapState({
      sidebar: state => state.app.sidebar,
      device: state => state.app.device,
    }),
    classObj() {
      return {
        hideSidebar: !this.sidebar.opened,
        openSidebar: this.sidebar.opened,
        withoutAnimation: this.sidebar.withoutAnimation,
        mobile: this.device === 'mobile'
      }
    }
  },

  data() {
    return {
      needTagsView: true,
      showSide: true
    }
  },
  created() {
    if (this.$route.query.showSide && this.$route.query.showSide == '0') {
      this.showSide = false
    } else  {
      this.showSide = true
    }
  },
  mounted() {
    let appMainH = document.getElementsByClassName('app-main')[0].clientHeight
    this.$store.dispatch('app/setAppMainH', appMainH)
    window.addEventListener(
      'resize',
      debounce(() => {
        let appMainH = document.getElementsByClassName('app-main')[0].clientHeight
        this.$store.dispatch('app/setAppMainH', appMainH)
      }, 500)
    )
  },
  methods: {
    handleClickOutside() {
      this.$store.dispatch('app/closeSideBar', { withoutAnimation: false })
    },
    openTarget() {
      let routeUrl = this.$router.resolve({
        path: this.$route.path
      });
    }
  },
  watch: {
    showSide(newVal) {
      if (!newVal) {
        this.openTarget()
      }
    }
  }
}
</script>

<style lang="scss" scoped>
@import '~@/styles/mixin.scss';
@import '~@/styles/variables.scss';

.app-wrapper {
  @include clearfix;
  position: relative;
  height: 100%;
  width: 100%;

  &.mobile.openSidebar {
    position: fixed;
    top: 0;
  }
}

.drawer-bg {
  background: #000;
  opacity: 0.3;
  width: 100%;
  top: 0;
  height: 100%;
  position: absolute;
  z-index: 999;
}

.fixed-header {
  position: fixed;
  top: 0;
  right: 0;
  z-index: 9;
  width: calc(100% - #{$sideBarWidth});
  transition: width 0.28s;
}

.hideSidebar .fixed-header {
  width: calc(100% - 54px);
}

.mobile .fixed-header {
  width: 100%;
}
</style>
