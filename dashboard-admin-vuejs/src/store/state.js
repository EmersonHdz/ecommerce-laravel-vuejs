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
    countries: [],
    orders: {
       loading: false,
       data: [],
       links: [],
       from: null,
       to: null,
       page: 1,
       limit: null,
       total: null, 
       search: '',
       sort_field: 'updated_at',
       sort_direction: 'desc'
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