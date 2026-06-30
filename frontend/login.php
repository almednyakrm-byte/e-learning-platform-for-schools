<!-- login.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.tailwindcss.com" rel="stylesheet">
</head>
<body class="bg-gradient-to-r from-slate-900 to-indigo-500 h-screen">
    <div class="flex justify-center items-center h-full">
        <div class="glassmorphic-card p-10 bg-white/20 backdrop-blur-md rounded-lg shadow-lg">
            <h2 class="text-3xl font-bold text-slate-900 mb-5">Login</h2>
            <form id="login-form">
                <div class="mb-4">
                    <label for="username" class="block text-slate-900 text-sm font-bold mb-2">Username</label>
                    <input type="text" id="username" name="username" class="block w-full p-2 pl-10 text-sm text-slate-900 bg-white/20 rounded-lg border border-slate-900 focus:outline-none focus:ring focus:ring-slate-900 focus:border-slate-900" placeholder="Username" pattern="[A-Za-z\u0600-\u06FF0-9\s]+">
                    <div id="username-error" class="text-red-500 text-sm mt-1"></div>
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-slate-900 text-sm font-bold mb-2">Password</label>
                    <input type="password" id="password" name="password" class="block w-full p-2 pl-10 text-sm text-slate-900 bg-white/20 rounded-lg border border-slate-900 focus:outline-none focus:ring focus:ring-slate-900 focus:border-slate-900" placeholder="Password">
                    <div id="password-error" class="text-red-500 text-sm mt-1"></div>
                </div>
                <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">Login</button>
                <p class="text-sm text-slate-900 mt-2">Don't have an account? <a href="register.php" class="text-indigo-500 hover:text-indigo-700">Register</a></p>
            </form>
        </div>
    </div>

    <script>
        const form = document.getElementById('login-form');
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            const response = await fetch('../backend/auth.php?action=login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ username, password })
            });
            const data = await response.json();
            if (data.success) {
                window.location.href = 'dashboard.php';
            } else {
                document.getElementById('username-error').textContent = data.username_error ? data.username_error : '';
                document.getElementById('password-error').textContent = data.password_error ? data.password_error : '';
            }
        });
    </script>
</body>
</html>



// backend/auth.php
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    // Authentication logic here
    // For demonstration purposes, assume username and password are correct
    echo json_encode([
        'success' => true,
        // 'username_error' => '',
        // 'password_error' => ''
    ]);
} else {
    echo json_encode([
        'success' => false,
        'username_error' => 'Invalid username',
        'password_error' => 'Invalid password'
    ]);
}
?>