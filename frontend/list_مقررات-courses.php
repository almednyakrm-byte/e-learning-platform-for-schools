**list_مقررات-courses.php**

<?php
// Session validation
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
    <title>مقررات (Courses)</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
        }
        .header {
            background-color: #1a1d23;
            color: #fff;
            padding: 1rem;
            text-align: center;
        }
        .header a {
            color: #fff;
            text-decoration: none;
        }
        .header a:hover {
            color: #ccc;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 1rem;
            text-align: left;
        }
        .table th {
            background-color: #1a1d23;
            color: #fff;
        }
        .search-bar {
            width: 50%;
            padding: 1rem;
            border: 1px solid #ccc;
            border-radius: 0.5rem;
        }
        .search-bar input[type="search"] {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 0.5rem;
        }
        .search-bar input[type="search"]:focus {
            outline: none;
            box-shadow: 0 0 0 0.25rem rgba(0, 0, 0, 0.25);
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الصفحة الرئيسية</a>
        <span class="text-indigo-500">مرحباً, <?php echo $_SESSION['username']; ?></span>
        <a href="logout.php">تسجيل خروج</a>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl text-slate-900 mb-4">مقررات (Courses)</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded mb-4" onclick="location.href='create_مقررات-courses.php'">إضافة جديد</button>
        <div class="search-bar">
            <input type="search" id="search-input" placeholder="بحث...">
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>عنوان المقرر</th>
                    <th>وصف المقرر</th>
                    <th>حذف</th>
                    <th>تعديل</th>
                </tr>
            </thead>
            <tbody id="records-table">
                <!-- Records will be loaded here -->
            </tbody>
        </table>
    </div>

    <script>
        const searchInput = document.getElementById('search-input');
        const recordsTable = document.getElementById('records-table');

        function searchRecords() {
            const searchQuery = searchInput.value.trim();
            if (searchQuery) {
                fetch('../backend/مقررات-courses.php', {
                    method: 'GET',
                    params: { search: searchQuery }
                })
                .then(response => response.json())
                .then(data => {
                    recordsTable.innerHTML = '';
                    data.forEach(record => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${record.title}</td>
                            <td>${record.description}</td>
                            <td><button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button></td>
                            <td><button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='edit_مقررات-courses.php?id=${record.id}'">تعديل</button></td>
                        `;
                        recordsTable.appendChild(row);
                    });
                })
                .catch(error => console.error(error));
            } else {
                fetch('../backend/مقررات-courses.php', {
                    method: 'GET'
                })
                .then(response => response.json())
                .then(data => {
                    recordsTable.innerHTML = '';
                    data.forEach(record => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${record.title}</td>
                            <td>${record.description}</td>
                            <td><button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button></td>
                            <td><button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='edit_مقررات-courses.php?id=${record.id}'">تعديل</button></td>
                        `;
                        recordsTable.appendChild(row);
                    });
                })
                .catch(error => console.error(error));
            }
        }

        function deleteRecord(id) {
            if (confirm('هل أنت متأكد من حذف هذا المقرر؟')) {
                fetch('../backend/مقررات-courses.php', {
                    method: 'DELETE',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('تم حذف المقرر بنجاح');
                        searchRecords();
                    } else {
                        alert('حدث خطأ أثناء حذف المقرر');
                    }
                })
                .catch(error => console.error(error));
            }
        }

        searchRecords();
    </script>
</body>
</html>

This code includes the following features:

1. Session validation: Redirects to login.php if the user is not authenticated.
2. Header navigation: Links to index.php, displays the current user's info, and provides a logout link.
3. Table: Displays a list of records with actions (Edit and Delete).
4. Search bar: Filters elements in real-time using the Fetch API.
5. AJAX calls: Fetches list records from '../backend/مقررات-courses.php' (GET) and DELETE requests.

Note: This code assumes that the backend API is implemented in a separate file (`../backend/مقررات-courses.php`) and that it returns JSON data.