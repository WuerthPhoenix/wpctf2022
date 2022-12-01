<template>
  <div id="app">
    <NotificationContainer />
    <router-view class="router-container" />
  </div>
</template>

<script lang="ts">
import { Component, Vue } from "vue-property-decorator";
import NotificationContainer from "@/components/notifications/NotificationContainer.vue";

@Component({
  components: { NotificationContainer: NotificationContainer },
})
export default class App extends Vue {}
</script>

<style lang="scss">
#app {
  max-width: 100vw;
  overflow-x: hidden;
}
</style>
