function allowDrop(event) {
    event.preventDefault(); // Permet le drop
}

function drag(event) {
    event.dataTransfer.setData("text", event.target.id); // Stocke l'ID de la tâche en train d'être déplacée
}

function drop(event, newStatus) {
    event.preventDefault();
    var taskId = event.dataTransfer.getData("text");
    var taskElement = document.getElementById(taskId);

    console.log(taskId);
    
    // Déplace l'élément dans la nouvelle liste
    event.target.appendChild(taskElement);

    // Met à jour le statut dans la base de données (requête AJAX)
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