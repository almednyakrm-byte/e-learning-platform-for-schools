**list_طلاب.php**

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
    <title>طلاب</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
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
            text-align: center;
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
        .search-bar input[type="search"] {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 0.5rem;
        }
        .search-bar input[type="search"]:focus {
            outline: none;
            box-shadow: 0 0 0 0.25rem rgba(13, 130, 184, 0.5);
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الرئيسية</a>
        <span class="text-indigo-500 font-bold">مرحباً <?php echo $_SESSION['username']; ?></span>
        <a href="logout.php" class="text-red-500">تسجيل الخروج</a>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl text-slate-900 font-bold mb-4">طلاب</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded mb-4" onclick="location.href='create_طلاب.php'">إضافة جديد</button>
        <div class="search-bar">
            <input type="search" id="search-input" placeholder="بحث...">
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>اسم الطالب</th>
                    <th>تاريخ الميلاد</th>
                    <th>جنس الطالب</th>
                    <th>حذف</th>
                    <th>تعديل</th>
                </tr>
            </thead>
            <tbody id="records-table">
                <?php
                // Fetch records from backend
                $response = file_get_contents('../backend/طلاب.php');
                $records = json_decode($response, true);
                foreach ($records as $record) {
                    echo '<tr>';
                    echo '<td>' . $record['name'] . '</td>';
                    echo '<td>' . $record['birthdate'] . '</td>';
                    echo '<td>' . $record['gender'] . '</td>';
                    echo '<td><button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(' . $record['id'] . ')">حذف</button></td>';
                    echo '<td><a href="edit_طلاب.php?id=' . $record['id'] . '" class="text-indigo-500 font-bold">تعديل</a></td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        function searchRecords() {
            const searchInput = document.getElementById('search-input').value;
            fetch('../backend/طلاب.php?search=' + searchInput)
                .then(response => response.json())
                .then(records => {
                    const recordsTable = document.getElementById('records-table');
                    recordsTable.innerHTML = '';
                    records.forEach(record => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${record.name}</td>
                            <td>${record.birthdate}</td>
                            <td>${record.gender}</td>
                            <td><button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button></td>
                            <td><a href="edit_طلاب.php?id=${record.id}" class="text-indigo-500 font-bold">تعديل</a></td>
                        `;
                        recordsTable.appendChild(row);
                    });
                });
        }

        function deleteRecord(id) {
            if (confirm('هل تريد حذف هذا السجل؟')) {
                fetch('../backend/طلاب.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('تم حذف السجل بنجاح');
                        window.location.reload();
                    } else {
                        alert('حدث خطأ أثناء حذف السجل');
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        }
    </script>
</body>
</html>

**backend/طلاب.php**

<?php
// Fetch records from database
$records = array();
$records[] = array('id' => 1, 'name' => 'محمد', 'birthdate' => '1990-01-01', 'gender' => 'ذكر');
$records[] = array('id' => 2, 'name' => 'سارة', 'birthdate' => '1995-02-02', 'gender' => 'أنثى');
$records[] = array('id' => 3, 'name' => 'عمر', 'birthdate' => '1992-03-03', 'gender' => 'ذكر');

// Search functionality
if (isset($_GET['search'])) {
    $searchTerm = $_GET['search'];
    $records = array_filter($records, function($record) use ($searchTerm) {
        return strpos($record['name'], $searchTerm) !== false || strpos($record['birthdate'], $searchTerm) !== false || strpos($record['gender'], $searchTerm) !== false;
    });
}

// Delete record
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $records = array_filter($records, function($record) use ($id) {
        return $record['id'] !== $id;
    });
    echo json_encode(array('success' => true));
    exit;
}

// Output records as JSON
echo json_encode($records);