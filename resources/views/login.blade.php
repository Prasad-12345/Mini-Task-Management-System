<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="bg-light">
        <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="card shadow p-4" style="width: 400px;">
            <h2 class="text-center mb-4">Login</h2>
            <form id="loginForm">
                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password:</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>
        </div>
    </div>
    </div>
    <script>
        $('#loginForm').submit(function(event) {
            event.preventDefault();
            $.ajax({
                url: '/api/login',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({
                    email: $('#email').val(),
                    password: $('#password').val()
                }),
                success: function(response) {
                    alert('Login successful!');
                    localStorage.setItem('token', response.token);
                    window.location.href = '/task-list';
                },
                error: function(xhr) {
                    alert('Login failed: ' + xhr.responseJSON.message);
                }
            });
        });
    </script>
</body>
</html>