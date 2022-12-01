import axios, { AxiosResponse } from "axios";
import { LocaleMessageObject } from "vue-i18n";
import { UserInfoDto } from "@/store/user";

const axiosInstance = axios.create({
  baseURL: (axios.defaults.baseURL = "/api/"),
  timeout: 120_000,
});

const TRANSLATIONS_API = "/user/translation";
const USER_INFO_API = "/user/info";

export async function postTranslations(
  translationJson: Record<string, unknown>
): Promise<AxiosResponse<LocaleMessageObject>> {
  const headers = {
    Accept: "application/json",
  };

  return axiosInstance.post<LocaleMessageObject>(
    TRANSLATIONS_API,
    translationJson,
    {
      headers,
    }
  );
}

export async function getUserInfo(): Promise<AxiosResponse<UserInfoDto>> {
  return axiosInstance.get<UserInfoDto>(USER_INFO_API);
}
