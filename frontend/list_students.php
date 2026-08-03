**list_students.php**

<?php
session_start();

// Check if user is authenticated
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
    <title>Students</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
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
        <a href="index.php">Back to Index</a>
        <span class="text-indigo-500 font-bold">Welcome, <?php echo $_SESSION['username']; ?></span>
        <a href="logout.php" class="text-red-500">Logout</a>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">Students</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded mb-4" onclick="location.href='create_students.php'">Add New Item</button>
        <div class="search-bar">
            <input type="search" id="search-input" placeholder="Search...">
            <button class="bg-slate-900 hover:bg-slate-800 text-white font-bold py-2 px-4 rounded" onclick="searchStudents()">Search</button>
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
            <tbody id="student-list">
                <?php
                // Fetch students from backend
                $students = fetchStudents();
                foreach ($students as $student) {
                    ?>
                    <tr>
                        <td><?php echo $student['id']; ?></td>
                        <td><?php echo $student['name']; ?></td>
                        <td><?php echo $student['email']; ?></td>
                        <td>
                            <a href="edit_students.php?id=<?php echo $student['id']; ?>" class="text-indigo-500 hover:text-indigo-700">Edit</a>
                            <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteStudent(<?php echo $student['id']; ?>)">Delete</button>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        function searchStudents() {
            const searchInput = document.getElementById('search-input').value;
            fetch('../backend/students.php?search=' + searchInput)
                .then(response => response.json())
                .then(data => {
                    const studentList = document.getElementById('student-list');
                    studentList.innerHTML = '';
                    data.forEach(student => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${student.id}</td>
                            <td>${student.name}</td>
                            <td>${student.email}</td>
                            <td>
                                <a href="edit_students.php?id=${student.id}" class="text-indigo-500 hover:text-indigo-700">Edit</a>
                                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteStudent(${student.id})">Delete</button>
                            </td>
                        `;
                        studentList.appendChild(row);
                    });
                });
        }

        function deleteStudent(id) {
            if (confirm('Are you sure you want to delete this student?')) {
                fetch('../backend/students.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Student deleted successfully!');
                        location.reload();
                    } else {
                        alert('Error deleting student!');
                    }
                })
                .catch(error => console.error(error));
            }
        }

        function fetchStudents() {
            return fetch('../backend/students.php')
                .then(response => response.json())
                .then(data => data.students);
        }
    </script>
</body>
</html>


**students.php (backend)**

<?php
// Database connection
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Search query
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $query = "SELECT * FROM students WHERE name LIKE '%$search%' OR email LIKE '%$search%'";
} else {
    $query = "SELECT * FROM students";
}

// Fetch students
$result = $conn->query($query);
$students = array();
while ($row = $result->fetch_assoc()) {
    $students[] = $row;
}

// Delete student
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM students WHERE id = '$id'";
    $conn->query($query);
    echo json_encode(array('success' => true));
}

// Output students
echo json_encode(array('students' => $students));
$conn->close();
?>


Note: This is a basic implementation and you should adapt it to your specific needs and database schema. Additionally, you should ensure that your backend is secure and follows best practices for database interactions.