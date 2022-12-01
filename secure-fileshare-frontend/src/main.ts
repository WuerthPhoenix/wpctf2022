import Vue from "vue";
import App from "./App.vue";
import router from "./router";
import store from "./store";
import i18n from "@/utils/i18n";
import VueCookies from "vue-cookies";
import "carbon-components/scss/globals/scss/styles.scss";
// @ts-ignore
import CarbonComponentsVue from "@carbon/vue/src/index";

Vue.use(CarbonComponentsVue);
Vue.config.productionTip = false;
Vue.use(VueCookies);

new Vue({
  i18n,
  router,
  store,
  render: (h) => h(App),
}).$mount("#app");
