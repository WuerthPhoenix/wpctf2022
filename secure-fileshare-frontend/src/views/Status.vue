<template>
  <Page class="status" title="Current Status" user-permission="listFiles">
    <div class="services">
      <cv-tile kind="standard" class="service">
        <h4>API Requests</h4>
        <cv-skeleton-text class="loading" width="100px" v-if="loading" />
        <div class="state" v-else>
          <span>Normal</span> <CheckmarkFilled class="state-icon ok" />
        </div>
      </cv-tile>

      <cv-tile kind="standard" class="service">
        <h4>Disk Space</h4>
        <cv-skeleton-text class="loading" width="100px" v-if="loading" />
        <div class="state" v-else>
          <span>Normal</span> <CheckmarkFilled class="state-icon ok" />
        </div>
      </cv-tile>

      <cv-tile kind="standard" class="service">
        <h4>Firewall</h4>
        <cv-skeleton-text class="loading" width="100px" v-if="loading" />
        <div class="state" v-else>
          <span>Running</span> <CheckmarkFilled class="state-icon ok" />
        </div>
      </cv-tile>

      <cv-tile kind="standard" class="service">
        <h4>Web Server</h4>
        <cv-skeleton-text class="loading" width="100px" v-if="loading" />
        <div class="state" v-else>
          <span>Running</span> <CheckmarkFilled class="state-icon ok" />
        </div>
      </cv-tile>
    </div>
  </Page>
</template>

<script lang="ts">
import { Component, Vue } from "vue-property-decorator";
import Page from "@/components/structure/Page.vue";
// @ts-ignore
import CheckmarkFilled from "@carbon/icons-vue/es/checkmark--filled/20";

@Component({
  components: { Page, CheckmarkFilled },
})
export default class Status extends Vue {
  public loading = true;

  private beforeMount(): void {
    this.loading = true;
    setTimeout(() => {
      this.loading = false;
    }, 1500);
  }
}
</script>

<style lang="scss" scoped>
.service {
  margin-bottom: $spacing-03;
}

.state,
.loading {
  display: flex;
  margin-top: $spacing-03;

  span {
    width: 55px;
  }

  .state-icon {
    margin-left: $spacing-03;
    position: relative;
    top: -3px;

    &.ok {
      color: #42be65;
    }
  }
}
</style>
