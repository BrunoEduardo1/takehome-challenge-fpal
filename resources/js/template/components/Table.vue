<template>
  <table class="table">
    <thead>
      <slot name="columns">
        <tr>
          <th v-for="column in columns" :key="column.key">{{ column.header }}</th>
          <th v-if="actions">Ações</th>
        </tr>
      </slot>
    </thead>
    <tbody>
      <tr v-for="(item, index) in data" :key="index">
        <slot :row="item">
          <td v-for="column in columns" :key="column.key" v-if="hasValue(item, column.key)">
            {{ itemValue(item, column.key) }}
          </td>
          <td v-if="actions">
            <router-link
              v-for="action in actions"
              :key="action.key"
              class="btn btn-primary btn-sm mr-2"
              :class="action.key == 'delete' ? 'btn-danger' : ''"
              :data-toggle="action.key == 'delete' ? 'modal' : ''"
              :data-target="action.key == 'delete' ? '#confirm-delete-modal' : ''"
              :to="`${action.path}/${item.id}`"
              event=""
              @click.native="clickListener(action.key, `${action.path}/${item.id}`, item.id)"
              >{{ action.name }}</router-link
            >
          </td>
        </slot>
      </tr>
    </tbody>
  </table>
</template>
<script>
export default {
  name: 'l-table',
  props: {
    columns: {
      type: Array,
      default: () => {
        return [{ key: 'Id', header: 'id' }];
      }
    },
    data: {
      type: Array,
      default: () => {
        return [];
      }
    },
    actions: {
      type: Array,
      default: () => {
        return [];
      }
    }
  },
  methods: {
    hasValue(item, column) {
      return item[column.toLowerCase()] !== 'undefined';
    },
    itemValue(item, column) {
      return item[column.toLowerCase()];
    },
    clickListener(actionKey, route, item) {
      if (actionKey.toLowerCase() !== 'delete') {
        this.$router.push(route);
      } else {
        this.$emit('delete-item-id', item);
      }
    }
  }
};
</script>
<style>
</style>
