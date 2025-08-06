export const returnPrev = {
  methods: {
    returnPrev() {
      this.$store.dispatch('tagsView/delView', this.$route);
      this.$router.go(-1);
    }
  }
}