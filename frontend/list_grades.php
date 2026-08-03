**list_grades.php**

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
    <title>Grades Management</title>
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
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 1rem;
        }
        .table th {
            background-color: #1a1d23;
            color: #fff;
        }
        .table td {
            color: #333;
        }
        .table td a {
            color: #1a1d23;
            text-decoration: none;
        }
        .table td a:hover {
            color: #ccc;
        }
        .search-bar {
            width: 50%;
            padding: 1rem;
            border: 1px solid #ddd;
            border-radius: 0.5rem;
            margin: 1rem auto;
        }
        .search-bar input[type="search"] {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 0.5rem;
        }
        .search-bar button[type="submit"] {
            background-color: #1a1d23;
            color: #fff;
            border: none;
            padding: 1rem 2rem;
            border-radius: 0.5rem;
            cursor: pointer;
        }
        .search-bar button[type="submit"]:hover {
            background-color: #1a1d23;
            color: #ccc;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Grades Management</h1>
        <a href="index.php">Back to Index</a>
        <a href="profile.php">User Profile</a>
        <a href="logout.php">Logout</a>
    </div>
    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold mb-4">Grades List</h2>
        <div class="search-bar">
            <input type="search" id="search-input" placeholder="Search...">
            <button type="submit" id="search-button">Search</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Student Name</th>
                    <th>Grade</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="grades-table">
                <?php
                // Fetch grades list from backend
                $response = file_get_contents('../backend/grades.php');
                $grades = json_decode($response, true);
                foreach ($grades as $grade) {
                    echo '<tr>';
                    echo '<td>' . $grade['id'] . '</td>';
                    echo '<td>' . $grade['student_name'] . '</td>';
                    echo '<td>' . $grade['grade'] . '</td>';
                    echo '<td>';
                    echo '<a href="edit_grades.php?id=' . $grade['id'] . '">Edit</a>';
                    echo '<button class="ml-2" onclick="deleteGrade(' . $grade['id'] . ')">Delete</button>';
                    echo '</td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded mt-4" onclick="location.href='create_grades.php'">Add New Item</button>
    </div>

    <script>
        // Search functionality
        const searchInput = document.getElementById('search-input');
        const searchButton = document.getElementById('search-button');
        const gradesTable = document.getElementById('grades-table');

        searchButton.addEventListener('click', () => {
            const searchQuery = searchInput.value.trim();
            if (searchQuery !== '') {
                fetch('../backend/grades.php?search=' + searchQuery)
                    .then(response => response.json())
                    .then(data => {
                        gradesTable.innerHTML = '';
                        data.forEach(grade => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${grade.id}</td>
                                <td>${grade.student_name}</td>
                                <td>${grade.grade}</td>
                                <td>
                                    <a href="edit_grades.php?id=${grade.id}">Edit</a>
                                    <button class="ml-2" onclick="deleteGrade(${grade.id})">Delete</button>
                                </td>
                            `;
                            gradesTable.appendChild(row);
                        });
                    });
            } else {
                fetch('../backend/grades.php')
                    .then(response => response.json())
                    .then(data => {
                        gradesTable.innerHTML = '';
                        data.forEach(grade => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${grade.id}</td>
                                <td>${grade.student_name}</td>
                                <td>${grade.grade}</td>
                                <td>
                                    <a href="edit_grades.php?id=${grade.id}">Edit</a>
                                    <button class="ml-2" onclick="deleteGrade(${grade.id})">Delete</button>
                                </td>
                            `;
                            gradesTable.appendChild(row);
                        });
                    });
            }
        });

        // Delete functionality
        function deleteGrade(id) {
            if (confirm('Are you sure you want to delete this grade?')) {
                fetch('../backend/grades.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Grade deleted successfully!');
                        location.reload();
                    } else {
                        alert('Error deleting grade!');
                    }
                })
                .catch(error => console.error(error));
            }
        }
    </script>
</body>
</html>

**grades.php (backend)**

<?php
// Fetch grades list from database
$grades = array();
$grades[] = array('id' => 1, 'student_name' => 'John Doe', 'grade' => 'A');
$grades[] = array('id' => 2, 'student_name' => 'Jane Doe', 'grade' => 'B');
$grades[] = array('id' => 3, 'student_name' => 'Bob Smith', 'grade' => 'C');

if (isset($_GET['search'])) {
    $searchQuery = $_GET['search'];
    $filteredGrades = array_filter($grades, function($grade) use ($searchQuery) {
        return strpos($grade['student_name'], $searchQuery) !== false || strpos($grade['grade'], $searchQuery) !== false;
    });
    echo json_encode($filteredGrades);
} else {
    echo json_encode($grades);
}
?>

Note: This is a basic implementation and you should replace the `grades.php` backend script with your actual database connection and query logic.