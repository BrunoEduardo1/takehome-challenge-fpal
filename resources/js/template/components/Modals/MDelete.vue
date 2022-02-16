<script>
export default {
  name: 'DeleteModal',
  props: {
    title: String,
    body: String,
    item: String
  },
  emits: ['delete-confirmed'],
  data() {
    return {};
  },
  methods: {
    deleteConfirmed() {
      this.$emit('delete-confirmed', this.item);
    }
  }
};
</script>

<template>
  <div
    id="confirm-delete-modal"
    class="modal fade"
    tabindex="-1"
    role="dialog"
    aria-labelledby="confirm-delete-modal"
    aria-hidden="true"
  >
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 id="confirm-delete-modal-title" class="modal-title">{{ title }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div v-if="body" class="modal-body">{{ body }}</div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-success" data-dismiss="modal" @click="deleteConfirmed">Confirmar</button>
        </div>
      </div>
    </div>
  </div>
</template>
