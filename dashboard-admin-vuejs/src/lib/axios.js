/**
 * Created Emerson Sanchez Hernandez on 15/01/2024.
 */
import axios from "axios";
import store from "../store";
import router from "../router/index";

// Create axios instance
const axiosClient = axios.create({
  // Base URL for API requests
  baseURL: `${import.meta.env.VITE_API_BASE_URL}/api`,
  // Include credentials in requests
  withCredentials: true,
});

// Request interceptor to add Auth+orization header with token
axiosClient.interceptors.request.use(config => {
  // Add Authorization header with token from store
  config.headers.Authorization = `Bearer ${store.state.user.token}`;
  return config;
});

// Response interceptor to handle token expiration
axiosClient.interceptors.response.use(response => {
  // Return response if status is not 401 (unauthorized)
  return response;
}, error => {
  // Handle 401 (unauthorized) error
  if (error.response.status === 401) {
    // Clear token in store
    store.commit('setToken', null);
    // Redirect user to login page
    router.push({ name: 'login' });
  }
 
  throw error;
});


export default axiosClient;
