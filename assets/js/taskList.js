document.addEventListener('DOMContentLoaded', function () {

    /* DRAG & DROP */
    document.addEventListener('dragover', function (event) {
        event.preventDefault();
    });

    document.addEventListener('dragstart', function (event) {
        if (event.target.classList.contains('task-item')) {
            event.dataTransfer.setData("text", event.target.id);
        }
    });

    document.addEventListener('drop', function (event) {
        event.preventDefault();
        const taskId = event.dataTransfer.getData("text");
        const taskElement = document.getElementById(taskId);

        if (taskElement && event.target.classList.contains('drop-zone')) {
            event.target.appendChild(taskElement);
            updateTaskStatus(taskId, event.target.dataset.status);
        }
    });

    function updateTaskStatus(taskId, newStatus) {
        fetch(`/project/api/update-task-status/${taskId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ status: newStatus })
        })
        .then(response => response.json())
        .then(data => console.log("Status updated:", data))
        .catch(error => console.error("Error updating task:", error));
    }

    /* TASK DETAILS (Offcanvas) */
    const taskDetailsOffcanvas = document.getElementById('taskDetailsOffcanvas');
    if (taskDetailsOffcanvas) {
        taskDetailsOffcanvas.addEventListener('show.bs.offcanvas', function (event) {
            const button = event.relatedTarget;
            if (button) {
                document.getElementById('taskTitle').textContent = button.getAttribute('data-title') || "N/A";
                document.getElementById('taskDescription').value = button.getAttribute('data-description') || "N/A";
                document.getElementById('taskPriority').value = button.getAttribute('data-priority') || null;
                document.getElementById('taskResponsible').value = button.getAttribute('data-responsible') || null;
                document.getElementById('taskId').value = button.getAttribute('data-task-id') || "N/A";
            }
        });

        taskDetailsOffcanvas.addEventListener('hidden.bs.offcanvas', function () {
            document.body.style.filter = "blur(1px)";
            document.body.style.pointerEvents = "none";
            location.reload();
        });
    }

    /* TASK Responsible */
    const taskResponsibleSelect = document.getElementById('taskResponsible');
    if (taskResponsibleSelect) {
        taskResponsibleSelect.addEventListener('change', function () {
            updateTaskField('responsible', taskResponsibleSelect.value);
        });
    }

    /* TASK Priority */
    const taskPrioritySelect = document.getElementById('taskPriority');
    if (taskPrioritySelect) {
        taskPrioritySelect.addEventListener('change', function () {
            updateTaskField('priority', taskPrioritySelect.value);
        });
    }

    /* TASK Description */
    const taskDescriptionInput = document.getElementById('taskDescription');
    if (taskDescriptionInput) {
        taskDescriptionInput.addEventListener('blur', function () {
            updateTaskField('description', taskDescriptionInput.value);
        });
    }

    /* Function to update a task field */
    function updateTaskField(field, value) {
        const taskId = document.getElementById('taskId').value;
        if (!taskId) return;
    
        fetch(`/project/api/update-task/${taskId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ [field]: value })
        })
        .then(response => response.json())
        .then(data => {
            console.log(`${field} updated:`, data);
            
            if (field === 'responsible') {
                const selectedOption = document.querySelector(`#taskResponsible option[value="${value}"]`);
                if (selectedOption) {
                    document.getElementById('taskResponsible').value = value;
                }
            } else if (field === 'priority') {
                document.getElementById('taskPriority').value = value;
            } else if (field === 'description') {
                document.getElementById('taskDescription').value = value;
            }

        })
        .catch(error => console.error(`Error updating ${field}:`, error));
    }

    /* TASK PROGRESS STATE (Dropdown) */
    document.addEventListener('click', function (event) {
        if (event.target.classList.contains('progressState-item')) {
            const taskItem = event.target.closest('.task');
            if (!taskItem) return;

            const dropdown = taskItem.querySelector('#dropdownProgressState');
            const newProgressStatus = event.target.textContent.trim().toLowerCase();
            const archiveButton = taskItem.querySelector('.archive-btn');

            if (dropdown) {
                dropdown.textContent = event.target.textContent;
                dropdown.classList.remove("pending", "ongoing", "done", "ko");
                dropdown.classList.add(newProgressStatus);
            }

            if (archiveButton) {
                archiveButton.classList.toggle('d-none', !(newProgressStatus === 'done' || newProgressStatus === 'ko'));
            }

            updateTaskProgressState(taskItem.id, newProgressStatus);
        }
    });

    function updateTaskProgressState(taskId, newProgressStatus) {
        fetch(`/project/api/update-task-progressState/${taskId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ progressState: newProgressStatus })
        })
        .then(response => response.json())
        .then(data => {
            console.log("Progress updated:", data);

            const progressBar = document.querySelector('.progress-bar');
            if (progressBar) {
                let progress = Math.round(data.progress) || 0;
                let color = data.color || '#e5e5e5';
                let text = progress > 0 ? `${progress}%` : "No task completed";

                progressBar.style.width = progress > 0 ? `${progress}%` : "100%";
                progressBar.style.backgroundColor = color;
                progressBar.setAttribute('aria-valuenow', progress);
                progressBar.textContent = text;
            }
        })
        .catch(error => console.error("Error updating progress:", error));
    }

    /* ARCHIVE/UNARCHIVE TASK */
    document.addEventListener('click', function (event) {
        if (event.target.classList.contains('archive-btn')) {
            const taskItem = event.target.closest('.task-item');
            if (!taskItem) return;

            updateTaskStatus(taskItem.id, 'finished');
            taskItem.classList.add('d-none');
        }

        if (event.target.classList.contains('unarchive-btn')) {
            const taskItem = event.target.closest('.task-item');
            if (!taskItem) return;

            updateTaskStatus(taskItem.id, 'sprint');
            event.target.classList.add('d-none');
        }
    });

});

