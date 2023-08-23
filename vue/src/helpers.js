import Vue from "vue";
import Swal from "sweetalert2";

export const makeToast = (type, message) => {
  // Vue.$toast.open('You did it!');
  Vue.$toast.open({
    message,
    type,
    position: "bottom",
  });
};

export const ask = (
  message = "Do you want to save the changes?",
  type = "info"
) => {
  return Swal.fire({
    title: message,
    icon: type,
    showCancelButton: true,
    confirmButtonText: "<span class='v-btn__content'>نعم</span>",
    denyButtonText: "<span class='v-btn__content'>الغاء</span>",
    cancelButtonText: "<span class='v-btn__content'>الغاء</span>",
    buttonsStyling: true,
    focusConfirm: false,
    customClass: {
      confirmButton:
        "v-btn v-btn--contained v-btn--is-elevated v-btn--has-bg theme--light v-size--default danger",
      cancelButton: "v-btn v-size--default",
    },
  });
};

export const sleep = (time) => {
  return new Promise((resolve) => setTimeout(resolve, time));
};
