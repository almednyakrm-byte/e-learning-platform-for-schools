<?php
session_start();

// Check if user is authenticated
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>منصة تعليم إلكتروني لمدارس</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
        }
        .glassmorphism-card {
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="flex justify-between items-center p-4 bg-slate-900">
        <h1 class="text-3xl text-white">منصة تعليم إلكتروني لمدارس</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='logout.php'">تسجيل خروج</button>
    </div>
    <div class="flex justify-center items-center p-4 bg-slate-900">
        <div class="glassmorphism-card w-1/2 p-4">
            <h2 class="text-2xl text-white">مرحباً بكم في منصة تعليم إلكتروني لمدارس</h2>
            <div class="flex justify-between items-center mt-4">
                <div class="w-1/2">
                    <h3 class="text-lg text-white">إحصائيات</h3>
                    <div id="stats-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-4"></div>
                </div>
                <div class="w-1/2">
                    <h3 class="text-lg text-white">إدارة المواد</h3>
                    <div class="flex justify-between items-center mt-4">
                        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='students.php'">طلاب</button>
                        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='teachers.php'">معلمين</button>
                        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='courses.php'">مقررات</button>
                        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='grades.php'">درجات</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Fetch stats dynamically via API calls
        fetch('stats.php')
            .then(response => response.json())
            .then(data => {
                const statsGrid = document.getElementById('stats-grid');
                data.forEach(stat => {
                    const statCard = document.createElement('div');
                    statCard.classList.add('bg-white', 'rounded', 'p-4', 'm-4');
                    statCard.innerHTML = `
                        <h3 class="text-lg text-gray-600">${stat.title}</h3>
                        <p class="text-2xl text-gray-900">${stat.value}</p>
                    `;
                    statsGrid.appendChild(statCard);
                });
            })
            .catch(error => console.error(error));
    </script>
</body>
</html>


This code uses Tailwind CSS for styling and includes a session check to redirect to the login page if the user is not authenticated. The dashboard layout includes a welcome message, logout button, overview stats grid, and quick links to manage modules. The stats grid is populated dynamically via API calls from the backend files.

Note: You'll need to create a `stats.php` file to handle the API calls and return the stats data in JSON format.