<script>
import Cookie from 'js-cookie';

export default {
  name: 'Register',

  data() {
    return {
      name: '',
      email: '',
      password: ''
    };
  },

  methods: {
    handleSubmit() {
      let formData = {
        name: this.name,
        email: this.email,
        password: this.password
      };

      axios
        .post(`/api/register`, formData)
        .then((response) => {
          this.$router.push({ name: 'Login' });

          this.$notifications.notify({
            message: `${response.data.message}`,
            horizontalAlign: 'left',
            verticalAlign: 'top',
            type: 'success'
          });
        })
        .catch((err) => {
          this.$notifications.notify({
            message: `${err.response.data.message}`,
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
  <div id="register-form-wrapper" class="text-center">
    <notifications></notifications>

    <main class="form-signin">
      <form @submit.stop.prevent="handleSubmit">
        <h1 class="h3 mb-3 fw-normal">Cadastro</h1>

        <div class="form-floating">
          <input
            id="floating-name-input"
            v-model="name"
            type="text"
            class="form-control"
            placeholder="Digite seu nome"
            required
          />
          <label for="floatingInput">Nome</label>
        </div>
        <div class="form-floating">
          <input
            id="floating-password-input"
            v-model="email"
            type="email"
            class="form-control"
            placeholder="name@example.com"
            required
          />
          <label for="floatingInput">Email</label>
        </div>
        <div class="form-floating">
          <input
            id="floatingPassword"
            v-model="password"
            type="password"
            class="form-control"
            placeholder="Password"
            required
          />
          <label for="floatingPassword">Senha</label>
        </div>

        <div class="text-right mb-3">
          <small><router-link :to="'/'">Possui usuário?</router-link></small>
        </div>

        <button class="w-100 btn btn-lg btn-success" type="submit">Finalizar</button>
      </form>
    </main>
  </div>
</template>

<style scoped>
@import url('https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css');
html,
#register-form-wrapper {
  height: 100vh;
}

#register-form-wrapper {
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
