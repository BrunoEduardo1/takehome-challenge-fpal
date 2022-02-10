<script>
export default {
  name: 'RecoverPassword',

  data() {
    return {
      email: 'bruno_test@test.com',
      inputDisabled: false
    };
  },

  methods: {
    handleSubmit() {
      let formData = {
        email: this.email
      };

      this.inputDisabled = true;

      axios
        .post(`/api/forgotPassword`, formData)
        .then((response) => {
          this.$notifications.notify({
            message: `${response.data.message}`,
            horizontalAlign: 'left',
            verticalAlign: 'top',
            type: response.data.success ? 'success' : 'danger'
          });

        })
        .catch((err) => {
          this.$notifications.notify({
            message: `${err.response.data.error}`,
            // icon: 'nc-icon nc-app',
            horizontalAlign: 'left',
            verticalAlign: 'top',
            type: 'danger'
          });
        });
    }
  }
};
</script>

<template>
  <div id="recover-form-wrapper" class="text-center">
    <notifications></notifications>

    <main class="form-signin">
      <form @submit.stop.prevent="handleSubmit">
        <h1 class="h3 mb-3 fw-normal">Recuperar senha</h1>

        <div class="form-floating">
          <input
            id="floatingInput"
            v-model="email"
            type="email"
            class="form-control"
            placeholder="name@example.com"
            required
            :disabled="inputDisabled"
          />
          <label for="floatingInput">Email</label>
        </div>

        <!-- <div class="row">
          <div class="col text-left mt-2 mb-3">
            <small><router-link :to="'/'">Voltar para o Login</router-link></small>
          </div>
        </div> -->

        <button class="w-100 mt-3 btn btn-lg btn-success" type="submit" :disabled="inputDisabled">Enviar</button>

        <router-link class="mt-3 w-100 btn btn-sm btn-light" :to="'/'">Voltar para o login</router-link>
      </form>
    </main>
  </div>
</template>

<style scoped>
@import url('https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css');
html,
#recover-form-wrapper {
  height: 100vh;
}

#recover-form-wrapper {
  display: flex;
  align-items: center;
  padding-top: 40px;
  padding-bottom: 40px;
  background-color: #f5f5f5;
}

.form-signin {
  width: 100%;
  max-width: 330px;
  padding: 15px;
  margin: auto;
}

.form-signin .checkbox {
  font-weight: 400;
}

.form-signin .form-floating:focus-within {
  z-index: 2;
}

.form-signin input[type='email'] {
  margin-bottom: -1px;
  border-bottom-right-radius: 0;
  border-bottom-left-radius: 0;
}

.form-signin input[type='password'] {
  margin-bottom: 10px;
  border-top-left-radius: 0;
  border-top-right-radius: 0;
}
</style>
