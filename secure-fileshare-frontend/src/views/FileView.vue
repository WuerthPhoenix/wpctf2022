<template>
  <Page class="fileview" title="File viewer" user-permission="listFiles">
    <cv-data-table :columns="columns">
      <template slot="data">
        <cv-data-table-row v-for="file in files" :key="file.id">
          <cv-data-table-cell>{{ file.name }}</cv-data-table-cell>
          <cv-data-table-cell>{{ file.size }}</cv-data-table-cell>
          <cv-data-table-cell class="actions-cell">
            <button
              class="
                cv-button
                bx--btn bx--btn--icon-only bx--btn--secondary bx--btn--field
              "
              @click="onDownload(file)"
              v-if="userCanDownloadFiles"
            >
              <Download16 />
            </button>
            <button
              class="
                delete-btn
                cv-button
                bx--btn bx--btn--icon-only bx--btn--danger bx--btn--field
              "
              @click="onDelete(file)"
              v-if="userCanDeleteFiles"
            >
              <TrashCan16 class="bx--btn__icon" />
            </button>
          </cv-data-table-cell>
        </cv-data-table-row>
      </template>
    </cv-data-table>
  </Page>
</template>

<script lang="ts">
import { Vue, Component } from "vue-property-decorator";
import UIShell from "@/components/structure/UIShell.vue";
// @ts-ignore
import TrashCan16 from "@carbon/icons-vue/es/trash-can/16";
// @ts-ignore
import Download16 from "@carbon/icons-vue/es/download/16";
import axios from "axios";
import FileSaver from "file-saver";
import Page from "@/components/structure/Page.vue";
import notification from "@/store/notification";
import user from "@/store/user";

export type FileEntry = {
  id: number;
  name: string;
  size: number;
};

@Component({
  components: {
    Page,
    UIShell,
    TrashCan16,
    Download16,
  },
})
export default class FileView extends Vue {
  private columns = ["Name", "Size", "Actions"];
  private files: FileEntry[] = [];

  public mounted(): void {
    this.fetchData();
  }

  public fetchData(): void {
    axios
      .get("/files.php")
      .then((data) => {
        this.files = data.data;
      })
      .catch((e) => {
        notification.addError({
          title: "Error",
          message: e,
        });
      });
  }

  public onDelete(file: FileEntry): void {
    axios
      .delete(`/files.php?id=${file.id}`)
      .then(() => {
        notification.addSuccess({
          title: "Deleted",
          message: `File ${file.name} has been deleted`,
        });
        this.fetchData();
      })
      .catch((e) => {
        notification.addError({
          title: "Error",
          message: e,
        });
      });
  }

  public onDownload(file: FileEntry): void {
    axios({
      method: "GET",
      url: `/files.php?id=${file.id}`,
      responseType: "blob",
    })
      .then((resp) => {
        console.log("deleted!" + file.id);
        var blob = new Blob([resp.data], {
          type: resp.headers["Content-Type"],
        });
        FileSaver.saveAs(blob, file.name);
      })
      .catch((e) => {
        notification.addError({
          title: "Error",
          message: e,
        });
      });
  }

  get userCanDeleteFiles(): boolean {
    if (!user.props) {
      return false;
    }

    return user.props.permissions.deleteFiles;
  }

  get userCanDownloadFiles(): boolean {
    if (!user.props) {
      return false;
    }

    return user.props.permissions.download;
  }
}
</script>

<style lang="scss" scoped>
.delete-btn {
  margin-left: $spacing-03;
}

.actions-cell {
  width: 150px;
}
</style>
