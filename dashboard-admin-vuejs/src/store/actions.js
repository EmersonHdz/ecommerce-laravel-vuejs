import axiosClient from "../lib/axios";

/**
 * 
 */
  

  export async function requestPasswordReset({ commit }, email) {
    commit("setErrorMessage", "");
    commit("setSuccessMessage", "");

    try {
      const response = await axiosClient
        .post("/forgot-password", { email });
      commit("setSuccessMessage", response.data.message);
    } catch (error) {
      commit("setErrorMessage", error.response?.data?.error || "Something went wrong.");
    }
  }


  export function clearError({ commit }) {
    commit("clearErrorMessage");
  }

  export function clearSuccess({ commit }) {
    commit("clearSuccessMessage");
  }

/**===================================================================PRDUCTS AREA ================================= */
/**
 * Fetches the current authenticated user from the server.
 *
 * @param {Object} context - Vuex context object, includes commit function.
 * @param {Object} data - Optional request parameters.
 * @returns {Promise} - Axios GET request response containing user data.
 */
export function getCurrentUser({ commit }, data) {
  return axiosClient.get('/user', data)
    .then(({ data }) => {
      commit('setUser', data); // Store the user data in Vuex state
      return data; // Return user data for further use
    });
}

/**
 * Authenticates a user by sending login credentials and stores user information.
 *
 * @param {Object} context - Vuex context object, includes commit function.
 * @param {Object} data - User login credentials (email, password, etc.).
 * @returns {Promise<Object>} - The response containing user data and authentication token.
 * @throws {Error} - Throws an error if login fails.
 */
export async function login({ commit }, data) {
  try {
    const response = await axiosClient.post('/login', data);
    const responseData = response.data;

    commit('setUser', responseData.user); // Store user details in Vuex state
    commit('setToken', responseData.token); // Store authentication token

    return responseData; // Return user data and token for further use
  } catch (error) {
    // Handle login error (e.g., incorrect credentials, server issues)
    throw error;
  }
}

/**
 * Logs out the current user by invalidating their session.
 *
 * @param {Object} context - Vuex context object, includes commit function.
 * @returns {Promise} - Axios POST request response confirming logout.
 */
export async function logout({ commit }) {
  const response = await axiosClient.post('/logout');

  commit('setToken', null); // Clear authentication token from Vuex state

  return response; // Return response for further handling if needed
}


/**===================================================================PRDUCTS AREA ================================= */


/**
 * Retrieves a list of products from the database.
 * @param {Object} context - Vuex context object, includes commit function.
 * @param {Object} options - Optional parameters to customize the request.
 * @param {String} options.url - URL to send the request to.
 * @param {String} options.search - Search query to filter products.
 * @param {Number} options.per_page - Number of products to retrieve per page.
 * @param {String} options.sort_field - Field to sort the products by.
 * @param {String} options.sort_direction - Direction to sort the products by.
 * @returns {Promise} - Axios GET request response.
 * 
 */
export async function getProducts({commit, state}, {url = null, search = '', per_page, sort_field, sort_direction} = {}) {
  commit('setProducts', [true])
  url = url || '/products'
  const params = {
    per_page: state.products.limit,
  }
  return axiosClient.get(url, {
    params: {
      ...params,
      search, per_page, sort_field, sort_direction
    }
  })
    .then((response) => {
      commit('setProducts', [false, response.data])
    })
    .catch(() => {
      commit('setProducts', [false])
    })
}


/**
 * Retrieves A SINGLE product from the database using its ID. 
 * @param {Object} context - Vuex context object, includes commit function.
 * @param {Number} id - Product ID to retrieve.
 * @returns {Promise} - Axios GET request response.
 */
export function getProduct({commit}, id) {
  return axiosClient.get(`/products/${id}`)
}




/**
 * CREATE a new product using a FormData object to send data to the server.
 * This function handles creating a new product with all required fields.
 * @param {Object} context - Vuex context object, includes commit function.
 * @param {Object} product - Product object containing all required fields.
 * @returns {Promise} - Axios POST request response. 
 * 
 */
export function createProduct({ commit }, product) {
  const form = new FormData();

  form.append("title", product.title);
  form.append("description", product.description || "");
  form.append("published", product.published ? 1 : 0);
  form.append("price", product.price);
  form.append("quantity", product.quantity);

  if (product.images && product.images.length) {
    product.images.forEach((im) => {
      if (im instanceof File) { // Skip images that are not files
        form.append("images[]", im);
      }
    });
  }

  return axiosClient.post("/products", form);
}



/**
 * UPDATE a product using a FormData object to send data to the server.
 * This function handles updating product details, images, deleted images, 
 * and image positions while ensuring correct formatting.
 *
 * @param {Object} context - Vuex context object, includes commit function.
 * @param {Object} product - Product object containing updated data.
 * @returns {Promise} - Axios POST request response.
 */
export function updateProduct({ }, product) {
  const id = product.id; // Get the product ID
  const form = new FormData(); // Create a new FormData object for sending multipart/form-data

  // Create a new product object with modified values
  const updatedProduct = {
    ...product,
    published: product.published ? 1 : 0, // Convert `true/false` to `1/0` for database compatibility
  };

  // Append each property of the updated product to FormData, ignoring null/undefined values
  Object.entries(updatedProduct).forEach(([key, value]) => {
    if (value !== null && value !== undefined) {
      form.append(key, value);
    } // Only append if the value is not empty
  });

  // Indicate that this is an update operation (required for Laravel's PUT method)
  form.append("_method", "PUT");

  // Handle product images if they exist
  if (product.images && product.images.length) {
    product.images.forEach((im, index) => {
      const imageId = Number(im.id) || index; // Ensure the ID is a number, fallback to index if missing
      form.append(`images[${imageId}]`, im); // Append image to FormData
    });
  }

  // Handle deleted images if they exist
  if (product.deleted_images) {
    product.deleted_images.forEach((imageId) => {
      form.append("deleted_images[]", Number(imageId)); // Ensure the ID is a number before appending
    });
  }

  // Handle image positions if they exist
  for (let id in product.image_positions) {
    const numericId = Number(id) || 0; // Convert key to a number
    if (numericId > 0) {
      form.append(`image_positions[${numericId}]`, product.image_positions[id]); // Append position data
    }
  }

  // Send the form data to update the product using an Axios POST request
  return axiosClient.post(`/products/${id}`, form);
}



/**
 * DELETE a product from the database using its ID.
 * @param {Object} context - Vuex context object, includes commit function.
 * @param {Number} id - Product ID to delete.
 * @returns {Promise} - Axios DELETE request response.
 */
export function deleteProduct({commit}, id) {
  return axiosClient.delete(`/products/${id}`)
}


/**========================USER AREA ================================= */

/**
 * Retrieves a list of users from the database.
 * @param {Object} context - Vuex context object, includes commit function.
 * @param {Object} options - Optional parameters to customize the request.
 * @param {String} options.url - URL to send the request to.
 * @param {String} options.search - Search query to filter users.
 * @param {Number} options.per_page - Number of users to retrieve per page.
 * @param {String} options.sort_field - Field to sort the users by.
 * @param {String} options.sort_direction - Direction to sort the users by.
 * @returns {Promise} - Axios GET request response.
 */
export function getUsers({commit, state}, {url = null, search = '', per_page, sort_field, sort_direction} = {}) {
  commit('setUsers', [true])
  url = url || '/users'
  const params = {
    per_page: state.users.limit,
  }
  return axiosClient.get(url, {
    params: {
      ...params,
      search, per_page, sort_field, sort_direction
    }
  })
    .then((response) => {
      commit('setUsers', [false, response.data])
    })
    .catch(() => {
      commit('setUsers', [false])
    })
}


/**
 * Retrieves A USER from the database using its ID.
 * @param {Object} context - Vuex context object, includes commit function.
 * @param {Number} id - User ID to retrieve.
 * @returns {Promise} - Axios GET request response.
 */
export function getUser({commit}, id) {
  return axiosClient.get(`/users/${id}`)
}


/**
 * CREATE a new user using a FormData object to send data to the server.
 * This function handles creating a new user with all required fields.
 * @param {Object} context - Vuex context object, includes commit function.
 * @param {Object} user - user object containing all required fields.
 * @returns {Promise} - Axios POST request response.
 */
export function createUser({ commit }, user) {
  let formData = new FormData();
  formData.append("name", user.name);
  formData.append("last_name", user.last_name);
  formData.append("email", user.email);
  formData.append("password", user.password);
  formData.append("phone", user.phone);
  

  if (user.avatar instanceof File) {
    formData.append("avatar", user.avatar);
  }

  return axiosClient.post("/users", formData, {
    headers: {
      "Content-Type": "multipart/form-data",
    },
  });
}



/**
 * UPDATE a user using a FormData object to send data to the server.
 * This function handles updating user details
 * @param {Object} context - Vuex context object, includes commit function.
 * @param {Object} user - user object containing updated data.
 * @returns {Promise} - Axios POST request response.
 * @throws {Error} - Axios error object if the request fails.
 */

export async function updateUser({ commit }, user) {
  let form = new FormData();
  // Loop through the user object and append each key-value pair to FormData
  Object.entries(user).forEach(([key, value]) => {
    if (value) form.append(key, value); // Only append if the value is not empty
  });
  // Append "_method" as "PUT" to indicate an update request (Laravel requirement)
  form.append("_method", "PUT");

  try {
    // Send the request to update the user via POST (since FormData is used)
    return await axiosClient.post(`/users/${user.id}`, form, {
      headers: { "Content-Type": "multipart/form-data" }, // Ensure the correct content type
    });
  } catch (error) {
    console.error("Error updating user:", error);
    throw error; // Rethrow the error to handle it in the calling function
  }
}


/**
 * DELETE a user from the database using its ID.
 * @param {Object} context - Vuex context object, includes commit function.
 * @param {Number} id - User ID to delete.
 * @returns {Promise} - Axios DELETE request response.
 */
export function deleteUser({commit}, id) {
  return axiosClient.delete(`/users/${id}`)
}


/**=================================ORDERS AREA ================================= */

export function getOrders({ commit, state }, { url = null, search = null, per_page = null, sort_field = null, sort_direction = null } = {}) {
  commit('setOrders', [true]);

  url = url || '/orders';

  // use the provided parameters or fallback to state defaults
  const params = {
    per_page: per_page || state.orders.limit || 10,
    search: search !== null ? search : state.orders.search || '',
    sort_field: sort_field || state.orders.sort_field || 'updated_at',
    sort_direction: sort_direction || state.orders.sort_direction || 'desc'
  };

  return axiosClient.get(url, { params })
    .then((response) => {
      // update the state with the new parameters
      state.orders.limit = params.per_page;
      state.orders.search = params.search;
      state.orders.sort_field = params.sort_field;
      state.orders.sort_direction = params.sort_direction;

      commit('setOrders', [false, response.data]);
    })
    .catch(() => {
      commit('setOrders', [false]);
    });
}

export function getOrder({commit}, id) {
  return axiosClient.get(`/orders/${id}`)
}

export function deleteOrder({commit}, id) {
  return axiosClient.delete(`/orders/${id}`)
}




export function getCountries({commit}) {
  return axiosClient.get('countries')
    .then(({data}) => {
      commit('setCountries', data)
    })
}
