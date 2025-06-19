let db;
const DB_NAME = 'LessonAttachments';
const STORE_NAME = 'attachments';

document.addEventListener('DOMContentLoaded', () => {
    openDB().then(() => {
        previewAttachment();
    });

    $('#attachment').on('change', function () {
        const file = this.files[0];

        if(file.type == 'video/mp4' && file.size > (10 * 1024 * 1024)) {
            Swal.fire({
                icon: 'error',
                title: 'File too large',
                text: 'Please upload a file smaller than 10MB. or create a link to the file.',
            });
            this.value = ''; // Clear the input
            return;
        }

        if (file) {
            saveAttachment(file);
            $('#attachment').val('');
        }
    });
});

function openDB() {
    return new Promise((resolve, reject) => {
        const request = indexedDB.open(DB_NAME, 1);

        request.onerror = () => reject('DB failed to open');
        request.onsuccess = () => {
            db = request.result;
            resolve();
        };

        request.onupgradeneeded = function (e) {
            db = e.target.result;
            if (!db.objectStoreNames.contains(STORE_NAME)) {
                db.createObjectStore(STORE_NAME, { keyPath: 'id', autoIncrement: true });
            }
        };
    });
}

function saveAttachment(file) {
    pre_loader();

    const transaction = db.transaction([STORE_NAME], 'readwrite');
    const store = transaction.objectStore(STORE_NAME);

    const attachment = {
        name: file.name,
        type: file.type,
        file: file
    };

    store.add(attachment);

    transaction.oncomplete = () => {
        Swal.close();
        previewAttachment();
    }
    transaction.onerror = () => alert('Failed to save attachment');
}

function previewAttachment() {
    const previewContainer = $('.attachment-preview');
    previewContainer.empty();

    const transaction = db.transaction([STORE_NAME], 'readonly');
    const store = transaction.objectStore(STORE_NAME);
    const request = store.getAll();

    request.onsuccess = function () {
        const attachments = request.result;

        attachments.forEach(attachment => {
            const file = attachment.file;
            const index = attachment.id;
            let previewHtml = '';

            const url = URL.createObjectURL(file);

            if (file.type.startsWith('image/')) {
                previewHtml = `<img src="${url}" style="width: 80px; height: 80px; object-fit: cover;">`;
            } else if (file.type === 'application/pdf') {
                previewHtml = `<img src="https://cdn-icons-png.flaticon.com/512/337/337946.png" style="width: 80px; height: 80px; object-fit: contain;">`;
            } else if (file.type === 'video/mp4') {
                previewHtml = `<video src="${url}" style="width: 80px; height: 80px; object-fit: cover;" muted autoplay loop></video>`;
            } else {
                previewHtml = `<img src="https://cdn-icons-png.flaticon.com/512/109/109612.png" style="width: 80px; height: 80px;">`;
            }

            const card = `
                <div class="card" style="width: 200px;" data-id="${index}">
                    <div class="card-body text-center">
                        ${previewHtml}
                        <p class="card-text">${file.name}</p>
                    </div>
                    <div class="card-footer">
                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeAttachment(${index})">
                            <i class="fa-solid fa-trash"></i> Remove
                        </button>
                    </div>
                </div>
            `;

            previewContainer.append(card);
        });
    };
}

function removeAttachment(id) {
    const transaction = db.transaction([STORE_NAME], 'readwrite');
    const store = transaction.objectStore(STORE_NAME);
    store.delete(id);

    transaction.oncomplete = () => previewAttachment();
}


$('#lessons_form').on('submit', function (e) {
    e.preventDefault();

    const formData = new FormData(this);

    const transaction = db.transaction([STORE_NAME], 'readonly');
    const store = transaction.objectStore(STORE_NAME);
    const request = store.getAll();

    request.onsuccess = function () {
        const attachments = request.result;

        attachments.forEach((attachment, i) => {
            formData.append('attachments[]', attachment.file, attachment.name);
        });

        $.ajax({
            url: '/lessons/store',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function () {
                pre_loader();
            },
            success: function (response) {

                if (!response.success) {
                    error_message(response.message)
                }
                success_message(response.message)
                setTimeout(() => {
                    clearAttachments();
                    window.location.reload();
                }, 1500);

            },
            error: function (error) {
                alert('Failed to create lesson');
                clearAttachments()
            }
        });
    };
});
function clearAttachments() {
    const transaction = db.transaction([STORE_NAME], 'readwrite');
    const store = transaction.objectStore(STORE_NAME);
    store.clear();

    transaction.oncomplete = () => previewAttachment();
}


function remove_to_db(material_id) {
    Swal.fire({
        title: "Ooopsss?",
        text: "Are you sure you want to delete this attachment?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Confirm",
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/lessons/deleteMaterials/${material_id}`,
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
                        window.location.reload()
                    }, 1500)

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


