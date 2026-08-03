**list_teachers.php**

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
    <title>Teachers List</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
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
            border-collapse: collapse;
            width: 100%;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 1rem;
        }
        .table th {
            background-color: #1a1d23;
            color: #fff;
        }
        .search-bar {
            padding: 1rem;
            background-color: #f7f7f7;
            border: 1px solid #ddd;
            border-radius: 0.5rem;
        }
        .search-bar input {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 0.5rem;
        }
        .search-bar input:focus {
            outline: none;
            box-shadow: 0 0 0 0.25rem rgba(0, 0, 0, 0.25);
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">Back to Index</a>
        <span class="text-indigo-500 font-bold">Welcome, <?php echo $_SESSION['username']; ?></span>
        <a href="logout.php" class="text-indigo-500 font-bold">Logout</a>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl text-slate-900 font-bold mb-4">Teachers List</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded mb-4" onclick="location.href='create_teachers.php'">Add New Item</button>
        <div class="search-bar">
            <input type="search" id="search-input" placeholder="Search teachers...">
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="searchTeachers()">Search</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="teachers-list">
                <!-- Teachers list will be populated here -->
            </tbody>
        </table>
    </div>

    <script>
        // Fetch API to get teachers list
        async function getTeachers() {
            try {
                const response = await fetch('../backend/teachers.php');
                const data = await response.json();
                const teachersList = document.getElementById('teachers-list');
                teachersList.innerHTML = '';
                data.forEach((teacher) => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${teacher.id}</td>
                        <td>${teacher.name}</td>
                        <td>${teacher.email}</td>
                        <td>
                            <a href="edit_teachers.php?id=${teacher.id}" class="text-indigo-500 font-bold">Edit</a>
                            <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteTeacher(${teacher.id})">Delete</button>
                        </td>
                    `;
                    teachersList.appendChild(row);
                });
            } catch (error) {
                console.error(error);
            }
        }

        // Search teachers
        function searchTeachers() {
            const searchInput = document.getElementById('search-input');
            const searchQuery = searchInput.value.trim();
            if (searchQuery === '') {
                getTeachers();
            } else {
                fetch('../backend/teachers.php?search=' + searchQuery)
                    .then(response => response.json())
                    .then(data => {
                        const teachersList = document.getElementById('teachers-list');
                        teachersList.innerHTML = '';
                        data.forEach((teacher) => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${teacher.id}</td>
                                <td>${teacher.name}</td>
                                <td>${teacher.email}</td>
                                <td>
                                    <a href="edit_teachers.php?id=${teacher.id}" class="text-indigo-500 font-bold">Edit</a>
                                    <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteTeacher(${teacher.id})">Delete</button>
                                </td>
                            `;
                            teachersList.appendChild(row);
                        });
                    })
                    .catch(error => console.error(error));
            }
        }

        // Delete teacher
        async function deleteTeacher(id) {
            if (confirm('Are you sure you want to delete this teacher?')) {
                try {
                    const response = await fetch('../backend/teachers.php', {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ id: id })
                    });
                    if (response.ok) {
                        getTeachers();
                    } else {
                        console.error('Error deleting teacher');
                    }
                } catch (error) {
                    console.error(error);
                }
            }
        }

        // Initialize teachers list
        getTeachers();
    </script>
</body>
</html>

**backend/teachers.php**

<?php
// Database connection
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Search query
$searchQuery = '';
if (isset($_GET['search'])) {
    $searchQuery = $_GET['search'];
}

// SQL query
$sql = "SELECT * FROM teachers";
if ($searchQuery !== '') {
    $sql .= " WHERE name LIKE '%$searchQuery%' OR email LIKE '%$searchQuery%'";
}

// Execute query
$result = $conn->query($sql);

// Fetch data
$data = array();
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// Close connection
$conn->close();

// Output data
echo json_encode($data);
?>

Note: This code assumes you have a `teachers` table in your database with columns `id`, `name`, and `email`. You'll need to modify the SQL query and database connection settings to match your actual database schema.