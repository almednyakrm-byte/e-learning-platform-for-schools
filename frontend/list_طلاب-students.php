**list_طلاب-students.php**

<?php
// Session validation
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طلاب (Students)</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .bg-slate-900 {
            background-color: #1A1D23;
        }
        .text-indigo-500 {
            color: #6B5CFF;
        }
    </style>
</head>
<body class="bg-slate-900">
    <header class="bg-slate-900 py-4">
        <nav class="container mx-auto flex justify-between items-center">
            <a href="index.php" class="text-indigo-500 hover:text-white">Back to Index</a>
            <div class="flex items-center">
                <p class="text-indigo-500 mr-4">Welcome, <?= $_SESSION['username'] ?></p>
                <a href="logout.php" class="text-indigo-500 hover:text-white">Logout</a>
            </div>
        </nav>
    </header>
    <main class="container mx-auto p-4">
        <h1 class="text-indigo-500 text-3xl mb-4">طلاب (Students)</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_طلاب-students.php'">Add New Item</button>
        <div class="flex justify-between items-center mb-4">
            <input type="search" id="search" class="bg-gray-800 text-gray-300 rounded w-full py-2 pl-10" placeholder="Search...">
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">Search</button>
        </div>
        <table class="w-full border-collapse border border-slate-600">
            <thead>
                <tr>
                    <th class="border border-slate-600 p-2">ID</th>
                    <th class="border border-slate-600 p-2">Name</th>
                    <th class="border border-slate-600 p-2">Actions</th>
                </tr>
            </thead>
            <tbody id="records">
                <?php
                // Fetch records from backend
                $records = fetchRecords();
                foreach ($records as $record) {
                    ?>
                    <tr>
                        <td class="border border-slate-600 p-2"><?= $record['id'] ?></td>
                        <td class="border border-slate-600 p-2"><?= $record['name'] ?></td>
                        <td class="border border-slate-600 p-2">
                            <a href="edit_طلاب-students.php?id=<?= $record['id'] ?>" class="text-indigo-500 hover:text-white">Edit</a>
                            <button class="text-red-500 hover:text-white" onclick="deleteRecord(<?= $record['id'] ?>)">Delete</button>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </main>
    <script>
        function searchRecords() {
            const search = document.getElementById('search').value;
            fetch('../backend/طلاب-students.php?search=' + search)
                .then(response => response.json())
                .then(data => {
                    const records = document.getElementById('records');
                    records.innerHTML = '';
                    data.forEach(record => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td class="border border-slate-600 p-2">${record.id}</td>
                            <td class="border border-slate-600 p-2">${record.name}</td>
                            <td class="border border-slate-600 p-2">
                                <a href="edit_طلاب-students.php?id=${record.id}" class="text-indigo-500 hover:text-white">Edit</a>
                                <button class="text-red-500 hover:text-white" onclick="deleteRecord(${record.id})">Delete</button>
                            </td>
                        `;
                        records.appendChild(row);
                    });
                });
        }

        function deleteRecord(id) {
            if (confirm('Are you sure you want to delete this record?')) {
                fetch('../backend/طلاب-students.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Record deleted successfully!');
                        location.reload();
                    } else {
                        alert('Error deleting record!');
                    }
                });
            }
        }

        function fetchRecords() {
            return fetch('../backend/طلاب-students.php')
                .then(response => response.json())
                .then(data => data.records);
        }
    </script>
</body>
</html>


**backend/طلاب-students.php**

<?php
// Database connection
$conn = mysqli_connect('localhost', 'username', 'password', 'database');

// Search query
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $query = "SELECT * FROM students WHERE name LIKE '%$search%'";
} else {
    $query = "SELECT * FROM students";
}

// Fetch records
$records = array();
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_assoc($result)) {
    $records[] = $row;
}

// Output records
echo json_encode(array('records' => $records));

// Delete record
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = json_decode(file_get_contents('php://input'), true)['id'];
    $query = "DELETE FROM students WHERE id = '$id'";
    mysqli_query($conn, $query);
    echo json_encode(array('success' => true));
}

// Close database connection
mysqli_close($conn);
?>