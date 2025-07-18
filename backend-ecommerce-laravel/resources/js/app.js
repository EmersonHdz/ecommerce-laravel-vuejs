import './bootstrap';

import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import {get, post} from "./http.js";
import Swal from 'sweetalert2'


Alpine.plugin(collapse)

window.Alpine = Alpine;

document.addEventListener("alpine:init", async () => {
 
  // show password field
  Alpine.data('passwordField', () => ({
    show: false,
    toggle() { this.show = !this.show;},
    inputType() { return this.show ? 'text' : 'password';}}));

  // toster notification
  Alpine.data("toast", () => ({
    visible: false,
    delay: 5000,
    percent: 0,
    interval: null,
    timeout: null,
    message: null,
    close() {
      this.visible = false;
      clearInterval(this.interval);
    },
    show(message) {
      this.visible = true;
      this.message = message;

      if (this.interval) {
        clearInterval(this.interval);
        this.interval = null;
      }
      if (this.timeout) {
        clearTimeout(this.timeout);
        this.timeout = null;
      }

      this.timeout = setTimeout(() => {
        this.visible = false;
        this.timeout = null;
      }, this.delay);
      const startDate = Date.now();
      const futureDate = Date.now() + this.delay;
      this.interval = setInterval(() => {
        const date = Date.now();
        this.percent = ((date - startDate) * 100) / (futureDate - startDate);
        if (this.percent >= 100) {
          clearInterval(this.interval);
          this.interval = null;
        }
      }, 30);
    },
  }));

    // show sweet alert message
    const statusElement = document.getElementById('status-message');
    if (statusElement) {
      Swal.fire({
        icon: 'success',
        title: '¡Link sent!',
        text: statusElement.getAttribute('data-status'),
        confirmButtonText: 'Got it',
        timer: 9000
      });
    }


  Alpine.data("productItem", (product) => {
    return {
      product,
      addToCart(quantity = 1) {
        post(this.product.addToCartUrl, {quantity})
          .then(result => {
            this.$dispatch('cart-change', {count: result.count})
            this.$dispatch("notify", {
              message: "The item was added into the cart",
            });
          })
          .catch(response => {
            this.$dispatch('notify', {
              message: response.message || 'Server Error. Please try again.',
              type: 'error'
            })
          })
      },
      removeItemFromCart() {
        post(this.product.removeUrl)
          .then(result => {
            this.$dispatch("notify", {
              message: "The item was removed from cart",
            });
            this.$dispatch('cart-change', {count: result.count})
            this.cartItems = this.cartItems.filter(p => p.id !== product.id)
          })
      },
      changeQuantity() {
        post(this.product.updateQuantityUrl, {quantity: product.quantity})
          .then(result => {
            this.$dispatch('cart-change', {count: result.count})
            this.$dispatch("notify", {
              message: "The item quantity was updated",
            });
          })
          .catch(response => {
            this.$dispatch('notify', {
              message: response.message || 'Server Error. Please try again.',
              type: 'error'
            })
          })
      },
    };
  });
});


Alpine.start();
