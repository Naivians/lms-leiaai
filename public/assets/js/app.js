var quill = new Quill('#editor', {
    modules: {
        toolbar: '#toolbar'
    },
    theme: 'snow'
});


$(".sidebar-toggle").on("click", function () {
    $("#sidebar").toggleClass("sidebar-collapsed");
    $(".nav_container").toggleClass("expanded");
    $(".main-content").toggleClass("expanded");
});

$('#logoutBtn').on('click', (e) => {
    e.preventDefault()

    confirm_message('Are you sure you want to logout?', () => {
        $.ajax({
            url: '/logout',
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: (res) => {
                window.location.reload();
            },
            error: function (xhr, status, error) {
                error_message('Logout failed: ' + error);
            }
        });
    })
})


// announcements
let announce_btn = $('.announce_btn');
let announce_form_controller = $('.announce_form_container');
announce_btn.on('click', function () {
    announce_form_controller.toggleClass('d-none');
});

function edit_announcement(id) {
    let announce_modal = $('#announcementForm');
    announce_modal.modal('sho')
}

// Tabs
document.addEventListener("DOMContentLoaded", function () {
    const tabButtons = document.querySelectorAll('#classTab button');

    const activeTabId = localStorage.getItem('activeTabId');
    if (activeTabId) {
        const triggerEl = document.querySelector(`#classTab button#${activeTabId}`);
        if (triggerEl) {
            new bootstrap.Tab(triggerEl).show();
        }
    }

    tabButtons.forEach(button => {
        button.addEventListener('shown.bs.tab', function (event) {
            localStorage.setItem('activeTabId', event.target.id);
        });
    });
});




$('#registerForm').on('submit', function (e) {
    e.preventDefault();

    let form = new FormData(this);
    const imgInput = $('#img');
    const MB = 1024 * 1024;
    let allowed_types = ['image/jpg', 'image/jpeg', 'image/png'];
    let files = imgInput[0].files[0];

    if (imgInput[0].files.length > 0) {
        if (files.size >= MB) {
            error_message('The selected image is too large. Please choose an image smaller than 1MB.');
            return;
        }

        if (!allowed_types.includes(files.type)) {
            error_message('Invalid image type. Only JPG or PNG files are allowed.');
            return;
        }
    }

    // test
    form.forEach(e => {
        console.log(e);
    })

    $.ajax({
        url: '/user/register',
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        contentType: false,
        processData: false,
        data: form,
        success: (response) => {
            if (!response.success) {
                error_message('Failed to update user')
                return
            }
            success_message(response.message);
            $("#registerForm")[0].reset();
            $('#errors').hide();
        },
        error: (xhr, status, error) => {
            try {
                const response = JSON.parse(xhr.responseText);
                $('#errors').show();
                $('#errorList').empty();

                if (response.errors) {
                    $('#errors').removeClass('d-none');
                    Object.values(response.errors).flat().forEach(msg => {
                        $('#errorList').append(`<li class="text-danger">${msg}</li>`);
                    });
                } else if (response.message) {
                    $('#errors').removeClass('d-none');
                    $('#errorList').append(`<li class="text-danger">${response.message}</li>`);
                } else {
                    $('#errors').removeClass('d-none');
                    $('#errorList').append(`<li class="text-danger">An unknown error occurred.</li>`);
                }
            } catch (e) {
                console.error('An unexpected error occurred', e);
                $('#errors').removeClass('d-none');
                $('#errorList').html('<li class="text-danger">An unexpected error occurred</li>');
            }
        }
    });
});
$('#updateForm').on('submit', function (e) {
    e.preventDefault();
    let form = new FormData(this);
    const imgInput = $('#img');
    const MB = 1024 * 1024;
    let allowed_types = ['image/jpg', 'image/jpeg', 'image/png'];
    let files = imgInput[0].files[0];

    if (imgInput[0].files.length > 0) {
        if (files.size >= MB) {
            error_message('The selected image is too large. Please choose an image smaller than 1MB.');
            return;
        }

        if (!allowed_types.includes(files.type)) {
            error_message('Invalid image type. Only JPG or PNG files are allowed.');
            return;
        }
    }

    // test
    // form.forEach(e => {
    //     console.log(e);
    // })

    $.ajax({
        url: '/user/update',
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        contentType: false,
        processData: false,
        data: form,
        success: (response) => {

            setInterval(() => {
                if (!response.success) {
                    error_message('Failed to register user')
                    return
                }
                success_message(response.message);
                $('#errors').hide();
            }, 1500)

            window.location.reload();

        },
        error: (xhr, status, error) => {
            try {
                const response = JSON.parse(xhr.responseText);
                $('#errors').show();
                $('#errorList').empty();

                if (response.errors) {
                    $('#errors').removeClass('d-none');
                    Object.values(response.errors).flat().forEach(msg => {
                        $('#errorList').append(`<li class="text-danger">${msg}</li>`);
                    });
                } else if (response.message) {
                    $('#errors').removeClass('d-none');
                    $('#errorList').append(`<li class="text-danger">${response.message}</li>`);
                } else {
                    $('#errors').removeClass('d-none');
                    $('#errorList').append(`<li class="text-danger">An unknown error occurred.</li>`);
                }
            } catch (e) {
                console.error('An unexpected error occurred', e);
                $('#errors').removeClass('d-none');
                $('#errorList').html('<li class="text-danger">An unexpected error occurred</li>');
            }
        }
    });


});

function validate_phone_number() {
    const phoneInput = document.getElementById('contact');
    const { parsePhoneNumberFromString } = libphonenumber;

    const phoneNumber = parsePhoneNumberFromString(phoneInput.value, 'PH');

    if (phoneNumber && phoneNumber.isValid()) {
        phoneInput.value = phoneNumber.formatInternational();
        return true
    }

    return false
}

function validatePhoneInput(input) {
    let val = input.value;

    val = val.replace(/[^\d+]/g, '');

    if (val.indexOf('+') > 0) {
        val = val.replace(/\+/g, '');
        val = '+' + val;
    }

    input.value = val;
}


function showPassword() {
    const password = document.getElementById('validationCustom06');
    const confirmPassword = document.getElementById('validationCustom05');
    const showPasswordCheckbox = document.getElementById('show_password');
    const passwordMatchMsg = document.getElementById('passwordMatchMsg');

    showPasswordCheckbox.addEventListener('change', () => {
        const type = showPasswordCheckbox.checked ? 'text' : 'password';
        password.type = type;
        confirmPassword.type = type;
    });

    confirmPassword.addEventListener('input', () => {
        if (confirmPassword.value !== password.value) {
            passwordMatchMsg.style.display = 'block';
        } else {
            passwordMatchMsg.style.display = 'none';
        }
    });

    password.addEventListener('input', () => {
        if (confirmPassword.value && confirmPassword.value !== password.value) {
            passwordMatchMsg.style.display = 'block';
        } else {
            passwordMatchMsg.style.display = 'none';
        }
    });
}
showPassword()
