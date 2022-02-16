<template>
  <ul class="nav nav-mobile-menu">
    <li class="nav-item">
      <a class="nav-link" href="#" @click="logout">
        <span class="no-icon">Sair</span>
      </a>
    </li>
  </ul>
</template>
<script>
import Cookie from 'js-cookie';

export default {
  name: 'mobile-menu',
  methods: {
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
