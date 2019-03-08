class Auth {
    constructor () {
        this.token = window.localStorage.getItem('token');

        let userData = window.localStorage.getItem('user');
        this.user = userData ? JSON.parse(userData) : {};

        if (this.token) {
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.token;
            this.getUser();
        }
    }

    login (token, user) {
        window.localStorage.setItem('token', token);
        window.localStorage.setItem('user', JSON.stringify(user));

        axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;

        this.token = token;
        this.user = user;

        Event.$emit('userLoggedIn');
    }

    logout () {
        window.localStorage.removeItem('token');
        window.localStorage.removeItem('user');

        delete axios.defaults.headers.common['Authorization'];

        this.token = null;
        this.user = null;

        Event.$emit('userLoggedOut');
    }

    getUser() {
        api.call('get', '/api/user')
            .then(({data}) => {
                this.user = data;
            });
    }

    check () {
        return !! this.token;
    }
}

export default Auth;
