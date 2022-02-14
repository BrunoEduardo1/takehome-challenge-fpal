<script>
export default {
  name: 'DeleteModal',
  props: {
    title: '',
    body: '',
    item: ''
  },
  data() {
    return {};
  },
  methods: {
    deleteConfirmed() {
      console.log('deleteConfirmedMotherFucker', this.item);
      this.$emit('delete-confirmed', this.item);
    }
  }
};
</script>

<template>
  <!-- Modal -->
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
          <h5 class="modal-title" id="confirm-delete-modal-title">{{ title }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" v-if="body">{{ body }}</div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-success" data-dismiss="modal" @click="deleteConfirmed">Confirmar</button>
        </div>
      </div>
    </div>
  </div>
</template>