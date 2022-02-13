<template>
  <table class="table">
    <thead>
      <slot name="columns">
        <tr>
          <th v-for="column in columns" :key="column.key">{{ column.header }}</th>
        </tr>
      </slot>
    </thead>
    <tbody>
      <tr v-for="(item, index) in data" :key="index">
        <slot :row="item">
          <td v-for="column in columns" :key="column.key" v-if="hasValue(item, column.key)">
            {{ itemValue(item, column.key) }}
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
    }
  },
  methods: {
    hasValue(item, column) {
      return item[column.toLowerCase()] !== 'undefined';
    },
    itemValue(item, column) {
      return item[column.toLowerCase()];
    }
  }
};
</script>
<style>
</style>
