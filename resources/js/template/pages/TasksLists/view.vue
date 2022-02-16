<script>
import Cookie from 'js-cookie';
import Card from '../../components/Cards/Card.vue';
import ItemsList from '../../components/Tasks/ItemsList.vue';
import DeleteModal from '../../components/Modals/MDelete.vue';

const getHeaders = () => {
  let token = Cookie.get('_task_token');
  return  {
    headers: {
      Authorization: `Bearer ${token}`
    }
  };
};

const changeItemStatus = (hash, status) => {
  let config = getHeaders();
  axios
    .put(`api/tasks/items/update/${hash}`, { done: status }, config)
    .then((response) => {})
    .catch((err) => {
      console.log(err.data);
    });
};

export default {
  components: {
    Card,
    ItemsList,
    DeleteModal
  },
  data() {
    return {
      task: {
        id: '',
        title: '',
        description: '',
        items: [],
        itemsColumns: []
      },
      taskItem: {
        title: ''
      },
      inputDisabled: false,
      inputTaskItemDisabled: false,
      confirmDeleteId: ''
    };
  },
  mounted() {
    this.getTaskData();
  },
  methods: {
    getTaskData() {
      const taskId = this.$route.params.id;
      let config = getHeaders();
      axios
        .get(`api/tasks/${taskId}`, config)
        .then((response) => {
          if (response.data.success) {
            let responseData = response.data;

            this.task = {
              id: responseData.item.id,
              title: responseData.item.title,
              description: responseData.item.description,
              items: responseData.item.items,
              itemsColumns: [{ key: 'title', header: '' }],
              itemsActions: [
                // { key: 'done', name: 'Ver', path: 'users/view' },
              ]
            };
          } else {
            this.$router.push({ name: 'Overview' });
          }
        })
        .catch((e) => {
          this.$router.push({ name: 'Overview' });
        });
    },
    setTaskItemAsDone(hash) {
      changeItemStatus(hash, 'T');
    },
    setTaskItemAsUndone(hash) {
      changeItemStatus(hash, 'F');
    },
    setCurrentItemForConfirmation(id) {
      this.confirmDeleteId = id;
    },
    deleteConfirmedItem() {
      let config = getHeaders();
      axios
        .delete(`api/tasks/items/delete/${this.confirmDeleteId}`, config)
        .then((response) => {
          this.$notifications.notify({
            message: `${response.data.message}`,
            horizontalAlign: 'left',
            verticalAlign: 'top',
            type: response.data.success ? 'success' : 'danger'
          });

          this.getTaskData();
        })
        .catch((err) => {
          this.$notifications.notify({
            message: `${err.response.data.message}`,
            // icon: 'nc-icon nc-app',
            horizontalAlign: 'left',
            verticalAlign: 'top',
            type: 'danger'
          });
        });
    },
    handleTaskItemSubmit() {
      let config = getHeaders();

      let formData = {
        title: this.taskItem.title,
        task_list_id: this.task.id
      };

      this.inputTaskItemDisabled = true;

      axios
        .post(`/api/tasks/items/create`, formData, config)
        .then((response) => {
          this.$notifications.notify({
            message: `${response.data.message}`,
            horizontalAlign: 'left',
            verticalAlign: 'top',
            type: response.data.success ? 'success' : 'danger'
          });

          this.inputTaskItemDisabled = false;

          this.getTaskData();

          this.taskItem.title = '';
        })
        .catch((err) => {
          this.$notifications.notify({
            message: `${err.response.data.message}`,
            horizontalAlign: 'left',
            verticalAlign: 'top',
            type: 'danger'
          });

          if (err.response.data.errors) {
            this.inputTaskItemDisabled = false;
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
            <h4 slot="header" class="card-title">Lista de tarefas - {{ task.title }}</h4>
            <p slot="header" class="card-category">{{ task.description }}</p>
            <div>
              <items-list
                v-if="task.items.length"
                :tableData="{ data: task.items, columns: task.itemsColumns, actions: task.itemsActions }"
                @set-task-as-done="setTaskItemAsDone"
                @set-task-as-undone="setTaskItemAsUndone"
                @delete-item-id="setCurrentItemForConfirmation"
              ></items-list>
              <div class="alert alert-primary text-center" v-if="!task.items.length" role="alert">
                <i class="bi bi-info-circle"></i> Nenhuma tarefa adicionada a lista
              </div>
              <div class="mt-0">
                <form>
                  <div class="row justify-content-sm-center">
                    <div class="col-sm-5">
                      <base-input
                        type="text"
                        label=""
                        placeholder="Nova tarefa"
                        v-model="taskItem.title"
                        :disabled="inputTaskItemDisabled"
                        required="true"
                      >
                      </base-input>
                    </div>
                    <div class="col-sm-auto">
                      <button
                        type="submit"
                        class="btn btn-primary btn-fill"
                        @click.prevent="handleTaskItemSubmit"
                        :disabled="inputTaskItemDisabled"
                      >
                        Adicionar
                      </button>
                    </div>
                  </div>
                  <div class="clearfix"></div>
                </form>
              </div>
              <div class="font-weight-bold mt-2">
                <router-link class="btn btn-secondary btn-fill float-left" :to="{ name: 'tasks-lists' }"
                  >Voltar</router-link
                >
              </div>
              <div class="clearfix"></div>
            </div>
          </card>
        </div>
      </div>
    </div>
    <delete-modal
      :item="confirmDeleteId"
      title="Deletar item da lista?"
      @delete-confirmed="deleteConfirmedItem"
    ></delete-modal>
  </div>
</template>
<style></style>
