


<style scoped>
@import url("https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css");
html,
body {
  height: 100%;
}

body {
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

.form-signin input[type="email"] {
  margin-bottom: -1px;
  border-bottom-right-radius: 0;
  border-bottom-left-radius: 0;
}

.form-signin input[type="password"] {
  margin-bottom: 10px;
  border-top-left-radius: 0;
  border-top-right-radius: 0;
}
</style>

<template>
  <div id="login-form-wrapper" class="text-center">
    <notifications></notifications>

    <main class="form-signin">
      <form @submit.stop.prevent="handleSubmit">
        <h1 class="h3 mb-3 fw-normal">Please sign in</h1>

        <div class="form-floating">
          <input
            type="email"
            class="form-control"
            id="floatingInput"
            placeholder="name@example.com"
            required
            v-model="email"
          />
          <label for="floatingInput">Email address</label>
        </div>
        <div class="form-floating">
          <input
            type="password"
            class="form-control"
            id="floatingPassword"
            placeholder="Password"
            required
            v-model="password"
          />
          <label for="floatingPassword">Password</label>
        </div>

        <div class="checkbox mb-3">
          <label>
            <input type="checkbox" value="remember-me" /> Remember me
          </label>
        </div>
        <button class="w-100 btn btn-lg btn-primary" type="submit">
          Entrar
        </button>
      </form>
    </main>
  </div>
</template>


<script>
export default {
  name: "Login",

  data() {
    return {
      email: "",
      password: "",
    };
  },

  methods: {
    handleSubmit() {
      let formData = {
        email: this.email,
        password: this.password,
      };

      axios
        .post(`/api/login`, formData)
        .then((response) => {

          this.$router.push({ name: 'Overview' });

          this.$notifications.notify({
            message: `Welcome`,
            horizontalAlign: 'left',
            verticalAlign: 'top',
            type: 'success',
          });
        })
        .catch((err) => {
          this.$notifications.notify({
            message: `${err.response.data.error}`,
            // icon: 'nc-icon nc-app',
            horizontalAlign: 'left',
            verticalAlign: 'top',
            type: 'danger',
          });
        });
    },
  },
};
</script>