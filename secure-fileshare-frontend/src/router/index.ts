import Vue from "vue";
import routes from "@/router/routes";
import VueRouter from "vue-router";
import user from "@/store/user";

if (!process || process.env.NODE_ENV !== "test") {
  Vue.use(VueRouter);
}

const router = new VueRouter({
  routes,
});

router.beforeEach(async (to, from, next) => {
  // Load JWT from cookies
  const jwt = Vue.$cookies.get("jwt");
  if (jwt && !user.props) {
    user.setProps(jwt);
  }

  if (user.props && to.name === "Login") {
    next({ name: "FileView" });
  } else if (!user.props && to.name !== "Login") {
    next({ name: "Login" });
  } else {
    next();
  }
});

export default router;
