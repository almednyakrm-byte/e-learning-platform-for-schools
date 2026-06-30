**list_معلمين.php**

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
    <title>معلمين</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7f7f7;
        }
        .header {
            background-color: #1a1d23;
            color: #fff;
        }
        .header a {
            color: #fff;
        }
        .header a:hover {
            color: #ccc;
        }
        .table {
            background-color: #fff;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .table th, .table td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        .table th {
            background-color: #f0f0f0;
        }
        .table th, .table td {
            text-align: center;
        }
        .table th {
            font-weight: bold;
        }
        .search-bar {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .search-bar input[type="search"] {
            padding: 10px;
            border: none;
            border-radius: 10px;
            width: 100%;
            font-size: 16px;
        }
        .search-bar button[type="submit"] {
            padding: 10px 20px;
            border: none;
            border-radius: 10px;
            background-color: #1a1d23;
            color: #fff;
            cursor: pointer;
        }
        .search-bar button[type="submit"]:hover {
            background-color: #2c3e50;
        }
    </style>
</head>
<body>
    <div class="header flex justify-between items-center p-4">
        <a href="index.php"><i class="fas fa-arrow-left"></i> الرجوع إلى الرئيسية</a>
        <div class="flex items-center">
            <img src="profile-picture.jpg" alt="Profile Picture" class="w-10 h-10 rounded-full mr-2">
            <span class="text-lg font-bold"><?= $_SESSION['username']; ?></span>
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded ml-2" onclick="location.href='logout.php'">تسجيل الخروج</button>
        </div>
    </div>
    <div class="container mx-auto p-4">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-3xl font-bold">معلمين</h1>
            <button class="bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_معلمين.php'">إضافة جديد</button>
        </div>
        <div class="search-bar flex justify-between items-center mb-4">
            <input type="search" id="search-input" placeholder="بحث...">
            <button type="submit" id="search-button">بحث</button>
        </div>
        <table class="table w-full">
            <thead>
                <tr>
                    <th>اسم المعلم</th>
                    <th>تليفون</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody id="table-body">
                <!-- Table rows will be populated here -->
            </tbody>
        </table>
    </div>

    <script>
        // Get search input and button elements
        const searchInput = document.getElementById('search-input');
        const searchButton = document.getElementById('search-button');

        // Add event listener to search button
        searchButton.addEventListener('click', async () => {
            const searchQuery = searchInput.value.trim();
            if (searchQuery) {
                // Make GET request to backend to fetch filtered data
                const response = await fetch(`../backend/معلمين.php?search=${searchQuery}`);
                const data = await response.json();
                populateTable(data);
            } else {
                // Make GET request to backend to fetch all data
                const response = await fetch('../backend/معلمين.php');
                const data = await response.json();
                populateTable(data);
            }
        });

        // Function to populate table with data
        function populateTable(data) {
            const tableBody = document.getElementById('table-body');
            tableBody.innerHTML = '';
            data.forEach((item) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${item.اسم المعلم}</td>
                    <td>${item.تليفون}</td>
                    <td>
                        <a href="edit_معلمين.php?id=${item.id}" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded mr-2">تعديل</a>
                        <button class="bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded" onclick="deleteItem(${item.id})">حذف</button>
                    </td>
                `;
                tableBody.appendChild(row);
            });
        }

        // Function to delete item
        async function deleteItem(id) {
            if (confirm('هل تريد حذف هذا العنصر؟')) {
                const response = await fetch(`../backend/معلمين.php?action=delete&id=${id}`, { method: 'DELETE' });
                if (response.ok) {
                    alert('تم حذف العنصر بنجاح');
                    location.reload();
                } else {
                    alert('حدث خطأ أثناء حذف العنصر');
                }
            }
        }
    </script>
</body>
</html>

This code includes a premium Tailwind UI design with a specific color palette matching the theme. It also includes session validation, a search bar, and AJAX calls to fetch and delete data from the backend. The table is populated with data from the backend, and each row includes edit and delete buttons. The delete button triggers an AJAX call to delete the item from the backend.