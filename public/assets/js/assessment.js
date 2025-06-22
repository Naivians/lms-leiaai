$(document).ready(function () {
    const debouncedLog = debounce((value) => {
        // get_total_question(value)
        localStorage.setItem("total_question", value);
        generate_question_ui();
    }, 10);

    $("#assessment_time").on("input", function () {
        let value = $(this).val();

        if ($("#assessment_time").val() == "") {
            $("#questions_container").empty()
        }

        const regex = /^[1-9][0-9]*$/;
        if (!regex.test(value)) {
            $(this).val("");
            return;
        }

        debouncedLog(value);
    });
});

// function get_total_question(total){
//     localStorage.setItem('total_question', $total)
// }

function generate_question_ui() {
    let total_questions = localStorage.getItem("total_question");
    let question_container = $("#questions_container");


    for (let index = 0; index < total_questions; index++) {
        const card = `<div class="card mx-auto my-3">
        <div class="card-header bg-primary text-light">
            <input type="text" name="question[]" id="question[]" class="form-control" placeholder="Question here">
        </div>
        <div class="card-body">
            ${[
                "Aircraft cleaning log",
                "Pilot's logbook",
                "Certificate of Airworthiness",
                "Flight instructor license",
            ]
                .map(
                    (option, i) => `
                <div class="form-check my-2">
                    <input class="form-check-input" type="radio" name="choices_${index}" id="q${index}_option${i}">
                    <label class="form-check-label" for="q${index}_option${i}">
                        <input type="text" name="choices[]" id="choices[]" class="form-control" autocomplete="off">
                    </label>
                </div>`
                )
                .join("")}
        </div>
        <div class="card-footer d-flex align-items-center justify-content-between">
            <div class="input-group w-25 d-flex align-items-center">
                <label for="correct_${index}" class="form-label text-secondary me-3 mt-2">Correct Answer</label>
                <input type="text" name="correct_${index}" id="correct_${index}" class="form-control" placeholder="eg. A">
            </div>
            <i class="fa-solid fa-trash btn btn-outline-danger" data-bs-toggle="tooltip" title="remove question"></i>
        </div>
    </div>`;
        question_container.append(card);
    }
}
