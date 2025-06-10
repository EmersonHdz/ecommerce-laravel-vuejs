/**
 * Mutations to update the Vuex state.
 * These functions modify the values stored in the Vuex store.
 */ 
export function clearErrorMessage(state) {
  state.resetPassword.errorMessage = "";
}
export function clearSuccessMessage(state) {
  state.resetPassword.successMessag = "";
}

export function setErrorMessage(state, message) {
  state.resetPassword.errorMessage = message;
}

export function setSuccessMessage(state, message) {
  state.resetPassword.successMessage = message;
}


/**
 * Updates the user information in the state.
 * @param {Object} state - Global Vuex state.
 * @param {Object} user - User data retrieved from the API.
 */
export function setUser(state, user) {
  state.user.data = user;
}

/**
* Stores or removes the authentication token in the state and localStorage.
* @param {Object} state - Global Vuex state.
* @param {string|null} token - Authentication token (null if the user logs out).
*/
export function setToken(state, token) {
  state.user.token = token;
  if (token) {
      localStorage.setItem('TOKEN', token); // Store the token in localStorage
  } else {
      localStorage.removeItem('TOKEN'); // Remove the token if null
  }
}

/**
* Updates the product list in the state along with pagination information.
* @param {Object} state - Global Vuex state.
* @param {Array} payload - Array with [loading, data] where:
*   - loading (boolean) indicates if the request is in progress.
*   - data (Object|null) contains the products retrieved from the API.
*/
export function setProducts(state, [loading, data = null]) {
  if (data) {
      state.products = {
          ...state.products,
          data: data.data, // Product list
          links: data.meta?.links, // Pagination links
          page: data.meta.current_page, // Current page
          limit: data.meta.per_page, // Products per page
          from: data.meta.from, // First product on the current page
          to: data.meta.to, // Last product on the current page
          total: data.meta.total, // Total number of products
      };
  }
  state.products.loading = loading; // Update loading state
}

/**
* Updates the user list in the state along with pagination information.
* @param {Object} state - Global Vuex state.
* @param {Array} payload - Array with [loading, data] where:
*   - loading (boolean) indicates if the request is in progress.
*   - data (Object|null) contains the users retrieved from the API.
*/
export function setUsers(state, [loading, data = null]) {
  if (data) {
      state.users = {
          ...state.users,
          data: data.data, // User list
          links: data.meta?.links, // Pagination links
          page: data.meta.current_page, // Current page
          limit: data.meta.per_page, // Users per page
          from: data.meta.from, // First user on the current page
          to: data.meta.to, // Last user on the current page
          total: data.meta.total, // Total number of users
      };
  }
  state.users.loading = loading; // Update loading state
}


/**==================ORDERS AREA =============== */
export function setOrders(state, [loading, data = null]) {

  if (data) {
    state.orders = {
      ...state.orders,
      data: data.data,
      links: data.meta?.links,
      page: data.meta.current_page,
      limit: data.meta.per_page,
      from: data.meta.from,
      to: data.meta.to,
      total: data.meta.total,
    }
  }
  state.orders.loading = loading;
}
/**
* Displays a toast notification in the UI.
* @param {Object} state - Global Vuex state.
* @param {string} message - Message to be displayed in the toast notification.
*/
export function showToast(state, message) {
  state.toast.show = true; // Show the toast
  state.toast.message = message; // Set the toast message
}

/**
* Hides the toast notification.
* @param {Object} state - Global Vuex state.
*/
export function hideToast(state) {
  state.toast.show = false; // Hide the toast
  state.toast.message = ''; // Clear the message
}

