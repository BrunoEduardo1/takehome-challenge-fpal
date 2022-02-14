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
  mounted() {
    this.getUserData();
  },
  methods: {
    getUserData() {
      // buscar do back
      const token = Cookie.get('_task_token');
      const userId = this.$route.params.id;
      let config = {
        headers: {
          Authorization: `Bearer ${token}`
        }
      };
      axios
        .get(`api/users/${userId}`, config)
        .then((response) => {
          if (response.data.success) {
            let responseData = response.data;

            this.user = {
              name: responseData.item.name,
              email: responseData.item.email
            };
          } else {
            this.$router.push({ name: 'Overview' });
          }
        })
        .catch((e) => {
          this.$router.push({ name: 'Overview' });
        });
    },
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
        // password_confirmation: this.user.password_confirmation
      };

      this.inputDisabled = true;

      axios
        .put(`/api/users/update/${this.$route.params.id}`, formData, config)
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
            <h4 slot="header" class="card-title">Editar usuário</h4>
            <form>
              <div class="row">
                <div class="col-md-6">
                  <base-input type="text" label="Nome" placeholder="Nome" v-model="user.name"> </base-input>
                </div>
                <div class="col-md-6">
                  <base-input type="email" label="Email" placeholder="Email" v-model="user.email"> </base-input>
                </div>
              </div>

              <div class="row">
                <div class="col-md-12">
                  <base-input type="password" label="Senha" placeholder="Senha" v-model="user.password"> </base-input>
                </div>
              </div>

              <div class="text-center">
                <router-link class="btn btn-secondary btn-fill float-left" :to="{ name: 'users' }">Voltar</router-link>
                <button
                  type="submit"
                  class="btn btn-primary btn-fill float-right"
                  @click.prevent="handleSubmit"
                  :disabled="inputDisabled"
                >
                  Atualizar
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
