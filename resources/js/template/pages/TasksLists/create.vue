<script>
import Cookie from 'js-cookie';
import Card from '../../components/Cards/Card.vue';

export default {
  components: {
    Card
  },
  data() {
    return {
      task: {
        title: '',
        description: '',
        hash: ''
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
        title: this.task.title,
        description: this.task.description
      };

      this.inputDisabled = true;

      axios
        .post(`/api/tasks/create/`, formData, config)
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
            <h4 slot="header" class="card-title">Criar lista</h4>
            <form @submit.stop.prevent="handleSubmit">
              <div class="row">
                <div class="col-md-12">
                  <base-input type="text" label="Título" placeholder="Nome" required="true" v-model="task.title"> </base-input>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <base-input type="text" label="Descrição" placeholder="Descrição" required="true" v-model="task.description">
                  </base-input>
                </div>
              </div>

              <div class="text-center">
                <router-link class="btn btn-secondary btn-fill float-left" :to="{ name: 'tasks-lists' }"
                  >Voltar</router-link
                >
                <button
                  type="submit"
                  class="btn btn-primary btn-fill float-right"
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
