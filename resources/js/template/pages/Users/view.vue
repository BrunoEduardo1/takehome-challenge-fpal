<script>
import Cookie from 'js-cookie';
import Card from '../../components/Cards/Card.vue';

export default {
  components: {
    Card
  },
  data() {
    return {
      user: {
        name: '',
        email: '',
        password: ''
      },
      inputDisabled: false
    };
  },
  mounted() {
    this.getUserData();
  },
  methods: {
    getUserData() {
      // buscar do back
      const token = Cookie.get('_task_token');
      const userId = this.$route.params.id;
      let config = {
        headers: {
          Authorization: `Bearer ${token}`
        }
      };
      axios
        .get(`api/users/${userId}`, config)
        .then((response) => {
          if (response.data.success) {
            let responseData = response.data;

            this.user = {
              name: responseData.item.name,
              email: responseData.item.email
            };
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
        <div class="col-md-12">
          <card>
            <h4 slot="header" class="card-title">Usuário</h4>
            <div>
              <ul class="list-group list-group-flush">
                <li class="list-group-item"><span class="text-bold">Nome:</span> {{user.name}}</li>
                <li class="list-group-item"><span class="text-bold">Email:</span> {{user.email}}</li>
              </ul>
              <div class="font-weight-bold mt-5">
                <router-link class="btn btn-secondary btn-fill float-left" :to="{ name: 'users' }">Voltar</router-link>
              </div>
              <div class="clearfix"></div>
            </div>
          </card>
        </div>
      </div>
    </div>
  </div>
</template>
<style>
</style>
