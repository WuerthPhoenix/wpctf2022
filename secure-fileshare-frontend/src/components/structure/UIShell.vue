<template>
  <div class="uishell">
    <cv-header>
      <cv-header-menu-button aria-controls="side-nav" />
      <cv-header-name prefix="NetEye"> [Secure File Uploader] </cv-header-name>
      <cv-header-nav>
        <cv-header-menu-item href="#/fileview" v-if="userHasListFiles">
          File Viewer
        </cv-header-menu-item>
        <cv-header-menu-item href="#/uploader" v-if="userHasUpload">
          Uploader
        </cv-header-menu-item>
        <cv-header-menu-item href="#/status" v-if="userHasListFiles">
          Status
        </cv-header-menu-item>
      </cv-header-nav>
      <template v-slot:header-global>
        <cv-header-global-action
          @click="actionUserLogout"
          aria-controls="user-panel"
          label="Log out"
          tipPosition="bottom"
          tipAlignment="end"
        >
          <UserAvatar20 v-if="loggedIn" />
        </cv-header-global-action>
      </template>

      <template v-slot:left-panels>
        <cv-side-nav id="side-nav" fixed>
          <cv-side-nav-items>
            <cv-header-menu-item href="#/fileview" v-if="userHasListFiles">
              File Viewer
            </cv-header-menu-item>
            <cv-header-menu-item href="#/uploader" v-if="userHasUpload">
              Uploader
            </cv-header-menu-item>
            <cv-header-menu-item href="#/status" v-if="userHasListFiles">
              Status
            </cv-header-menu-item>
          </cv-side-nav-items>
        </cv-side-nav>
      </template>
    </cv-header>
  </div>
</template>

<script lang="ts">
import { Component, Vue } from "vue-property-decorator";
// @ts-ignore
import UserAvatar20 from "@carbon/icons-vue/es/logout/20.js";
import router from "@/router";
import user from "@/store/user";

@Component({
  components: { UserAvatar20 },
})
export default class UIShell extends Vue {
  public loggedIn = true;

  actionUserLogout(): void {
    user.logout();
    router.push("/login");
  }

  get userHasListFiles(): boolean {
    if (!user.props) {
      return false;
    }

    return user.props.permissions.listFiles;
  }

  get userHasUpload(): boolean {
    if (!user.props) {
      return false;
    }

    return user.props.permissions.upload;
  }
}
</script>
