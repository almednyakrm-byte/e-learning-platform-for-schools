**list_مدرسين-teachers.php**

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
    <title>مدرسين (Teachers)</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
        }
        .header {
            background-color: #2d3748;
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
            background-color: #2d3748;
            color: #fff;
        }
        .search-bar {
            width: 50%;
            padding: 1rem;
            border: 1px solid #ccc;
            border-radius: 0.5rem;
        }
        .search-bar input {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 0.5rem;
        }
        .search-bar button {
            background-color: #2d3748;
            color: #fff;
            border: none;
            padding: 1rem 2rem;
            border-radius: 0.5rem;
            cursor: pointer;
        }
        .search-bar button:hover {
            background-color: #3b4453;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الصفحة الرئيسية</a>
        <span class="text-white">مرحباً, <?php echo $_SESSION['username']; ?></span>
        <a href="logout.php">تسجيل الخروج</a>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">مدرسين (Teachers)</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_مدرسين-teachers.php'">إضافة جديد</button>
        <div class="search-bar">
            <input type="search" id="search-input" placeholder="بحث...">
            <button onclick="searchRecords()">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>اسم المدرس</th>
                    <th>البريد الإلكتروني</th>
                    <th>الجوال</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody id="records-table">
                <?php
                // Fetch records from backend
                $records = fetchRecords();
                foreach ($records as $record) {
                    ?>
                    <tr>
                        <td><?php echo $record['name']; ?></td>
                        <td><?php echo $record['email']; ?></td>
                        <td><?php echo $record['phone']; ?></td>
                        <td>
                            <a href="edit_مدرسين-teachers.php?id=<?php echo $record['id']; ?>" class="bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded">تعديل</a>
                            <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(<?php echo $record['id']; ?>)">حذف</button>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        function searchRecords() {
            const searchInput = document.getElementById('search-input').value;
            fetch('../backend/مدرسين-teachers.php?search=' + searchInput)
                .then(response => response.json())
                .then(data => {
                    const recordsTable = document.getElementById('records-table');
                    recordsTable.innerHTML = '';
                    data.forEach(record => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${record.name}</td>
                            <td>${record.email}</td>
                            <td>${record.phone}</td>
                            <td>
                                <a href="edit_مدرسين-teachers.php?id=${record.id}" class="bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded">تعديل</a>
                                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                            </td>
                        `;
                        recordsTable.appendChild(row);
                    });
                });
        }

        function deleteRecord(id) {
            if (confirm('هل تريد حذف هذا المدرس؟')) {
                fetch('../backend/مدرسين-teachers.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('تم حذف المدرس بنجاح');
                        location.reload();
                    } else {
                        alert('حدث خطأ أثناء حذف المدرس');
                    }
                });
            }
        }

        function fetchRecords() {
            return fetch('../backend/مدرسين-teachers.php')
                .then(response => response.json())
                .then(data => data.records);
        }
    </script>
</body>
</html>


**backend/مدرسين-teachers.php**

<?php
// Fetch records from database
$records = array();
$records[] = array('id' => 1, 'name' => 'مدرس 1', 'email' => 'madr1@example.com', 'phone' => '0123456789');
$records[] = array('id' => 2, 'name' => 'مدرس 2', 'email' => 'madr2@example.com', 'phone' => '0987654321');
$records[] = array('id' => 3, 'name' => 'مدرس 3', 'email' => 'madr3@example.com', 'phone' => '1234567890');

// Search records
if (isset($_GET['search'])) {
    $searchInput = $_GET['search'];
    $records = array_filter($records, function($record) use ($searchInput) {
        return strpos($record['name'], $searchInput) !== false || strpos($record['email'], $searchInput) !== false || strpos($record['phone'], $searchInput) !== false;
    });
}

// Output records
header('Content-Type: application/json');
echo json_encode(array('records' => $records));


Note: This code assumes that you have a backend script (`backend/مدرسين-teachers.php`) that fetches records from a database and outputs them in JSON format. You should replace this script with your actual backend logic.