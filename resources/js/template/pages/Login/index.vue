<script>
import Cookie from 'js-cookie';

export default {
  name: 'Login',

  data() {
    return {
      email: '',
      password: ''
    };
  },

  methods: {
    handleSubmit() {
      let formData = {
        email: this.email,
        password: this.password
      };

      axios
        .post(`/api/login`, formData)
        .then((response) => {
          Cookie.set('_task_token', response.data.access_token);

          this.$router.push({ name: 'Overview' });

          this.$notifications.notify({
            message: `Bem vindo`,
            horizontalAlign: 'left',
            verticalAlign: 'top',
            type: 'success'
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
  <div id="login-form-wrapper" class="text-center">
    <notifications></notifications>

    <main class="form-signin">
      <form @submit.stop.prevent="handleSubmit">
        <h1 class="h3 mb-3 fw-normal">Login</h1>

        <div class="form-floating">
          <input
            id="floatingInput"
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

        <!-- <div class="checkbox mb-3">
          <label>
            <input type="checkbox" value="remember-me" /> Lembre-me
          </label>
        </div> -->
        <div class="row">
          <div class="col text-left mb-3">
            <small><router-link :to="'/forgotPassword'">Recuperar senha</router-link></small>
          </div>
          <div class="col text-right mb-3">
            <small><router-link :to="'/register'">Criar conta</router-link></small>
          </div>
        </div>

        <button class="w-100 btn btn-lg btn-primary" type="submit">Entrar</button>
      </form>
    </main>
  </div>
</template>

<style scoped>
@import url('https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css');
html,
#login-form-wrapper {
  height: 100vh;
}

#login-form-wrapper {
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
