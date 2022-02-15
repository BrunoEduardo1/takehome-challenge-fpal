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
        description: ''
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
      const taskId = this.$route.params.id;
      let config = {
        headers: {
          Authorization: `Bearer ${token}`
        }
      };
      axios
        .get(`api/tasks/${taskId}`, config)
        .then((response) => {
          if (response.data.success) {
            let responseData = response.data;

            this.task = {
              title: responseData.item.title,
              description: responseData.item.description
            };
          } else {
            this.$router.push({ name: 'Overview' });
          }
        })
        .catch((e) => {
          this.$router.push({ name: 'Overview' });
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
            <h4 slot="header" class="card-title">Lista de tarefas</h4>
            <div>
              <ul class="list-group list-group-flush">
                <li class="list-group-item"><span class="text-bold">Titulo:</span> {{task.title}}</li>
                <li class="list-group-item"><span class="text-bold">Descrição:</span> {{task.description}}</li>
              </ul>
              <div class="font-weight-bold mt-5">
                <router-link class="btn btn-secondary btn-fill float-left" :to="{ name: 'tasks-lists' }">Voltar</router-link>
              </div>
              <div class="clearfix"></div>
            </div>
          </card>
        </div>
      </div>
    </div>
  </div>
</template>
<style>
</style>
