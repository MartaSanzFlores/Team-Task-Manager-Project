//task drop and update status
function allowDrop(event) {
    event.preventDefault();
}

function drag(event) {
    event.dataTransfer.setData("text", event.target.id);
}

function drop(event, newStatus) {
    event.preventDefault();
    var taskId = event.dataTransfer.getData("text");
    var taskElement = document.getElementById(taskId);

    event.target.appendChild(taskElement);

    updateTaskStatus(taskId, newStatus);
}

function updateTaskStatus(taskId, newStatus) {
    fetch('/project/api/update-task-status/' + taskId, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ status: newStatus })
    }).then(response => response.json())
        .then(data => console.log("Status updated:", data))
        .catch(error => console.error("Error updating task:", error));
}

//task details
document.addEventListener('DOMContentLoaded', function () {
    var taskDetailsOffcanvas = document.getElementById('taskDetailsOffcanvas');
    taskDetailsOffcanvas.addEventListener('show.bs.offcanvas', function (event) {
        var button = event.relatedTarget;
        document.getElementById('taskTitle').textContent = button.getAttribute('data-title');
        document.getElementById('taskDescription').textContent = button.getAttribute('data-description');
        document.getElementById('taskPriority').textContent = button.getAttribute('data-priority');
    });
});

//task progressState
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.progressState-item').forEach(item => {
        item.addEventListener('click', function () {
            const dropdown = this.closest('.dropdown').querySelector('#dropdownProgressState');
            const newProgressStatus = this.textContent.trim().toLowerCase();
            const archiveButton = this.closest('.task-item').querySelector('.archive-btn');

            // text badge update
            dropdown.textContent = this.textContent;

            //class badge update
            const allowedClasses = ["pending", "ongoing", "done", "ko"];
            allowedClasses.forEach(cls => dropdown.classList.remove(cls));
            dropdown.classList.add(newProgressStatus);

            //archive button
            if (newProgressStatus === 'done') {
                archiveButton.classList.remove('d-none');
            } else {
                archiveButton.classList.add('d-none');
            }
            
            const taskItem = this.closest('.task-item');
            const taskId = taskItem ? taskItem.id : null;

            if (taskId) {
                updateTaskProgressState(taskId, newProgressStatus);
            } else {
                console.error("Error: Task ID not found");
            }

        });
    });
});

function updateTaskProgressState(taskId, newProgressStatus) {

    fetch('/project/api/update-task-progressState/' + taskId, {

        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ progressState: newProgressStatus })

    }).then(response => response.json())
        .then(data => {
            console.log("Status updated:", data);

            const progressBar = document.querySelector('.progress-bar');

            if (progressBar) {
                let progress = Math.round(data.progress);
                let color = data.color ? data.color : '#e5e5e5';
                let text = progress > 0 ? progress + "%" : "No task completed";

                progressBar.style.width = progress > 0 ? progress + "%" : "100%";
                progressBar.style.backgroundColor = color;
                progressBar.setAttribute('aria-valuenow', progress);
                progressBar.textContent = text;
            }

        })
        .catch(error => console.error("Error updating task:", error));
}

//archive task
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.archive-btn').forEach(item => {
        item.addEventListener('click', function () {

            const taskItem = this.closest('.task-item');
            const taskId = taskItem ? taskItem.id : null;

            if (taskId) {
                updateTaskStatus(taskId, 'finished');
                taskItem.classList.add('d-none');
            } else {
                console.error("Error: Task ID not found");
            }

        });
    });
});
