<?php
// Session validation
session_start();
if (!isset($_SESSION['authenticated'])) {
    header('Location: login.php');
    exit;
}

// Current user info
$current_user = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courses Management</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <header class="bg-blue-500 text-white p-4">
        <nav class="flex justify-between">
            <a href="index.php" class="text-lg font-bold">Back to Index</a>
            <span class="text-lg font-bold">Welcome, <?php echo $current_user; ?></span>
            <a href="logout.php" class="text-lg font-bold">Logout</a>
        </nav>
    </header>
    <main class="p-4">
        <h1 class="text-3xl font-bold mb-4">Courses Management</h1>
        <div class="flex justify-between mb-4">
            <button class="bg-orange-300 hover:bg-orange-400 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_courses.php'">Add New Item</button>
            <input type="text" id="search" class="py-2 px-4 rounded" placeholder="Search...">
        </div>
        <table id="courses-table" class="w-full table-auto border-collapse border border-gray-300">
            <thead class="bg-blue-500 text-white">
                <tr>
                    <th class="py-2 px-4 border border-gray-300">ID</th>
                    <th class="py-2 px-4 border border-gray-300">Name</th>
                    <th class="py-2 px-4 border border-gray-300">Actions</th>
                </tr>
            </thead>
            <tbody id="courses-tbody">
                <!-- Table content will be populated by JavaScript -->
            </tbody>
        </table>
    </main>

    <script>
        // Fetch API to get courses list
        fetch('../backend/courses.php')
            .then(response => response.json())
            .then(data => {
                const tbody = document.getElementById('courses-tbody');
                data.forEach(course => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="py-2 px-4 border border-gray-300">${course.id}</td>
                        <td class="py-2 px-4 border border-gray-300">${course.name}</td>
                        <td class="py-2 px-4 border border-gray-300">
                            <a href="edit_courses.php?id=${course.id}" class="text-blue-500 hover:text-blue-700">Edit</a>
                            <button class="text-red-500 hover:text-red-700" onclick="deleteCourse(${course.id})">Delete</button>
                        </td>
                    `;
                    tbody.appendChild(row);
                });
            });

        // Delete course using Fetch API
        function deleteCourse(id) {
            fetch('../backend/courses.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error deleting course');
                }
            });
        }

        // Search bar filtering
        const searchInput = document.getElementById('search');
        searchInput.addEventListener('input', () => {
            const filter = searchInput.value.toUpperCase();
            const rows = document.getElementById('courses-tbody').rows;
            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const nameCell = row.cells[1];
                if (nameCell.textContent.toUpperCase().indexOf(filter) > -1) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });
    </script>
</body>
</html>