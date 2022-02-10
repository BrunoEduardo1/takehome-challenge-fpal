<script>
export default {
  name: 'NotFound',

  data() {
    return {};
  },

  methods: {}
};
</script>

<template>
  <div id="container-wrapper" class="text-center">
    <div class="not-found-message">
      <h1 class="display-1">404</h1>
      <h5>Página não encontrada</h5>
      <h5>A página que você está tentando visitar não existe</h5>

      <router-link class="mt-3 w-100 btn btn-sm btn-light" :to="'/'">Voltar</router-link>
    </div>
  </div>
</template>

<style scoped>
@import url('https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css');
html,
#container-wrapper {
  height: 100vh;
}

#container-wrapper {
  display: flex;
  align-items: center;
  padding-top: 40px;
  padding-bottom: 40px;
  background-color: #f5f5f5;
}

.not-found-message {
  width: 100%;
  max-width: 330px;
  padding: 15px;
  margin: auto;
}
</style>
