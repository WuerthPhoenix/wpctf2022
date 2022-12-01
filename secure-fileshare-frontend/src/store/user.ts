import { Module, VuexModule, Mutation, Action } from "vuex-module-decorators";
import { getModule } from "vuex-module-decorators";
import store from "@/store";
import { getUserInfo } from "@/api/api";
import Notification from "@/store/notification";
import i18n from "@/utils/i18n";
import jwt_decode from "jwt-decode";
import { Vue } from "vue-property-decorator";
import axios from "axios";

export enum ThemeDto {
  light = "light",
  dark = "dark",
  system = "system",
}

export type UserPreferencesDto = {
  language: string;
  theme: ThemeDto;
};

export type UserInfoDto = {
  user: string;
  preferences: UserPreferencesDto;
};

export type UserPermissions = {
  upload: boolean;
  listFiles: boolean;
  deleteFiles: boolean;
  listUsers: boolean;
  download: boolean;
};

export type UserProperties = {
  full_name: string;
  group_id: number;
  permissions: UserPermissions;
};

@Module({ dynamic: true, store: store, name: "User" })
class User extends VuexModule {
  public info: UserInfoDto | null = null;
  public props: UserProperties | null = null;

  @Mutation
  setInfo(info: UserInfoDto | null) {
    this.info = info;
  }

  @Mutation
  setProps(jwt: string) {
    Vue.$cookies.set("jwt", jwt);
    axios.defaults.headers.common["Authorization"] = `Bearer ${jwt}`;

    const decoded = jwt_decode<any>(jwt);
    const userPermissions = {
      upload: parseInt(decoded.permissions.upload) === 1,
      listFiles: parseInt(decoded.permissions.listFiles) === 1,
      deleteFiles: parseInt(decoded.permissions.deleteFiles) === 1,
      listUsers: parseInt(decoded.permissions.listUsers) === 1,
      download: parseInt(decoded.permissions.download) === 1,
    };

    this.props = {
      full_name: decoded.full_name,
      group_id: decoded.group_id,
      permissions: userPermissions,
    };
  }

  @Action
  async updateInfo() {
    let info = null;
    try {
      const response = await getUserInfo();
      if (response.status === 200) {
        info = response.data;
      } else {
        Notification.addError({
          title: response.status.toString(),
          message: response.statusText,
        });
      }
    } catch (error) {
      Notification.addError({
        title: i18n.tc("errors.error"),
        message: error.toString(),
      });
    }

    this.setInfo(info);
  }

  @Mutation
  logout() {
    Vue.$cookies.remove("jwt");
    this.props = null;
  }
}

const moduleInstance = getModule(User);
export default moduleInstance;
