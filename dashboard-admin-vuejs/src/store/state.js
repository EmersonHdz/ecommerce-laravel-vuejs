export default {
    resetPassword: {
        loading: false,
        successMessage: "",
        errorMessage: "",
    },

    user: {
        token: localStorage.getItem('TOKEN'),
        data: {}
    },
    products: {
        loading: false,
        data: [],
        links: [],
        from: null,
        to: null,
        page: 1,
        limit: null,
        total: null
    },
    users: {
        loading: false,
        data: [],
        links: [],
        from: null,
        to: null,
        page: 1,
        limit: null,
        total: null
      },
    toast: {
        show: false,
        message: '',
        delay: 5000
    },
}