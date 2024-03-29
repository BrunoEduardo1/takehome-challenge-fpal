<script>
import Cookie from 'js-cookie';
import LTable from '../../components/Table.vue';
import Pagination from '../../components/Pagination.vue';
import Card from '../../components/Cards/Card.vue';
import DeleteModal from '../../components/Modals/MDelete.vue';
const tableColumns = [
  { key: 'id', header: 'Id' },
  { key: 'title', header: 'Título' },
  { key: 'description', header: 'Descrição' },
  { key: 'formatted_created_at', header: 'Criado em', type: 'Datetime' }
];

export default {
  components: {
    LTable,
    Card,
    Pagination,
    DeleteModal
  },
  data() {
    return {
      title: 'Listas de tarefas',
      table: {
        columns: [...tableColumns],
        data: [],
        pagination: {},
        actions: []
      },
      confirmDeleteId: ''
    };
  },
  mounted() {
    this.getTableData();
  },
  methods: {
    setCurrentItemForConfirmation(id) {
      this.confirmDeleteId = id;
    },
    deleteConfirmedItem(id) {
      const token = Cookie.get('_task_token');
      let config = {
        headers: {
          Authorization: `Bearer ${token}`
        }
      };
      axios
        .delete(`api/tasks/delete/${this.confirmDeleteId}`, config)
        .then((response) => {
          this.$notifications.notify({
            message: `${response.data.message}`,
            horizontalAlign: 'left',
            verticalAlign: 'top',
            type: response.data.success ? 'success' : 'danger'
          });

          this.getTableData(this.table.pagination.current_page);
        })
        .catch((err) => {
          this.$notifications.notify({
            message: `${err.response.data.message}`,
            horizontalAlign: 'left',
            verticalAlign: 'top',
            type: 'danger'
          });
        });
    },
    getTableData: function (page = 1) {
      const token = Cookie.get('_task_token');
      let config = {
        headers: {
          Authorization: `Bearer ${token}`
        }
      };
      axios
        .get(`api/tasks?page=${page}`, config)
        .then((response) => {
          if (response.data.success) {
            let responseData = response.data;

            this.table.data = responseData.items.data;

            this.table.pagination = responseData.items;
            this.table.actions = [
              { key: 'view', name: 'Ver', path: 'tasks/view' },
              { key: 'edit', name: 'Editar', path: 'tasks/edit' },
              { key: 'delete', name: 'Deletar', path: 'tasks' }
            ];
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
        <div class="col-12">
          <card class="card-plain">
            <template slot="header">
              <div class="row">
                <div class="col">
                  <h4 class="card-title">{{ title }}</h4>
                </div>
                <div class="col">
                  <router-link class="btn btn-sm btn-secondary btn-fill float-right" :to="{ name: 'create-task' }"
                    >Criar</router-link
                  >
                </div>
              </div>
            </template>
            <div v-if="table.data.length" class="table-responsive">
              <l-table
                class="table-hover"
                :columns="table.columns"
                :data="table.data"
                :actions="table.actions"
                @delete-item-id="setCurrentItemForConfirmation"
              >
              </l-table>
              <div class="text-right">
                <pagination :pagination="table.pagination" @paginate="getTableData"></pagination>
              </div>
            </div>
            <div v-if="!table.data.length">
              <div class="alert alert-primary text-center" role="alert">
                <i class="bi bi-info-circle"></i> Nenhuma lista de tarefas cadastrada
              </div>
            </div>
          </card>
        </div>
      </div>
    </div>
    <delete-modal
      :item="confirmDeleteId"
      title="Deletar lista de tarefas e seus itens?"
      @delete-confirmed="deleteConfirmedItem"
    ></delete-modal>
  </div>
</template>
<style></style>
