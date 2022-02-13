<template>
  <nav>
    <ul class="pagination">
      <li v-if="pagination.current_page > 1" class="page-item">
        <a
          class="page-link"
          href="javascript:void(0)"
          aria-label="Previous"
          @click.prevent="changePage(pagination.current_page - 1)"
        >
          <span aria-hidden="true">«</span>
        </a>
      </li>
      <li
        v-for="page in pagesNumber"
        :key="page"
        class="page-item"
        :class="{ active: page == pagination.current_page }"
      >
        <a class="page-link" href="javascript:void(0)" @click.prevent="changePage(page)">{{ page }}</a>
      </li>
      <li v-if="pagination.current_page < pagination.last_page" class="page-item">
        <a
          class="page-link"
          href="javascript:void(0)"
          aria-label="Next"
          @click.prevent="changePage(pagination.current_page + 1)"
        >
          <span aria-hidden="true">»</span>
        </a>
      </li>
    </ul>
  </nav>
</template>
<script>
export default {
  name: 'pagination',
  props: {
    pagination: {
      type: Object,
      required: true
    },
    offset: {
      type: Number,
      default: 4
    }
  },
  computed: {
    pagesNumber() {
      if (!this.pagination.to) {
        return [];
      }
      let from = this.pagination.current_page - this.offset;
      if (from < 1) {
        from = 1;
      }
      let to = from + this.offset * 2;
      if (to >= this.pagination.last_page) {
        to = this.pagination.last_page;
      }
      let pagesArray = [];
      for (let page = from; page <= to; page++) {
        pagesArray.push(page);
      }
      return pagesArray;
    }
  },
  methods: {
    changePage(page) {
      this.pagination.current_page = page;
      this.$emit('paginate', page);
    }
  }
};
</script>