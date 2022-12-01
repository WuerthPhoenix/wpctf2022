<template>
  <Page class="uploader" title="Uploader" user-permission="upload">
    <cv-file-uploader
      kind="button"
      label="Choose file to upload"
      helperText="Select the file you want to upload"
      drop-target-label="Add file"
      accept=".jpg,.png"
      :clear-on-reselect="true"
      :initial-state-uploading="true"
      :multiple="false"
      :removable="false"
      v-model="files"
      remove-aria-label="Custom remove aria label"
      ref="fileUploader"
      @change="onchanges"
    >
    </cv-file-uploader>
    {{ this.message }}
  </Page>
</template>

<script lang="ts">
import { Component, Vue } from "vue-property-decorator";
import axios from "axios";
import UIShell from "@/components/structure/UIShell.vue";
import Page from "@/components/structure/Page.vue";
import notification from "@/store/notification";
import user from "@/store/user";

@Component({
  components: { Page, UIShell },
})
export default class Uploader extends Vue {
  public files = null;
  private el: any;
  public message = "";
  private created(): void {
    console.log("created");
  }

  private mounted() {
    console.log("mounted");
    // console.log(this.$refs);
    this.el = this.$refs.fileUploader;
  }

  public onchanges(e: any[]): void {
    if (!user.props) {
      notification.addError({
        title: "Error",
        message: "Not authorized",
      });
      return;
    }

    console.log(e);
    let file = e[0].file;
    console.log(file);
    let data = new FormData();
    data.append("file", file);
    data.append("group_id", `${user.props.group_id}`);

    this.el.setState(0, "uploading");

    axios
      .post("/fileupload.php", data, {
        headers: {
          "Content-Type": "multipart/form-data",
        },
      })
      .then(() => {
        this.message = "";
        this.el.setState(0, "complete");
      })
      .catch((reason) => {
        notification.addError({
          title: "Error",
          message: reason,
        });
        this.el.setState(0, "");
        this.message = "Error uploading:" + reason.toString();
      });

    console.log("finished");
  }
}
</script>
