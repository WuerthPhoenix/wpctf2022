<template>
  <div class="login-container">
    <div class="login-split-left">
      <div class="login-form">
        <h1>Log in</h1>
        <cv-form @submit.prevent="actionLogin">
          <cv-text-input
            class="form-row"
            label="Username"
            v-model="username"
            placeholder="Username"
          >
          </cv-text-input>
          <cv-text-input
            type="password"
            class="form-row"
            label="Password"
            v-model="password"
            placeholder="Password"
          >
          </cv-text-input>
          <cv-button class="login-btn">Log in</cv-button>
        </cv-form>
        <cv-modal
          id="mfa_modal"
          size="small"
          :visible="mfa_check_visible"
          @modal-shown="actionShown"
          @after-modal-hidden="actionAfterHidden"
          :auto-hide-off="true"
        >
          <template slot="title">2-Step Verification</template>
          <template slot="content"
            >Please confirm on your mobile phone</template
          >
        </cv-modal>
      </div>
    </div>
    <div class="login-split-right">
      <div class="team-name" v-if="teamName">Team {{ teamName }}</div>
    </div>
  </div>
</template>

<script lang="ts">
import { Component, Vue } from "vue-property-decorator";
import axios from "axios";
import router from "@/router";
import userStore from "@/store/user";
import notification from "@/store/notification";

@Component({})
export default class Login extends Vue {
  public username = "";
  public password = "";
  public mfa_check_visible = false;
  private mfa_check = 0;
  private mfa_check_passed = false;
  private teamName: string | null = null;

  private beforeMount(): void {
    this.fetchTeamName();
  }

  async fetchTeamName(): Promise<void> {
    axios
      .get("/login.php")
      .then((resp) => {
        this.teamName = resp.headers["wp-ctf-team-name"];
      })
      .catch((error) => {
        this.showError(error);
      });
  }

  actionLogin(): void {
    let data = {
      username: this.username,
      password: this.password,
    };

    axios
      .post("/login.php", data)
      .then((resp) => {
        if (resp.data.success) {
          this.mfa_check = resp.data.mfa_check;
          this.mfa_check_visible = true;
        } else {
          notification.addError({
            title: "Login failed",
            message: "Wrong Username or Password",
          });
        }
      })
      .catch((error) => {
        this.showError(error);
      });

    return;
  }

  async actionShown(): Promise<void> {
    console.log("begin polling!!");
    while (!this.mfa_check_passed) {
      const sleep = (ms: number) => new Promise((r) => setTimeout(r, ms));
      await sleep(1000);
      let data = {
        username: this.username,
        mfa_check: this.mfa_check,
      };
      axios
        .post("/mfa_verify.php", data)
        .then((resp) => {
          if (resp.data.success) {
            this.mfa_check_passed = true;
            this.mfa_check_visible = false;
            userStore.setProps(resp.data.jwt);
          }
        })
        .catch((error) => {
          this.showError(error);
        });
    }

    return;
  }

  showError(error: string): void {
    notification.addError({
      title: "Error",
      message: error,
    });
  }

  actionAfterHidden(): void {
    console.log("redirect to dashboards");
    router.push("/fileview");
    return;
  }
}
</script>

<style lang="scss" scoped>
.login-container {
  display: flex;

  .login-split-left {
    min-width: 350px;
    height: 100vh;
    display: flex;
    flex-direction: column;
    justify-content: center;

    h1 {
      margin-bottom: $spacing-08;
    }

    .login-form {
      margin: $spacing-06;
      margin-left: $spacing-08;

      .form-row {
        margin-bottom: $spacing-05;
      }
    }

    .login-btn {
      margin-top: $spacing-06;
    }
  }

  .login-split-right {
    width: 100%;
    background-image: url("/img/login_bg.png");
    background-size: cover;

    .team-name {
      font-size: 38px;
      margin: 16px;
      justify-content: flex-end;
      display: flex;
    }
  }
}

@media only screen and (max-width: 800px) {
  .login-split-right {
    display: none;
  }

  .login-split-left {
    width: 100%;

    .login-form {
      margin-right: $spacing-08 !important;
    }
  }
}
</style>

<style lang="scss">
#mfa_modal .bx--modal-close {
  visibility: hidden;
}
</style>
