<script>
import Cookie from 'js-cookie';
import Card from '../../components/Cards/Card.vue';

export default {
  components: {
    Card
  },
  data() {
    return {
      taskItem: {
        title: '',
        hash: '',
        taskId: ''
      },
      inputDisabled: false
    };
  },
  mounted() {
    this.getTaskData();
  },
  methods: {
    getTaskData() {
      const token = Cookie.get('_task_token');
      const taskItemId = this.$route.params.id;
      let config = {
        headers: {
          Authorization: `Bearer ${token}`
        }
      };
      axios
        .get(`api/tasks/items/${taskItemId}`, config)
        .then((response) => {
          if (response.data.success) {
            let responseData = response.data;

            this.taskItem = {
              title: responseData.item.title,
              hash: responseData.item.hash,
              taskId: responseData.item.task_list_id
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
        title: this.taskItem.title
      };

      this.inputDisabled = true;

      axios
        .put(`/api/tasks/items/update/${this.taskItem.hash}`, formData, config)
        .then((response) => {
          this.$notifications.notify({
            message: `${response.data.message}`,
            horizontalAlign: 'left',
            verticalAlign: 'top',
            type: response.data.success ? 'success' : 'danger'
          });

          this.inputDisabled = false;

          this.$router.push({ name: 'view-task', params: { id: this.taskItem.taskId } });
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
            <h4 slot="header" class="card-title">Editar item da lista</h4>
            <form @submit.stop.prevent="handleSubmit">
              <div class="row">
                <div class="col-md-12">
                  <base-input type="text" label="Título" placeholder="Título" required="true" v-model="taskItem.title">
                  </base-input>
                </div>
              </div>

              <div class="text-center">
                <router-link
                  class="btn btn-secondary btn-fill float-left"
                  :to="{ name: 'view-task', params: { id: taskItem.taskId } }"
                  >Voltar</router-link
                >
                <button type="submit" class="btn btn-primary btn-fill float-right" :disabled="inputDisabled">
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
