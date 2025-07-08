function success_message(message) {
    Swal.fire({
        position: "top",
        icon: "success",
        title: message,
        showConfirmButton: false,
        timer: 1500,
    });
}

function error_message(message) {
    Swal.fire({
        icon: "error",
        title: "Oops...",
        text: message,
    });
}

function confirm_message(message, callback) {
    Swal.fire({
        title: "Ooopsss?",
        text: message,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Confirm",
    }).then((result) => {
        if (result.isConfirmed) {
            success_message("Logout Successfully");
            setTimeout(() => {
                callback();
            }, 1500);
        }
    });
}
function pre_loader(
    title = "Loading...",
    text = "Please wait while we process your request."
) {
    Swal.fire({
        title: "Loading...",
        text: "Please wait while we process your request.",
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        },
    });
}

function success_message2(message = '', icon = 'success') {
    const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 1500,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        },
    });
    Toast.fire({
        icon: icon,
        title: message,
    });
}
