<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Manager</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container py-4">
        <h1 class="text-center mb-4">Task Manager</h1>

        <!-- Error Messages -->
        <div id="error-message" class="alert alert-danger d-none"></div>

        <!-- Task Form -->
        <div class="card shadow-sm p-3 mb-4">
            <h4 class="mb-3">Add Task</h4>
            <form id="taskForm" class="row g-2">
                <div class="col-md-3">
                    <input type="text" id="title" class="form-control" placeholder="Task Title" required>
                </div>
                <div class="col-md-3">
                    <input type="text" id="description" class="form-control" placeholder="Task Description">
                </div>
                <div class="col-md-2">
                    <input type="date" id="due_date" class="form-control" required>
                </div>
                <div class="col-md-2">
                    <select id="priority" class="form-select">
                        <option value="Low">Low</option>
                        <option value="Medium">Medium</option>
                        <option value="High">High</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select id="status" class="form-select">
                        <option value="Pending">Pending</option>
                        <option value="Completed">Completed</option>
                    </select>
                </div>
                <div class="col-md-12">
                    <button type="submit" class="btn btn-success w-100">Add Task</button>
                </div>
            </form>
        </div>

        <!-- Search and Filters -->
        <div class="card shadow-sm p-3 mb-4">
            <h4 class="mb-3">Search & Filters</h4>
            <div class="row g-2">
                <div class="col-md-4">
                    <input type="text" id="searchTask" class="form-control" placeholder="Search by title...">
                </div>
                <div class="col-md-3">
                    <select id="filterPriority" class="form-select">
                        <option value="">Filter by Priority</option>
                        <option value="Low">Low</option>
                        <option value="Medium">Medium</option>
                        <option value="High">High</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select id="filterStatus" class="form-select">
                        <option value="">Filter by Status</option>
                        <option value="Pending">Pending</option>
                        <option value="Completed">Completed</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button id="resetFilters" class="btn btn-secondary w-100">Reset</button>
                </div>
            </div>
        </div>

        <!-- Task List Table -->
        <div class="card shadow-sm p-3">
            <h4 class="mb-3">Task List</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Due Date</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="taskList"></tbody>
            </table>
        </div>
    </div>
    <!-- Edit Task Modal -->
    <div class="modal fade" id="editTaskModal" tabindex="-1" aria-labelledby="editTaskModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editTaskModalLabel">Edit Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editTaskForm">
                        <input type="hidden" id="editTaskId">

                        <div class="mb-3">
                            <label for="editTitle" class="form-label">Title</label>
                            <input type="text" id="editTitle" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="editDescription" class="form-label">Description</label>
                            <textarea id="editDescription" class="form-control"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="editDueDate" class="form-label">Due Date</label>
                            <input type="date" id="editDueDate" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="editPriority" class="form-label">Priority</label>
                            <select id="editPriority" class="form-select" required>
                                <option value="Low">Low</option>
                                <option value="Medium">Medium</option>
                                <option value="High">High</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="editStatus" class="form-label">Status</label>
                            <select id="editStatus" class="form-select" required>
                                <option value="Pending">Pending</option>
                                <option value="Completed">Completed</option>
                            </select>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-warning">Update Task</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script>
        const token = localStorage.getItem('token')
        console.log(token)
        if (!token) {
            showError("Unauthorized! Please log in.");
        }

        function showError(message) {
            $('#error-message').removeClass('d-none').text(message);
            setTimeout(() => $('#error-message').addClass('d-none'), 5000);
        }

        function fetchTasks() {
            if (!token) {
                showError("Unauthorized! Please log in.");
            }

            $.ajax({
                url: '/api/tasks',
                method: 'GET',
                headers: { 'Authorization': `Bearer ${token}` },
                success: function(response) {
                    renderTasks(response.data);
                },
                error: function(err) {
                    showError('Failed to fetch tasks!');
                }
            });
        }

        function renderTasks(tasks) {
            let output = '';

            tasks.forEach(task => {
                output += `
                    <tr data-title="${task.title.toLowerCase()}" data-priority="${task.priority}" data-status="${task.status}">
                        <td>${task.id}</td>
                        <td>${task.title}</td>
                        <td>${task.description || 'No description'}</td>
                        <td>${task.due_date}</td>
                        <td>${task.priority}</td>
                        <td>${task.status}</td>
                        <td>
                            <button class="btn btn-sm btn-info me-2" onclick="editTask(${task.id}, '${task.title}', '${task.description}', '${task.due_date}', '${task.priority}', '${task.status}')">Edit</button>
                            <button class="btn btn-sm btn-danger" onclick="deleteTask(${task.id})">Delete</button>
                        </td>
                    </tr>
                `;
            });

            $('#taskList').html(output);
        }

        $('#taskForm').submit(function(event) {
            event.preventDefault();
            const taskData = {
                title: $('#title').val(),
                description: $('#description').val(),
                due_date: $('#due_date').val(),
                priority: $('#priority').val(),
                status: $('#status').val()
            };

            $.ajax({
                url: '/api/tasks',
                method: 'POST',
                headers: { 'Authorization': `Bearer ${token}`, 'Content-Type': 'application/json' },
                data: JSON.stringify(taskData),
                success: function() { 
                    alert('Task added successfully!');
                    fetchTasks();
                    $('#taskForm')[0].reset();
                },
                error: function() { alert('Failed to add task!'); }
            });
        });

        function editTask(id, title, description, due_date, priority, status) {
            $('#editTaskId').val(id);
            $('#editTitle').val(title);
            $('#editDescription').val(description);
            $('#editDueDate').val(due_date);
            $('#editPriority').val(priority);
            $('#editStatus').val(status);
            $('#editTaskModal').modal('show');
        }

        // Update Task
        $('#editTaskForm').submit(function(event) {
            event.preventDefault();

            const taskId = $('#editTaskId').val();
            const updatedTaskData = {
                id : taskId,
                title: $('#editTitle').val(),
                description: $('#editDescription').val(),
                due_date: $('#editDueDate').val(),
                priority: $('#editPriority').val(),
                status: $('#editStatus').val()
            };

            $.ajax({
                url: `/api/tasks/${taskId}`,
                method: 'PUT',
                headers: { 'Authorization': `Bearer ${token}`, 'Content-Type': 'application/json' },
                data: JSON.stringify(updatedTaskData),
                success: function() {
                    alert('Task updated successfully!');
                    $('#editTaskModal').modal('hide'); 
                    fetchTasks(); 
                },
                error: function(err) {
                    alert('Failed to update task!');
                    console.error(err.responseJSON);
                }
            });
        });

        // Delete Task with Alert
        function deleteTask(id) {
            if (confirm('Are you sure you want to delete this task?')) {
                $.ajax({
                    url: `/api/tasks/${id}`,
                    method: 'DELETE',
                    headers: { 'Authorization': `Bearer ${token}` },
                    success: function() {
                        alert('Task deleted successfully!');
                        fetchTasks();
                    },
                    error: function(err) {
                        alert('Failed to delete task!');
                        console.error(err.responseJSON);
                    }
                });
            }
        }

        // Live Search by Task Title
        $('#searchTask').on('keyup', function() {
            let value = $(this).val().toLowerCase();
            $("#taskList tr").filter(function() {
                $(this).toggle($(this).data("title").indexOf(value) > -1);
            });
        });

        // Filter Tasks by Priority & Status
        function applyFilters() {
            let selectedPriority = $('#filterPriority').val();
            let selectedStatus = $('#filterStatus').val();

            $("#taskList tr").each(function() {
                let rowPriority = $(this).data("priority");
                let rowStatus = $(this).data("status");

                let priorityMatch = selectedPriority === "" || rowPriority === selectedPriority;
                let statusMatch = selectedStatus === "" || rowStatus === selectedStatus;

                $(this).toggle(priorityMatch && statusMatch);
            });
        }

        $('#filterPriority, #filterStatus').on('change', applyFilters);

        // Reset Filters
        $('#resetFilters').click(function() {
            $('#filterPriority, #filterStatus').val("");
            $('#searchTask').val("");
            fetchTasks(); 
        });

        $(document).ready(fetchTasks);
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
