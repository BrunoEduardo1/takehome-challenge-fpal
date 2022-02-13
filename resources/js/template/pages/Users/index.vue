<script>
import Cookie from 'js-cookie';
import LTable from '../../components/Table.vue';
import Pagination from '../../components/Pagination.vue';
import Card from '../../components/Cards/Card.vue';
const tableColumns = [
  { key: 'id', header: 'Id' },
  { key: 'name', header: 'Nome' },
  { key: 'Email', header: 'Email' },
  { key: 'created_at', header: 'Criado em' }
];

export default {
  components: {
    LTable,
    Card,
    Pagination
  },
  data() {
    return {
      title: 'Usuários',
      table: {
        columns: [...tableColumns],
        data: [],
        pagination: {}
      }
    };
  },
  mounted() {
    this.getTableData();
  },
  methods: {
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
          } else {
            this.$router.push({ name: 'Overview' });
          }
        })
        .catch((e) => {});
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
              <h4 class="card-title">{{ title }}</h4>
            </template>
            <div class="table-responsive">
              <l-table class="table-hover" :columns="table.columns" :data="table.data"> </l-table>
              <div class="text-right">
                <pagination :pagination="table.pagination" @paginate="getTableData"></pagination>
              </div>
            </div>
          </card>
        </div>
      </div>
    </div>
  </div>
</template>
<style></style>