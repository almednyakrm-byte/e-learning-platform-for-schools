<?php
// Session validation
session_start();
if (!isset($_SESSION['authenticated'])) {
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
</head>
<body>
    <header class="bg-blue-500 text-white p-4">
        <nav class="flex justify-between">
            <a href="index.php" class="text-lg font-bold">Home</a>
            <div class="flex items-center">
                <span class="mr-4">Welcome, <?php echo $_SESSION['username']; ?></span>
                <a href="logout.php" class="bg-orange-300 hover:bg-orange-400 text-white font-bold py-2 px-4 rounded">Logout</a>
            </div>
        </nav>
    </header>
    <main class="p-4">
        <h1 class="text-3xl font-bold mb-4">Teachers List</h1>
        <div class="flex justify-between mb-4">
            <a href="create_teachers.php" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">Add New Item</a>
            <input type="search" id="search" placeholder="Search" class="py-2 pl-10 text-sm text-gray-700">
        </div>
        <table id="teachers-table" class="w-full table-auto">
            <thead class="bg-blue-500 text-white">
                <tr>
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody id="teachers-tbody">
                <!-- Table content will be populated via AJAX -->
            </tbody>
        </table>
    </main>

    <script>
        // Fetch API to get teachers list
        fetch('../backend/teachers.php')
            .then(response => response.json())
            .then(data => {
                const tbody = document.getElementById('teachers-tbody');
                data.forEach(teacher => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="px-4 py-2">${teacher.id}</td>
                        <td class="px-4 py-2">${teacher.name}</td>
                        <td class="px-4 py-2">
                            <a href="edit_teachers.php?id=${teacher.id}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">Edit</a>
                            <button class="bg-orange-300 hover:bg-orange-400 text-white font-bold py-2 px-4 rounded" onclick="deleteTeacher(${teacher.id})">Delete</button>
                        </td>
                    `;
                    tbody.appendChild(row);
                });
            });

        // Delete teacher via AJAX
        function deleteTeacher(id) {
            fetch('../backend/teachers.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove the deleted row from the table
                    const rows = document.getElementById('teachers-tbody').children;
                    for (let i = 0; i < rows.length; i++) {
                        if (rows[i].children[0].textContent == id) {
                            rows[i].remove();
                            break;
                        }
                    }
                } else {
                    console.error('Error deleting teacher:', data.error);
                }
            });
        }

        // Search bar filtering
        const searchInput = document.getElementById('search');
        searchInput.addEventListener('input', () => {
            const searchValue = searchInput.value.toLowerCase();
            const rows = document.getElementById('teachers-tbody').children;
            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const name = row.children[1].textContent.toLowerCase();
                if (name.includes(searchValue)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });
    </script>
</body>
</html>