function success_message(message) {
    Swal.fire({
        position: "top",
        icon: "success",
        title: message,
        showConfirmButton: false,
        timer: 1500
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
        confirmButtonText: "Confirm"
    }).then((result) => {
        if (result.isConfirmed) {
            success_message('Logout Successfully')
            setInterval(() => {
                callback();
            }, 1500)

        }
    });
}
function pre_loader() {
    Swal.fire({
        title: 'Redirecting...',
        text: 'Please wait while we take you there.',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
}
