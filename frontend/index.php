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
    <title>منصة تعليم إلكترونية لمدارس</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }
        .glassmorphism-card {
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="flex justify-between items-center p-4 bg-slate-900 text-white">
        <h1 class="text-3xl font-bold">منصة تعليم إلكترونية لمدارس</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='logout.php'">تسجيل الخروج</button>
    </div>
    <div class="flex justify-center items-center p-4 bg-slate-900 text-white">
        <h2 class="text-2xl font-bold">مرحباً بكم في منصة تعليم إلكترونية لمدارس</h2>
    </div>
    <div class="flex justify-center items-center p-4 bg-slate-900 text-white">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php
            // Fetch stats dynamically via Javascript API calls from the backend files
            ?>
            <div class="glassmorphism-card bg-white p-4">
                <h2 class="text-lg font-bold">إحصائيات</h2>
                <div id="stats-grid"></div>
            </div>
            <div class="glassmorphism-card bg-white p-4">
                <h2 class="text-lg font-bold">إدارة الطلاب</h2>
                <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='students.php'">طلاب</button>
            </div>
            <div class="glassmorphism-card bg-white p-4">
                <h2 class="text-lg font-bold">إدارة المدرسين</h2>
                <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='teachers.php'">مدرسين</button>
            </div>
            <div class="glassmorphism-card bg-white p-4">
                <h2 class="text-lg font-bold">إدارة المقررات</h2>
                <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='courses.php'">مقررات</button>
            </div>
        </div>
    </div>

    <script>
        // Fetch stats dynamically via Javascript API calls from the backend files
        fetch('api/stats.php')
            .then(response => response.json())
            .then(data => {
                const statsGrid = document.getElementById('stats-grid');
                data.forEach(stat => {
                    const statCard = document.createElement('div');
                    statCard.classList.add('glassmorphism-card', 'bg-white', 'p-4');
                    statCard.innerHTML = `
                        <h2 class="text-lg font-bold">${stat.title}</h2>
                        <p class="text-lg">${stat.value}</p>
                    `;
                    statsGrid.appendChild(statCard);
                });
            })
            .catch(error => console.error(error));
    </script>
</body>
</html>


Note: This code assumes that you have a backend API set up to fetch the stats data. You'll need to replace `api/stats.php` with the actual URL of your API endpoint. Additionally, you'll need to create the necessary PHP files (e.g. `logout.php`, `students.php`, `teachers.php`, `courses.php`) to handle the logout and module management functionality.