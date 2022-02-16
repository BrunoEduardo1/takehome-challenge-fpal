<script>
import Cookie from 'js-cookie';
import LTable from '../../components/Table.vue';
import Pagination from '../../components/Pagination.vue';
import Card from '../../components/Cards/Card.vue';
import DeleteModal from '../../components/Modals/MDelete.vue';
const tableColumns = [
  { key: 'id', header: 'Id' },
  { key: 'name', header: 'Nome' },
  { key: 'Email', header: 'Email' },
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
      title: 'Usuários',
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
        .delete(`api/users/delete/${this.confirmDeleteId}`, config)
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
            // icon: 'nc-icon nc-app',
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
        .get(`api/users?page=${page}`, config)
        .then((response) => {
          if (response.data.success) {
            let responseData = response.data;

            this.table.data = responseData.items.data;

            this.table.pagination = responseData.items;
            this.table.actions = [
              { key: 'view', name: 'Ver', path: 'users/view' },
              { key: 'edit', name: 'Editar', path: 'users/edit' },
              { key: 'delete', name: 'Deletar', path: 'users' }
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
                  <router-link class="btn btn-sm btn-secondary btn-fill float-right" :to="{ name: 'create-user' }"
                    >Novo</router-link
                  >
                </div>
              </div>
            </template>
            <div class="table-responsive">
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
          </card>
        </div>
      </div>
    </div>
    <delete-modal
      :item="confirmDeleteId"
      title="Deletar usuário?"
      @delete-confirmed="deleteConfirmedItem"
    ></delete-modal>
  </div>
</template>
<style></style>
