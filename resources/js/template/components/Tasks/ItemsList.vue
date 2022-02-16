<script>
import LTable from '../Table.vue';

export default {
  name: 'ItemsList',
  components: {
    LTable
  },
  props: {
    tableData: Object,
    title: String,
    description: String,
    footerData: String
  },
  emits: ['set-task-as-done', 'set-task-as-undone', 'delete-item-id'],
  methods: {
    changeItemStatusEvent(item, status) {
      if (status == 'F') {
        this.$emit('set-task-as-done', item);
      } else {
        this.$emit('set-task-as-undone', item);
      }
    },
    clickListener(item) {
      this.$emit('delete-item-id', item);
    }
  }
};
</script>

<template>
  <card>
    <template slot="header">
      <h5 v-if="title" class="title">{{ title }}</h5>
      <p v-if="description" class="category">{{ description }}</p>
    </template>
    <l-table v-if="tableData.data" :data="tableData.data" :columns="tableData.columns" :actions="tableData.actions">
      <template slot-scope="{ row }">
        <td>
          <div class="form-check">
            <input
              :id="'task-item-check-' + row.hash"
              v-model="row.done"
              true-value="T"
              false-value="F"
              class="form-check-input"
              type="checkbox"
              value="F"
              style="opacity: 1; visibility: visible"
              :checked="row.done == 'T'"
              @click="changeItemStatusEvent(row.hash, row.done)"
            />
            <label class="form-check-label" :for="'task-item-check-' + row.hash" style="padding-left: 5px">
              {{ row.title }}
            </label>
          </div>
          <div style="position: absolute; right: 0; top: 16px">
            <div>
              <router-link
                class="btn btn-primary btn-sm mr-2"
                :to="{ name: 'edit-task-item', params: { id: row.id } }"
                title="Editar"
              >
                <i class="bi bi-pencil-square" aria-label="Editar"></i
              ></router-link>
              <router-link
                class="btn btn-danger btn-sm mr-2"
                data-toggle="modal"
                data-target="#confirm-delete-modal"
                :to="`api/tasks/items/delete/${row.hash}`"
                event=""
                title="Deletar"
                @click.native="clickListener(row.hash)"
              >
                <i class="bi bi-trash" aria-label="Deletar"></i
              ></router-link>
            </div>
          </div>
        </td>
      </template>
    </l-table>
    <div v-if="footerData" class="footer">
      <hr />
      <div class="stats"><i class="fa fa-history"></i> {{ footerData }}</div>
    </div>
  </card>
</template>
