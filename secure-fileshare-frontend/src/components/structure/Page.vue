<template>
  <div class="page">
    <UIShell></UIShell>
    <div class="content">
      <h2 class="title">{{ title }}</h2>
      <slot v-if="userIsAuthorized"></slot>
      <div v-else>
        <p>You should not be here, you are not authorized!</p>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { Component, Prop, Vue } from "vue-property-decorator";
import UIShell from "@/components/structure/UIShell.vue";
import user, { UserPermissions } from "@/store/user";

@Component({
  components: { UIShell },
})
export default class Page extends Vue {
  @Prop() title!: string;
  @Prop() userPermission!: string;

  get userIsAuthorized(): boolean {
    if (!user.props) {
      return false;
    }

    return user.props.permissions[this.userPermission as keyof UserPermissions];
  }
}
</script>

<style lang="scss" scoped>
.content {
  padding: $spacing-05;
}
.title {
  margin-top: $spacing-09;
  margin-bottom: $spacing-05;
}
</style>
