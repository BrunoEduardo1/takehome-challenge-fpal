<script>
export default {
  name: 'ResetPassword',

  data() {
    return {
      email: this.$route.params.email,
      token: this.$route.params.token,
      inputDisabled: false,
      password: null,
      password_confirmation: null
    };
  },

  methods: {
    handleSubmit() {
      let formData = {
        email: this.$route.params.email,
        token: this.$route.params.token,
        password: this.password,
        password_confirmation: this.password_confirmation
      };

      axios
        .post(`/api/resetUserPassword`, formData)
        .then((response) => {
          this.$notifications.notify({
            message: `${response.data.message}`,
            horizontalAlign: 'left',
            verticalAlign: 'top',
            type: response.data.success ? 'success' : 'danger'
          });

          this.inputDisabled = true;
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
        <h1 class="h3 mb-3 fw-normal">Resetar senha</h1>

        <div class="form-floating">
          <input
            id="floating-email-input"
            v-model="email"
            type="email"
            class="form-control"
            placeholder="name@example.com"
            required
            :disabled="inputDisabled"
          />
          <label for="floating-email-input">Email</label>
        </div>

        <div class="form-floating">
          <input
            id="floating-password-input"
            v-model="password"
            type="password"
            class="form-control mb-0"
            minlength="8"
            required
          />
          <label for="floating-password-input">Senha</label>
        </div>

        <div class="form-floating">
          <input
            id="floating-password-confirmation-input"
            v-model="password_confirmation"
            type="password"
            class="form-control"
            minlength="8"
            required
          />
          <label for="floating-password-confirmation-input">Confirmar senha</label>
        </div>

        <button class="w-100 mt-3 btn btn-lg btn-success" type="submit" :disabled="inputDisabled">Salvar</button>
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
