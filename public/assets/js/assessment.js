$(document).ready(function () {
    generate_question_ui();
    $("#questions_container").empty();
    const debouncedLog = debounce((value) => {
        localStorage.setItem("total_question", value);
        generate_question_ui();
    }, 10);

    $("#total")
        .off()
        .on("change", function () {
            let value = $(this).val();
            const regex = /^[1-9][0-9]*$/;

            if (value === "" || !regex.test(value)) {
                $(this).val() == 0;
                localStorage.setItem("total_question", 0);
                localStorage.removeItem("assessment_data");
                $("#questions_container").empty();
                return;
            }
            debouncedLog(value);
        });

    $(document).on("click", ".fa-trash", function () {
        $(this).closest(".card").remove();
        saveAssessmentData();
    });

    $(document).on("input", "#questions_container input", function () {
        saveAssessmentData();
    });

    $("#assessmentForms").on("submit", function (e) {
        e.preventDefault();
        saveAssessmentData();

        const form = new FormData(this);
        const data = JSON.parse(localStorage.getItem("assessment_data")) || [];



        Swal.fire({
            title: "Ooopsss?",
            text: "Are you sure you want to submit this form?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Confirm",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "/assessments/store",
                    type: "POST",
                    data: form,
                    processData: false,
                    contentType: false,
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    beforeSend: function () {
                        pre_loader();
                    },
                    success: function (response) {
                        if (!response.success) {
                            error_message(response.message);
                            return;
                        }
                        success_message(response.message);
                        setTimeout(() => {
                            // $(this).val("");
                            localStorage.setItem("total_question", 0);
                            localStorage.removeItem("assessment_data");
                            $("#questions_container").empty();
                            // $("#total").val("");
                            window.location.reload()
                        }, 1500);
                    },
                    error: function (error) {
                        alert(`error: ${error}`);
                    },
                });
            }
        });
    });
});


function generate_question_ui() {
    let total_questions =
        parseInt(localStorage.getItem("total_question"), 10) || 0;
    let savedData = JSON.parse(localStorage.getItem("assessment_data") || "[]");
    let question_container = $("#questions_container");

    question_container.empty();

    for (let index = 0; index < total_questions; index++) {
        const savedQuestion = savedData[index] || {};
        const questionText = savedQuestion.question || "";
        const choices = savedQuestion.choices || ["", "", "", ""];
        const correct = savedQuestion.correct || "";

        const card = `
        <div class="card mx-auto my-3">
            <div class="card-header bg-primary text-light">
                <input type="text" name="question[]" id="question_${index}" class="form-control" placeholder="Question here" value="${questionText}">
            </div>
            <div class="card-body">
                ${choices
                    .map((choice, i) => {
                        const optionLabel = String.fromCharCode(65 + i);
                        const isChecked =
                            savedQuestion.correct === optionLabel
                                ? "checked"
                                : "";
                        return `
                        <div class="form-check my-2">
                            <input class="form-check-input" type="radio" name="choices_${index}" id="q${index}_option${i}" value="${optionLabel}" ${isChecked}>
                            <label class="form-check-label w-100" for="q${index}_option${i}">
                                <input type="text" name="choices_${index}[]" id="choices_${index}_${i}" class="form-control" placeholder="Option ${optionLabel}" value="${choice}">
                            </label>
                        </div>`;
                    })
                    .join("")}
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between">
                <div class="input-group w-75 d-flex align-items-center">
                    <label for="correct_${index}" class="form-label text-secondary me-3 mt-2">Correct Answer</label>
                    <input type="text" name="correct_${index}" id="correct_${index}" class="form-control" placeholder="paste the correct answer" value="${correct}">
                </div>
                <i class="fa-solid fa-trash btn btn-outline-danger" title="Remove question"></i>
            </div>
        </div>`;
        question_container.append(card);
    }
}

// Save all assessment data to localStorage
function saveAssessmentData() {
    const data = [];

    $("#questions_container .card").each(function (index) {
        const questionText = $(this).find(`#question_${index}`).val();
        const correct = $(this).find(`#correct_${index}`).val().toUpperCase();
        const choices = [];

        for (let i = 0; i < 4; i++) {
            choices.push($(this).find(`#choices_${index}_${i}`).val());
        }

        data.push({
            question: questionText,
            choices: choices,
            correct: correct,
        });
    });

    localStorage.setItem("assessment_data", JSON.stringify(data));
}

function removeQuestions(question_id, assessment_id) {
    Swal.fire({
        title: "Ooopsss?",
        text: "Are you sure you want to remove this question?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Confirm",
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/assessments/destroyQuestion`,
                type: "POST",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                data: {
                    assessment_id: assessment_id,
                    question_id: question_id,
                },
                beforeSend: function () {
                    pre_loader();
                },
                success: function (response) {
                    if (!response.success) {
                        error_message(response.message);
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                        return;
                    }
                    success_message(response.message);
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                },
                error: function (error) {
                    alert(`error: ${error}`);
                },
            });
        }
    });
}
