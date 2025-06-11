
document.addEventListener("DOMContentLoaded", function () {
    const tooltipTriggerList = document.querySelectorAll(
        '[data-bs-toggle="tooltip"]'
    );
    tooltipTriggerList.forEach((tooltipTriggerEl) => {
        new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

$(document).ready(function () {
    const $searchInput = $("#search_fi");

    if ($searchInput.length) {
        $searchInput.on(
            "input",
            debounce(() => {
                console.log($searchInput.val());
            }, 300)
        );
    }

    displayEnrolledUsers();
});

function debounce(func, delay) {
    let timer;
    return function (...args) {
        clearTimeout(timer);
        timer = setTimeout(() => func.apply(this, args), delay);
    };
}


function displayEnrolledUsers() {
    let class_id = $("#encrypted_class_id").val();
    let encryptedId = window.location.pathname.split("/").pop();
    path = window.location.pathname.split("/");

    if (path[2] === "stream") {
        $.ajax({
            url: `/class/getEnrolledUsers/${encodeURIComponent(encryptedId)}`,
            type: "GET",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                const enrolled_fi = response.data1;
                const enrolled_students = response.data2;

                container = $("#enrolled_users_container");
                student_container = $("#enrolled_student_container");
                container.empty();
                student_container.empty();

                if (enrolled_fi.length === 0) {
                    container.append(
                        '<p class="text-muted">There are currently no faculty instructors or CGI enrolled in this class.</p>'
                    );
                }

                if (enrolled_students.length === 0) {
                    student_container.append(
                        '<p class="text-muted">here are currently no students enrolled in this class.</p>'
                    );
                }

                get_fi_and_cgi(enrolled_fi);
                get_enrolled_students(enrolled_students);
            },
            error: (xhr) => {
                try {
                    const response = JSON.parse(xhr.responseText);

                    if (response.errors) {
                        $("#errors").removeClass("d-none");
                        Object.values(response.errors)
                            .flat()
                            .forEach((msg) => {
                                console.log(msg);
                            });
                    } else if (response.message) {
                        error_message(response.message);
                    }
                } catch (e) {
                    console.error("An unexpected error occurred", e);
                }
            },
        });
    }
}

function get_fi_and_cgi(enrolled_users) {
    enrolled_users.forEach((users) => {
        const card = `
            <div class="card mb-2">
                <div class="card-header announcement_header d-flex justify-content-between align-items-center">
                    <div class="announcement_header d-flex align-items-center">
                        <div class="announcement_img_container me-2">
                            <img src="${users.img}" alt="${users.name}" width="50" height="50" class="rounded-circle">
                        </div>
                        <div>
                            <h5 class="my-0">${users.name}</h5>
                            <small>${users.role_label}</small>
                        </div>
                    </div>
                    <div class="edit_btn">
                        <i class="fa-solid fa-trash btn btn-outline-danger" onclick="delete_student(${users.id})"></i>
                    </div>
                </div>
            </div>
        `;
        container.append(card);
    });
}

function get_enrolled_students(enrolled_users) {
    enrolled_users.forEach((users) => {
        const card = `
            <div class="card mb-2">
                <div class="card-header announcement_header d-flex justify-content-between align-items-center">
                    <div class="announcement_header d-flex align-items-center">
                        <div class="announcement_img_container me-2">
                            <img src="${users.img}" alt="${users.name}" width="50" height="50" class="rounded-circle">
                        </div>
                        <div>
                            <h5 class="my-0">${users.name}</h5>
                            <small>${users.role_label}</small>
                        </div>
                    </div>
                    <div class="edit_btn">
                        <i class="fa-solid fa-trash btn btn-outline-danger" onclick="delete_student(${users.id})"></i>
                    </div>
                </div>
            </div>
        `;
        container.append(card);
    });
}

$("#editClassForm").on("submit", function (e) {
    e.preventDefault();
    let form = new FormData(this);

    // form.forEach((value, key) => {
    //     console.log(`${key}: ${value}`);
    // })

    $.ajax({
        url: `/class/update`,
        type: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        data: form,
        contentType: false,
        processData: false,
        beforeSend() {
            pre_loader();
        },
        success: function (response) {
            if (!response.success) {
                error_message(response.message);
                return;
            }
            success_message(response.message);
            setTimeout(() => {
                window.location.href = "/class";
            }, 1500);
        },
        error: (xhr) => {
            try {
                const response = JSON.parse(xhr.responseText);

                if (response.errors) {
                    $("#errors").removeClass("d-none");
                    Object.values(response.errors)
                        .flat()
                        .forEach((msg) => {
                            console.log(msg);
                        });
                } else if (response.message) {
                    error_message(response.message);
                }
            } catch (e) {
                console.error("An unexpected error occurred", e);
            }
        },
    });
});

document.querySelectorAll(".deleteClassBtn").forEach((button) => {
    button.addEventListener("click", function (e) {
        e.preventDefault();

        const encryptedId = this.dataset.id;
        var message = `<strong>Note:</strong> This action will archive the class, not delete it permanently
           If you need to restore an archived class, please contact the development team.`;

        path = window.location.pathname.split("/");

        if (path[2] === "archives") {
            message = `<strong>Note:</strong> This action will restore the archive classes. When the class is restored, all related data (such as users, assignments, and resources) will be restored as well.`
        }

        Swal.fire({
            title: "Are you sure?",
            html: message,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Confirm",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/class/archive/${encodeURIComponent(encryptedId)}`,
                    type: "POST",
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    beforeSend() {
                        pre_loader();
                    },
                    success: function (response) {
                        if (!response.success) {
                            error_message(response.message);
                            return;
                        }
                        success_message(response.message);
                        setTimeout(() => {
                            window.location.href = "/class";
                        }, 1500);
                    },
                    error: (xhr) => {
                        try {
                            const response = JSON.parse(xhr.responseText);

                            if (response.errors) {
                                $("#errors").removeClass("d-none");
                                Object.values(response.errors)
                                    .flat()
                                    .forEach((msg) => {
                                        console.log(msg);
                                    });
                            } else if (response.message) {
                                error_message(response.message);
                            }
                        } catch (e) {
                            console.error("An unexpected error occurred", e);
                        }
                    },
                });
            }
        });
    });
});

document.querySelectorAll(".editClassBtn").forEach((button) => {
    button.addEventListener("click", function (e) {
        e.preventDefault();
        $("#editClassModal").modal("show");
        const encryptedId = this.dataset.id;

        $.ajax({
            url: `/class/show/${encodeURIComponent(encryptedId)}`,
            type: "GET",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                if (!response.success) {
                    error_message(response.message);
                    return;
                }

                $("#edit_class_name").val(response.data.class_name);
                $("#edit_class_description").val(
                    response.data.class_description
                );
                $("#edit_class_id").val(response.data.id);
                $("#edit_course_name").val(response.data.course_name);
            },
            error: (xhr) => {
                try {
                    const response = JSON.parse(xhr.responseText);

                    if (response.errors) {
                        $("#errors").removeClass("d-none");
                        Object.values(response.errors)
                            .flat()
                            .forEach((msg) => {
                                console.log(msg);
                            });
                    } else if (response.message) {
                        error_message(response.message);
                    }
                } catch (e) {
                    console.error("An unexpected error occurred", e);
                }
            },
        });
    });
});

$(".sidebar-toggle").on("click", function () {
    $("#sidebar").toggleClass("sidebar-collapsed");
    $(".nav_container").toggleClass("expanded");
    $(".main-content").toggleClass("expanded");
});

$("#logoutBtn").on("click", (e) => {
    e.preventDefault();

    confirm_message("Are you sure you want to logout?", () => {
        $.ajax({
            url: "/logout",
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: (res) => {
                window.location.reload();
            },
            error: function (xhr, status, error) {
                error_message("Logout failed: " + error);
            },
        });
    });
});

$("#enroll_fi_container").on("click", () => {
    $("#enroll_fi_form").removeClass("d-none");
    $("#enroll_fi_container").addClass("d-none");
});

$("#close_enroll_fi_btn").on("click", () => {
    $("#enroll_fi_form").addClass("d-none");
    $("#enroll_fi_container").removeClass("d-none");
});

function refreshTable(tableName) {
    let table = $(tableName).DataTable();
    table.ajax.reload(null, false);
}

// announcements
let announce_btn = $(".announce_btn");
let announce_form_controller = $(".announce_form_container");
announce_btn.on("click", function () {
    announce_form_controller.toggleClass("d-none");
});

function edit_announcement(id) {
    let announce_modal = $("#announcementForm");
    announce_modal.modal("sho");
}

// Tabs
document.addEventListener("DOMContentLoaded", function () {
    const tabButtons = document.querySelectorAll("#classTab button");

    const activeTabId = localStorage.getItem("activeTabId");
    if (activeTabId) {
        const triggerEl = document.querySelector(
            `#classTab button#${activeTabId}`
        );
        if (triggerEl) {
            new bootstrap.Tab(triggerEl).show();
        }
    }

    tabButtons.forEach((button) => {
        button.addEventListener("shown.bs.tab", function (event) {
            localStorage.setItem("activeTabId", event.target.id);
        });
    });
});

$("#registerForm").on("submit", function (e) {
    e.preventDefault();

    let form = new FormData(this);
    const imgInput = $("#img");
    const MB = 1024 * 1024;
    let allowed_types = ["image/jpg", "image/jpeg", "image/png"];
    let files = imgInput[0].files[0];

    if (imgInput[0].files.length > 0) {
        if (files.size >= MB) {
            error_message(
                "The selected image is too large. Please choose an image smaller than 1MB."
            );
            return;
        }

        if (!allowed_types.includes(files.type)) {
            error_message(
                "Invalid image type. Only JPG or PNG files are allowed."
            );
            return;
        }
    }

    $.ajax({
        url: "/user/register",
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        contentType: false,
        processData: false,
        data: form,
        beforeSend() {
            setTimeout(() => {
                pre_loader();
            });
        },
        success: (response) => {
            if (!response.success) {
                error_message("Failed to update user");
                return;
            }

            success_message(response.message);
            setTimeout(() => {
                window.location.href = `${response.redirect}`;
            }, 1500);
            $("#registerForm")[0].reset();
            $("#errors").hide();
        },
        error: (xhr, status, error) => {
            try {
                const response = JSON.parse(xhr.responseText);
                $("#errors").show();
                $("#errorList").empty();

                if (response.errors) {
                    $("#errors").removeClass("d-none");
                    Object.values(response.errors)
                        .flat()
                        .forEach((msg) => {
                            $("#errorList").append(
                                `<li class="text-danger">${msg}</li>`
                            );
                        });
                } else if (response.message) {
                    $("#errors").removeClass("d-none");
                    $("#errorList").append(
                        `<li class="text-danger">${response.message}</li>`
                    );
                } else {
                    $("#errors").removeClass("d-none");
                    $("#errorList").append(
                        `<li class="text-danger">An unknown error occurred.</li>`
                    );
                }
            } catch (e) {
                console.error("An unexpected error occurred", e);
                $("#errors").removeClass("d-none");
                $("#errorList").html(`<li class="text-danger">${e}</li>`);
            }
        },
    });
});
$("#updateForm").on("submit", function (e) {
    e.preventDefault();
    let form = new FormData(this);
    const imgInput = $("#img");
    const MB = 1024 * 1024;
    let allowed_types = ["image/jpg", "image/jpeg", "image/png"];
    let files = imgInput[0].files[0];

    if (imgInput[0].files.length > 0) {
        if (files.size >= MB) {
            error_message(
                "The selected image is too large. Please choose an image smaller than 1MB."
            );
            return;
        }

        if (!allowed_types.includes(files.type)) {
            error_message(
                "Invalid image type. Only JPG or PNG files are allowed."
            );
            return;
        }
    }

    // form.forEach((value, key)=> {
    //     console.log(`${key}: ${value}`);
    // })

    $.ajax({
        url: "/user/update",
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        contentType: false,
        processData: false,
        data: form,
        success: (response) => {
            if (!response.success) {
                error_message("Failed to register user");
                return;
            }

            success_message(response.message);
            $("#errors").hide();
            setInterval(() => {
                window.location.reload();
            }, 1500);
        },
        error: (xhr, status, error) => {
            try {
                const response = JSON.parse(xhr.responseText);
                $("#errors").show();
                $("#errorList").empty();

                if (response.errors) {
                    $("#errors").removeClass("d-none");
                    Object.values(response.errors)
                        .flat()
                        .forEach((msg) => {
                            $("#errorList").append(
                                `<li class="text-danger">${msg}</li>`
                            );
                        });
                } else if (response.message) {
                    $("#errors").removeClass("d-none");
                    $("#errorList").append(
                        `<li class="text-danger">${response.message}</li>`
                    );
                } else {
                    $("#errors").removeClass("d-none");
                    $("#errorList").append(
                        `<li class="text-danger">An unknown error occurred.</li>`
                    );
                }
            } catch (e) {
                console.error("An unexpected error occurred", e);
                $("#errors").removeClass("d-none");
                $("#errorList").html(
                    '<li class="text-danger">An unexpected error occurred</li>'
                );
            }
        },
    });
});

function validate_phone_number() {
    const phoneInput = document.getElementById("contact");
    const { parsePhoneNumberFromString } = libphonenumber;

    const phoneNumber = parsePhoneNumberFromString(phoneInput.value, "PH");

    if (phoneNumber && phoneNumber.isValid()) {
        phoneInput.value = phoneNumber.formatInternational();
        return true;
    }

    return false;
}

function validatePhoneInput(input) {
    let val = input.value;

    val = val.replace(/[^\d+]/g, "");

    if (val.indexOf("+") > 0) {
        val = val.replace(/\+/g, "");
        val = "+" + val;
    }

    input.value = val;
}

function showPassword() {
    const password = document.getElementById("validationCustom06");
    const confirmPassword = document.getElementById("validationCustom05");
    const showPasswordCheckbox = document.getElementById("show_password");
    const passwordMatchMsg = document.getElementById("passwordMatchMsg");

    showPasswordCheckbox.addEventListener("change", () => {
        const type = showPasswordCheckbox.checked ? "text" : "password";
        password.type = type;
        confirmPassword.type = type;
    });

    confirmPassword.addEventListener("input", () => {
        if (confirmPassword.value !== password.value) {
            passwordMatchMsg.style.display = "block";
        } else {
            passwordMatchMsg.style.display = "none";
        }
    });

    password.addEventListener("input", () => {
        if (confirmPassword.value && confirmPassword.value !== password.value) {
            passwordMatchMsg.style.display = "block";
        } else {
            passwordMatchMsg.style.display = "none";
        }
    });
}
function login_status(selectElement, userId) {
    const selectedValue = selectElement.value;

    $.ajax({
        url: "update/login_status",
        type: "POST",
        data: {
            id: userId,
            login_status: selectedValue,
        },
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        beforeSend() {
            pre_loader();
        },
        success: function (response) {
            if (!response.success) {
                error_message(response.message);
                return;
            }
            success_message(response.message);
            refreshTable;
        },
        error: (xhr, status, error) => {
            try {
                const response = JSON.parse(xhr.responseText);

                if (response.errors) {
                    $("#errors").removeClass("d-none");
                    Object.values(response.errors)
                        .flat()
                        .forEach((msg) => {
                            console.log(msg);
                        });
                } else if (response.message) {
                    error_message(response.message);
                }
            } catch (e) {
                console.error("An unexpected error occurred", e);
            }
        },
    });
}

// add courses
function AddCourse() {
    $.ajax({
        url: "/course/create",
        type: "POST",
        data: {
            course_name: $("#course_name").val(),
        },
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        beforeSend() {
            pre_loader();
        },
        success: function (response) {
            if (!response.success) {
                error_message(response.message);
                return;
            }
            success_message(response.message);
            refreshTable("#courseTable");
        },
        error: (xhr, status, error) => {
            try {
                const response = JSON.parse(xhr.responseText);

                if (response.errors) {
                    $("#errors").removeClass("d-none");
                    Object.values(response.errors)
                        .flat()
                        .forEach((msg) => {
                            console.log(msg);
                        });
                } else if (response.message) {
                    error_message(response.message);
                }
            } catch (e) {
                console.error("An unexpected error occurred", e);
            }
        },
    });
}

function deleteCourse(course_id) {
    Swal.fire({
        title: "Ooopsss?",
        text: "Are you sure you want to delete this course?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Confirm",
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/course/delete/${course_id}`,
                type: "POST",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                beforeSend() {
                    pre_loader();
                },
                success: function (response) {
                    if (!response.success) {
                        error_message(response.message);
                        return;
                    }
                    success_message(response.message);
                    refreshTable("#courseTable");
                },
                error: (xhr, status, error) => {
                    try {
                        const response = JSON.parse(xhr.responseText);

                        if (response.errors) {
                            $("#errors").removeClass("d-none");
                            Object.values(response.errors)
                                .flat()
                                .forEach((msg) => {
                                    console.log(msg);
                                });
                        } else if (response.message) {
                            error_message(response.message);
                        }
                    } catch (e) {
                        console.error("An unexpected error occurred", e);
                    }
                },
            });
        }
    });
}

function showCourse(course_id) {
    $editCourse = $(".editCourse").removeClass("d-none");
    $addCourse = $(".addCourse").addClass("d-none");

    $.ajax({
        url: `/course/show/${course_id}`,
        type: "GET",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            if (!response.success) {
                error_message(response.message);
                return;
            }
            $("#course_description").val(response.data.course_description);
            $("#course_names").val(response.data.course_name);
            $("#course_id").val(response.data.id);
        },
        error: (xhr, status, error) => {
            try {
                const response = JSON.parse(xhr.responseText);

                if (response.errors) {
                    $("#errors").removeClass("d-none");
                    Object.values(response.errors)
                        .flat()
                        .forEach((msg) => {
                            console.log(msg);
                        });
                } else if (response.message) {
                    error_message(response.message);
                }
            } catch (e) {
                console.error("An unexpected error occurred", e);
            }
        },
    });
}

function backToCourses() {
    $(".editCourse").addClass("d-none");
    $(".addCourse").removeClass("d-none");
    $("#course_description").val("");
    $("#course_names").val("");
    $("#course_id").val("");
}

function EditCourse() {
    let course_id = $("#course_id").val();
    let course_name = $("#course_names").val();
    let course_description = $("#course_description").val();

    $.ajax({
        url: `/course/update`,
        type: "POST",
        data: {
            course_name: course_name,
            course_description: course_description,
            course_id: course_id,
        },
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        beforeSend() {
            pre_loader();
        },
        success: function (response) {
            if (!response.success) {
                error_message(response.message);
                return;
            }
            success_message(response.message);
            refreshTable("#courseTable");
            $(".editCourse").addClass("d-none");
            $(".addCourse").removeClass("d-none");
        },
        error: (xhr, status, error) => {
            try {
                const response = JSON.parse(xhr.responseText);

                if (response.errors) {
                    $("#errors").removeClass("d-none");
                    Object.values(response.errors)
                        .flat()
                        .forEach((msg) => {
                            console.log(msg);
                        });
                } else if (response.message) {
                    error_message(response.message);
                }
            } catch (e) {
                console.error("An unexpected error occurred", e);
            }
        },
    });
}
