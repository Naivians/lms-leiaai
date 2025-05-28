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

// users
let enable_inputs = document.querySelectorAll('.enable_input');
let user_update_btn = $('#user_update_btn').hide();
let edit_info_btn = $('#edit_info');

$('#edit_info').on('click', () => {
    if (edit_info_btn.text().trim().toLowerCase() === 'edit') {
        enable_inputs.forEach(input => input.disabled = false);
        edit_info_btn.text('Back')
        user_update_btn.show();
    } else {
        enable_inputs.forEach(input => input.disabled = true);
        user_update_btn.hide();
        edit_info_btn.text('Edit')
    }
});

// people
$('#enroll_fi_container').on('click', () => {
    $('#enroll_fi_form').removeClass('d-none');
    $('#enroll_fi_container').addClass('d-none');
})
$('#close_enroll_btn').on('click', () => {
    $('#enroll_fi_container').removeClass('d-none');
    $('#enroll_fi_form').addClass('d-none');
})

$('#enroll_student_container').on('click', () => {
    $('#enroll_student_form').removeClass('d-none');
    $('#enroll_student_container').addClass('d-none');
})
$('#close_enroll_student_btn').on('click', () => {
    $('#enroll_student_container').removeClass('d-none');
    $('#enroll_student_form').addClass('d-none');
})


$('#registerForm').on('submit', function (e) {
    e.preventDefault();

    let form = new FormData(this);
    const imgInput = $('#img');
    const MB = 1024 * 1024;
    const password = $('#validationCustom06');
    const confirmPassword = $('#validationCustom05');
    let allowed_types = ['image/jpg', 'image/jpeg', 'image/png'];
    let files = imgInput[0].files[0];

    // if (password.val().length < 8) {
    //     error_message('Password must be at least 8 characters long.');
    //     return;
    // }

    // if (password.val() !== confirmPassword.val()) {
    //     error_message('Passwords do not match. Please confirm your password.');
    //     return;
    // }

    // if (!validate_phone_number()) {
    //     error_message('The phone number entered is invalid. Please enter a valid Philippine number.');
    //     return;
    // }

    // if (imgInput[0].files.length > 0) {
    //     if (files.size >= MB) {
    //         error_message('The selected image is too large. Please choose an image smaller than 1MB.');
    //         return;
    //     }

    //     if (!allowed_types.includes(files.type)) {
    //         error_message('Invalid image type. Only JPG or PNG files are allowed.');
    //         return;
    //     }
    // }
    // test
    // form.forEach(e => {
    //     console.log(e);
    // })

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
                error_message('Failed to register user')
                return
            }

            success_message(response.message);
            $("#registerForm")[0].reset();

        },
        error: (xhr, status, error) => {
            try {
                const response = JSON.parse(xhr.responseText);
                error_message(response.message || 'An error occurred');
            } catch (e) {
                error_message('An unexpected error occurred');
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
