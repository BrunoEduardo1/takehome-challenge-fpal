import Cookie from 'js-cookie';

export default {
  run(to, from, next) {
    const token = Cookie.get('_task_token');
    const isToLoginPage = to.name == 'Login';
    const isToRegisterPage = to.name == 'Register';

    let config = {
      headers: {
        Authorization: `Bearer ${token}`
      }
    };

    if (!token) {
      if (isToLoginPage || isToRegisterPage) {
        next();
      } else {
        next('/');
      }
      return;
    }

    axios
      .post('/api/me', {}, config)
      .then((response) => {
        if (!response.data || !response.data.id) {
          if (isToLoginPage) {
            next();
          } else {
            next('/');
          }
          return;
        }
      })
      .catch((e) => {
        Cookie.remove('_task_token');
        next('/');
        return;
      });

    if (isToLoginPage || isToRegisterPage) {
      next('/admin/overview');
      return;
    }

    next();
  }
};
