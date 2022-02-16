<template>
  <nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
      <a class="navbar-brand" href="#"></a>
      <button
        type="button"
        class="navbar-toggler navbar-toggler-right"
        :class="{ toggled: $sidebar.showSidebar }"
        aria-controls="navigation-index"
        aria-expanded="false"
        aria-label="Toggle navigation"
        @click="toggleSidebar"
      >
        <span class="navbar-toggler-bar burger-lines"></span>
        <span class="navbar-toggler-bar burger-lines"></span>
        <span class="navbar-toggler-bar burger-lines"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-end">
        <ul class="nav navbar-nav mr-auto"></ul>
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a href="#" @click="logout" class="nav-link"> Sair </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>
<script>
import Cookie from 'js-cookie';

export default {
  data() {
    return {
      activeNotifications: false
    };
  },
  computed: {
    routeName() {
      const { name } = this.$route;
      return this.capitalizeFirstLetter(name);
    }
  },
  methods: {
    capitalizeFirstLetter(string) {
      return string.charAt(0).toUpperCase() + string.slice(1);
    },
    toggleNotificationDropDown() {
      this.activeNotifications = !this.activeNotifications;
    },
    closeDropDown() {
      this.activeNotifications = false;
    },
    toggleSidebar() {
      this.$sidebar.displaySidebar(!this.$sidebar.showSidebar);
    },
    hideSidebar() {
      this.$sidebar.displaySidebar(false);
    },
    logout() {
      let token = Cookie.get('_task_token');

      axios
        .post(
          `api/logout`,
          {},
          {
            headers: {
              Authorization: `Bearer ${token}`
            }
          }
        )
        .then((response) => {
          this.$router.push({ name: 'Login' });
        })
        .catch((err) => {
          this.$router.push({ name: 'Login' });
        });
    }
  }
};
</script>
<style>
</style>
