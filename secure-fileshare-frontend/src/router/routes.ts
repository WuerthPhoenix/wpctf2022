import { RouteConfig } from "vue-router";
import FileView from "@/views/FileView.vue";
import Uploader from "@/views/Uploader.vue";
import Login from "@/views/Login.vue";
import Status from "@/views/Status.vue";

const routes: RouteConfig[] = [
  {
    path: "/",
    redirect: "/login",
  },
  {
    path: "/login",
    name: "Login",
    component: Login,
  },
  {
    path: "/fileview",
    name: "FileView",
    component: FileView,
  },
  {
    path: "/uploader",
    name: "Uploader",
    component: Uploader,
  },
  {
    path: "/status",
    name: "status",
    component: Status,
  },
  {
    path: "*",
    redirect: "/login",
  },
];

export default routes;
