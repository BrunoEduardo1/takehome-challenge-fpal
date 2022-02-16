<script>
import ItemsList from '../components/Tasks/ItemsList.vue';
import StatsCard from '../components/Cards/StatsCard.vue';
import Cookie from 'js-cookie';

const changeItemStatus = (hash, status) => {
  const token = Cookie.get('_task_token');
  let config = {
    headers: {
      Authorization: `Bearer ${token}`
    }
  };
  axios
    .put(`api/tasks/items/update/${hash}`, { done: status }, config)
    .then((response) => {})
    .catch((err) => {
      console.log(err.data);
    });
};

export default {
  components: {
    StatsCard,
    ItemsList
  },
  data() {
    return {
      usersCount: 0,
      tasksListsCount: 0,
      pendingTasksCount: 0,
      finishedTasksCount: 0,
      tasksLists: []
    };
  },
  mounted() {
    this.getData();
  },
  methods: {
    getData() {
      const token = Cookie.get('_task_token');
      let config = {
        headers: {
          Authorization: `Bearer ${token}`
        }
      };
      axios
        .get(`api/dashboard/getData`, config)
        .then((response) => {
          if (response.data.success) {
            let responseData = response.data.items;
            this.usersCount = responseData.users_count;
            this.tasksListsCount = responseData.tasks_lists_count;
            this.pendingTasksCount = responseData.pending_tasks_count;
            this.finishedTasksCount = responseData.finished_tasks_count;

            this.tasksLists = responseData.tasks_lists;
          }
        })
        .catch((err) => {
          if (err.response.status == 401) {
            this.$router.push({ name: 'Login' });
          } else {
            console.log('Error getting the dashboard data:', err.message);
          }
        });
    },
    setTaskItemAsDone(hash) {
      changeItemStatus(hash, 'T');
      this.getData();
    },
    setTaskItemAsUndone(hash) {
      changeItemStatus(hash, 'F');
      this.getData();
    }
  }
};
</script>

<template>
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-xl-3 col-md-6">
          <stats-card>
            <div slot="header" class="icon-warning">
              <i class="bi bi-people-fill"></i>
            </div>
            <div slot="content">
              <p class="card-category">Usuários</p>
              <h4 class="card-title">{{ usersCount }}</h4>
            </div>
          </stats-card>
        </div>

        <div class="col-xl-3 col-md-6">
          <stats-card>
            <div slot="header" class="icon-success">
              <i class="bi bi-list"></i>
            </div>
            <div slot="content">
              <p class="card-category">Listas de Tarefas</p>
              <h4 class="card-title">{{ tasksListsCount }}</h4>
            </div>
          </stats-card>
        </div>

        <div class="col-xl-3 col-md-6">
          <stats-card>
            <div slot="header" class="icon-danger">
              <i class="bi bi-list-ul"></i>
            </div>
            <div slot="content">
              <p class="card-category">Tarefas pendentes</p>
              <h4 class="card-title">{{ pendingTasksCount }}</h4>
            </div>
          </stats-card>
        </div>

        <div class="col-xl-3 col-md-6">
          <stats-card>
            <div slot="header" class="icon-info">
              <i class="bi bi-list-check"></i>
            </div>
            <div slot="content">
              <p class="card-category">Tarefas concluídas</p>
              <h4 class="card-title">{{ finishedTasksCount }}</h4>
            </div>
          </stats-card>
        </div>
      </div>
      <div v-if="!tasksLists.length" class="row">
        <div class="col-12 mb-2">
          <h5><i class="bi bi-list"></i>Listas de tarefas mais recentes</h5>
          <div class="alert alert-primary text-center" role="alert">
            <i class="bi bi-info-circle"></i> Nenhuma lista de tarefas cadastrada
          </div>
        </div>
      </div>
      <div v-if="tasksLists.length" class="row">
        <div class="col-12 mb-2">
          <h5><i class="bi bi-list"></i>Listas de tarefas mais recentes</h5>
        </div>
        <div :key="taskList.id" v-for="taskList in tasksLists" class="col-md-6">
          <card>
            <template slot="header">
              <h5 class="title">{{ taskList.title }}</h5>
              <p class="category">
                {{
                  taskList.description.length > 95
                    ? taskList.description.substring(0, 95) + '...'
                    : taskList.description
                }}
              </p>

              <div>
                <items-list
                  v-if="taskList.items.length"
                  :tableData="{ data: taskList.items, columns: [{ key: 'title', header: '' }], actions: [] }"
                  :hideDeleButton="true"
                  @set-task-as-done="setTaskItemAsDone"
                  @set-task-as-undone="setTaskItemAsUndone"
                ></items-list>
                <div class="alert alert-primary text-center" v-if="!taskList.items.length" role="alert">
                  <i class="bi bi-info-circle"></i> Nenhuma tarefa adicionada a lista
                </div>
              </div>
            </template>
          </card>
        </div>
      </div>
    </div>
  </div>
</template>
<style></style>
