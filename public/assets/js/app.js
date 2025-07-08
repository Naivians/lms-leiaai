document.addEventListener("DOMContentLoaded", function () {
    const tooltipTriggerList = document.querySelectorAll(
        '[data-bs-toggle="tooltip"]'
    );
    tooltipTriggerList.forEach((tooltipTriggerEl) => {
        new bootstrap.Tooltip(tooltipTriggerEl);
    });

    const tabButtons = document.querySelectorAll("#classTab button");

    const activeTabId = localStorage.getItem("activeTabId");
    if (activeTabId) {
        const triggerEl = document.querySelector(
            `#classTab button#${activeTabId}`
        );
        if (triggerEl) {
            new bootstrap.Tab(triggerEl).show();
        }
    } else {
        const triggerEl = document.querySelector(`#classTab button#streams`);
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

$(document).ready(function () {
    const fi_searchInput = $("#search_fi");
    const student_search = $("#search_student");
    const fi_search_results = $("#fi_search_results");
    const student_search_results = $("#students_search_results");

    if (fi_searchInput.length > 0) {
        fi_searchInput.on(
            "input",
            debounce(() => {
                const searchVal = fi_searchInput.val().trim();

                if (searchVal.length === 0) {
                    fi_search_results.empty();
                    return;
                }

                $.ajax({
                    url: "/class/search",
                    method: "GET",
                    data: {
                        search: searchVal,
                        roles: "fi",
                    },
                    success: (response) => {
                        fi_search_results.empty();

                        if (response.html.length === 0) {
                            fi_search_results.append(
                                '<h5 class="text-muted text-center">No data found</h5>'
                            );
                        } else {
                            fi_search_results.append(response.html);
                        }
                    },
                    error: () => {
                        fi_search_results.empty();
                        fi_search_results.append(
                            '<h5 class="text-danger text-center">Error retrieving data</h5>'
                        );
                    },
                });
            }, 300)
        );
    } else {
        fi_searchInput.val("");
        fi_search_results.empty().hide();
    }

    if (student_search.length > 0) {
        student_search.on(
            "input",
            debounce(() => {
                const searchVal = student_search.val().trim();

                if (searchVal.length === 0) {
                    student_search_results.empty();
                    return;
                }

                $.ajax({
                    url: "/class/search",
                    method: "GET",
                    data: {
                        search: searchVal,
                        roles: "students",
                    },
                    success: (response) => {
                        student_search_results.empty();

                        if (response.html.length === 0) {
                            student_search_results.append(
                                '<h5 class="text-muted text-center">No data found</h5>'
                            );
                        } else {
                            student_search_results.append(response.html);
                        }
                    },
                    error: () => {
                        student_search_results.empty();
                        student_search_results.append(
                            '<h5 class="text-danger text-center">Error retrieving data</h5>'
                        );
                    },
                });
            }, 300)
        );
    } else {
        student_search.val("");
        student_search_results.empty().hide();
    }

    displayEnrolledUsers();
});

let quill;

const editorEl = document.querySelector("#editor");
if (editorEl) {
    quill = new Quill(editorEl, {
        modules: {
            toolbar: "#toolbar",
        },
        theme: "snow",
    });
}

$("#announcement_form").on("submit", function (e) {
    e.preventDefault();

    if (!quill) {
        console.error("Quill editor is not initialized.");
        return;
    }

    const content = quill.root.innerHTML;
    const isEmpty = quill.getText().trim().length === 0;
    if (isEmpty) {
        error_message("Announcement content cannot be empty.");
        return;
    }

    const formData = new FormData(this);
    formData.append("announcement_content", content);

    $.ajax({
        url: "/announcement/store",
        method: "POST",
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        beforeSend() {
            pre_loader();
        },
        success: function (response) {
            success_message(response.message);
            quill.setText("");
        },
        error: (xhr) => {
            try {
                const response = JSON.parse(xhr.responseText);

                if (response.errors) {
                    Object.values(response.errors)
                        .flat()
                        .forEach((msg) => {
                            console.log(msg);
                        });
                } else if (response.message) {
                    error_message(response.message);
                    $("#announcement_form")[0].reset();
                }
            } catch (e) {
                console.error("An unexpected error occurred", e);
            }
        },
    });
});

$("#edit_announcement_form").on("submit", function (e) {
    e.preventDefault();

    if (!quill) {
        console.error("Quill editor is not initialized.");
        return;
    }

    const content = quill.root.innerHTML;
    const isEmpty = quill.getText().trim().length === 0;
    if (isEmpty) {
        error_message("Announcement content cannot be empty.");
        return;
    }

    const formData = new FormData(this);
    formData.append("announcement_content", content);

    $.ajax({
        url: "/announcement/update",
        method: "POST",
        data: formData,
        processData: false,
        contentType: false,
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

            setTimeout(() => {
                window.location.href = response.redirect;
            }, 1500);
        },
        error: (xhr) => {
            try {
                const response = JSON.parse(xhr.responseText);

                if (response.errors) {
                    Object.values(response.errors)
                        .flat()
                        .forEach((msg) => {
                            console.log(msg);
                        });
                } else if (response.message) {
                    error_message(response.message);
                    $("#announcement_form")[0].reset();
                }
            } catch (e) {
                console.error("An unexpected error occurred", e);
            }
        },
    });
});

// lessons

function deleteLesson(lesson_id) {
    Swal.fire({
        title: "Delete Lesson?",
        html: `
            <p class="mb-1">Are you sure you want to delete this lesson?</p>
            <small class="text-danger">All attached materials will also be permanently deleted.</small>
        `,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Confirm",
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/lessons/deleteLesson/${lesson_id}`,
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
                        window.location.reload();
                    }, 1500);
                },
                error: (xhr, status, error) => {
                    try {
                        const response = JSON.parse(xhr.responseText);

                        if (response.errors) {
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

function delete_announcement(announcementId) {
    Swal.fire({
        title: "Are you sure?",
        text: "This action will permanently delete the announcement.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Confirm",
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/announcement/delete-announcement/${announcementId}`,
                type: "DELETE",
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
                        window.location.reload();
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
}

function enrollUser(userId, role_id) {
    let encryptedId = window.location.pathname.split("/").pop();
    $.ajax({
        url: "/class/enroll",
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        data: {
            userId: userId,
            classId: encryptedId,
            roleId: role_id,
        },
        success: (res) => {
            if (!res.success) {
                error_message(res.message);
                return;
            }
            $("#search_fi").val("");
            $("#search_student").val("");
            displayEnrolledUsers();
        },
    });
}

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

    if (path[2] === "stream" && class_id != "") {
        $.ajax({
            url: `/class/getEnrolledUsers/${encodeURIComponent(encryptedId)}`,
            type: "GET",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                const enrolled_fi = response.data1;
                const enrolled_students = response.data2;

                fi_container = $("#enrolled_fi_cgi_container");
                student_container = $("#enrolled_student_container");
                fi_container.empty();
                student_container.empty();

                if (enrolled_fi == null) {
                    fi_container.append(
                        '<p class="text-muted text-center">There are currently no faculty instructors or CGI enrolled in this class.</p>'
                    );
                }

                if (enrolled_students == null) {
                    student_container.append(
                        '<p class="text-muted text-center">There are currently no students enrolled in this class.</p>'
                    );
                }

                student_container.append(enrolled_students);
                fi_container.append(enrolled_fi);
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

function removeUserFromCLass(userId, roleLabel) {
    let message = `<strong>Note:</strong> This action will remove this FI /CGI from this class. `;

    if (roleLabel === "students") {
        message = `<strong>Note:</strong> This action will remove this student from this class.`;
    }

    Swal.fire({
        title: "Ooopsss?",
        html: message,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Confirm",
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "/class/remove-user-from-cLass",
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                data: {
                    userId: userId,
                },
                success: (res) => {
                    if (!res.success) {
                        error_message(res.message);
                        return;
                    }

                    success_message(res.message);
                    setTimeout(() => {
                        displayEnrolledUsers();
                    }, 1000);

                    // console.log(res);
                },
            });
        }
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
            message = `<strong>Note:</strong> This action will restore the archive classes. When the class is restored, all related data (such as users, assignments, and resources) will be restored as well.`;
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

function collapseSidebar() {
    $("#sidebar").addClass("sidebar-collapsed");
    $(".main-content").removeClass("expanded");
    $(".nav_container").removeClass("expanded");
}

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

$("[data-toggle-form]").on("click", function () {
    const target = $(this).data("toggle-form");
    $(`#${target}_form`).removeClass("d-none");
    $(`#${target}_container`).addClass("d-none");
    $("#students_search_results").empty().show();
    $("#fi_search_results").empty().show();
    $("#search_fi").val("");
    $("#search_student").val("");
});

$("[data-close-form]").on("click", function () {
    const target = $(this).data("close-form");
    $(`#${target}_form`).addClass("d-none");
    $(`#${target}_container`).removeClass("d-none");
    $("#fi_search_results").empty().hide();
    $("#students_search_results").empty().hide();
    $("#search_fi").val("");
    $("#search_student").val("");
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

            success_message(response.message, "error");

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

function validate_input_accept_numbers_only(input) {
    // Allow only positive integers, minimum 1, no letters, no negatives
    const regex = /^[1-9][0-9]*$/;

    if (!regex.test(input.value)) {
        input.value = input.value.replace(/[^0-9]/g, "");
        if (input.value.startsWith("0")) {
            input.value = input.value.replace(/^0+/, "");
        }
    }
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
