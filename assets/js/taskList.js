//task drop
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
    fetch('/update-task-status/' + taskId, {
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

            // text badge update
            dropdown.textContent = this.textContent;

            //class badge update
            const allowedClasses = ["pending", "ongoing", "done", "ko"];
            allowedClasses.forEach(cls => dropdown.classList.remove(cls));
            dropdown.classList.add(newProgressStatus);

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
        .then(data => console.log("Status updated:", data))
        .catch(error => console.error("Error updating task:", error));
}
