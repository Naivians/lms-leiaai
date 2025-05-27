var quill = new Quill('#editor', {
    modules: {
        toolbar: '#toolbar'
    },
    theme: 'snow'
});

(() => {
  'use strict'

  const forms = document.querySelectorAll('.needs-validation')

  Array.from(forms).forEach(form => {
    form.addEventListener('submit', event => {
      if (!form.checkValidity()) {
        event.preventDefault()
        event.stopPropagation()
      }

      form.classList.add('was-validated')
    }, false)
  })
})()

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
