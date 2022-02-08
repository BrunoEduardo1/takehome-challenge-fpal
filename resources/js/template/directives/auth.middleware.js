import Cookie from "js-cookie";

export default {
    run (to, from, next) {
        
        const token = Cookie.get('_task_token');

        let config = {
            headers: {
                Authorization: `Bearer ${token}`,
            }
        };

        if (!token) {
            next('/login');
        }

        axios.post('/api/me', {}, config).then(response => {

            if (!response.data || !response.data.id) {
                next('/login');
            }

        });

        next();
    },
}