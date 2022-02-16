<script>
import Cookie from 'js-cookie';
import Card from '../../components/Cards/Card.vue';

export default {
  components: {
    Card
  },
  data() {
    return {
      user: {
        name: '',
        email: '',
        password: ''
      },
      inputDisabled: false
    };
  },
  methods: {
    handleSubmit() {
      const token = Cookie.get('_task_token');

      let config = {
        headers: {
          Authorization: `Bearer ${token}`
        }
      };

      let formData = {
        name: this.user.name,
        email: this.user.email,
        password: this.user.password
      };

      this.inputDisabled = true;

      axios
        .post(`/api/users/create/`, formData, config)
        .then((response) => {
          this.$notifications.notify({
            message: `${response.data.message}`,
            horizontalAlign: 'left',
            verticalAlign: 'top',
            type: response.data.success ? 'success' : 'danger'
          });

          this.inputDisabled = false;
        })
        .catch((err) => {
          this.$notifications.notify({
            message: `${err.response.data.message}`,
            // icon: 'nc-icon nc-app',
            horizontalAlign: 'left',
            verticalAlign: 'top',
            type: 'danger'
          });

          if (err.response.data.errors) {
            this.inputDisabled = false;
          }
        });
    }
  }
};
</script>
<template>
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <card>
            <h4 slot="header" class="card-title">Criar usuário</h4>
            <form @submit.stop.prevent="handleSubmit">
              <div class="row">
                <div class="col-md-6">
                  <base-input type="text" label="Nome" placeholder="Nome" required="true" v-model="user.name">
                  </base-input>
                </div>
                <div class="col-md-6">
                  <base-input type="email" label="Email" placeholder="Email" required="true" v-model="user.email">
                  </base-input>
                </div>
              </div>

              <div class="row">
                <div class="col-md-12">
                  <base-input type="password" label="Senha" placeholder="Senha" v-model="user.password"> </base-input>
                </div>
              </div>

              <div class="text-center">
                <router-link class="btn btn-secondary btn-fill float-left" :to="{ name: 'users' }">Voltar</router-link>
                <button type="submit" class="btn btn-primary btn-fill float-right" :disabled="inputDisabled">
                  Criar
                </button>
              </div>
              <div class="clearfix"></div>
            </form>
          </card>
        </div>
      </div>
    </div>
  </div>
</template>
<style>
</style>
