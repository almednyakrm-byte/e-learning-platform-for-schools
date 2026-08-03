<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.tailwindcss.com" rel="stylesheet">
    <style>
        body {
            background-image: linear-gradient(to bottom, #2f4f7f, #1a1d23);
            background-size: 100% 300px;
            background-position: 0% 100%;
            transition: background-position 1s;
        }
        .glassmorphic {
            background: linear-gradient(90deg, #2f4f7f 0%, #1a1d23 100%);
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .glassmorphic::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, #2f4f7f 0%, #1a1d23 100%);
            mix-blend-mode: multiply;
            opacity: 0.5;
        }
    </style>
</head>
<body class="h-screen bg-gray-100 flex justify-center items-center">
    <div class="glassmorphic bg-gradient-to-r from-slate-900 to-indigo-500 p-10 rounded-lg shadow-lg w-96">
        <h1 class="text-3xl font-bold text-white mb-5">Login</h1>
        <form id="login-form" class="space-y-4">
            <div>
                <label for="username" class="block text-sm font-medium text-white">Username</label>
                <input type="text" id="username" name="username" class="block p-2 w-full text-sm text-gray-900 rounded-lg border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" pattern="[A-Za-z\u0600-\u06FF0-9\s]+" required>
                <div id="username-error" class="text-red-500 hidden"></div>
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-white">Password</label>
                <input type="password" id="password" name="password" class="block p-2 w-full text-sm text-gray-900 rounded-lg border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
                <div id="password-error" class="text-red-500 hidden"></div>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Login</button>
        </form>
        <p class="text-sm text-gray-500 mt-5">Don't have an account? <a href="register.php" class="text-indigo-500 hover:text-indigo-700">Register</a></p>
    </div>

    <script>
        const form = document.getElementById('login-form');
        const usernameInput = document.getElementById('username');
        const passwordInput = document.getElementById('password');
        const usernameError = document.getElementById('username-error');
        const passwordError = document.getElementById('password-error');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const username = usernameInput.value.trim();
            const password = passwordInput.value.trim();

            if (!username || !password) {
                alert('Please fill in all fields');
                return;
            }

            try {
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
                    if (data.usernameError) {
                        usernameError.textContent = data.usernameError;
                        usernameError.classList.remove('hidden');
                    } else {
                        usernameError.textContent = '';
                        usernameError.classList.add('hidden');
                    }

                    if (data.passwordError) {
                        passwordError.textContent = data.passwordError;
                        passwordError.classList.remove('hidden');
                    } else {
                        passwordError.textContent = '';
                        passwordError.classList.add('hidden');
                    }
                }
            } catch (error) {
                console.error(error);
                alert('Error logging in');
            }
        });
    </script>
</body>
</html>


This code uses Tailwind CSS to create a premium-looking login page with a glassmorphic layout and gradients. It includes a form for username and password input, with validation rules using standard HTML input pattern validator. The form is submitted using AJAX with the Fetch API to the `../backend/auth.php?action=login` endpoint. The response is handled dynamically, displaying error messages if the login fails. The code also includes a link to the registration page.