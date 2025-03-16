git clone https://github.com/Prasad-12345/Mini-Task-Management-System/tree/master
cd Mini-Task-Management-System
composer install
composer require tymon/jwt-auth
php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"
php artisan jwt:secret
php artisan migrate
make db setup

<!-- authentication -->
Login - POST /api/login
Register - POST /api/register

<!-- Api -->
Get All Tasks - GET /api/tasks
Search Tasks - GET /api/tasks?search=TaskTitle
Filter Tasks - GET /api/tasks?priority=High&status=Pending
Create Task - POST /api/tasks
Update Task - PUT /api/tasks/{id}
Delete Task - DELETE /api/tasks/{id}

<!-- pass token in authorization -->
Authorization: Bearer YOUR_ACCESS_TOKEN