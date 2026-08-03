**list_courses.php**

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
    <title>Courses</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7f7f7;
        }
        .bg-slate-900 {
            background-color: #1a1d23;
        }
        .text-indigo-500 {
            color: #6b7280;
        }
    </style>
</head>
<body class="bg-slate-900">
    <div class="max-w-7xl mx-auto p-4">
        <nav class="bg-slate-900 py-2">
            <div class="container mx-auto flex justify-between items-center">
                <a href="index.php" class="text-indigo-500 hover:text-white">Back to Dashboard</a>
                <div class="flex items-center">
                    <p class="text-indigo-500 mr-2">Hello, <?= $_SESSION['username'] ?></p>
                    <a href="logout.php" class="text-indigo-500 hover:text-white">Logout</a>
                </div>
            </div>
        </nav>
        <div class="container mx-auto p-4 mt-4">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-indigo-500 text-lg">Courses</h2>
                <a href="create_courses.php" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Add New Item</a>
            </div>
            <div class="flex justify-between items-center mb-4">
                <input type="search" id="search" class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Search...">
                <button id="search-btn" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Search</button>
            </div>
            <table class="w-full">
                <thead>
                    <tr>
                        <th class="px-4 py-2">ID</th>
                        <th class="px-4 py-2">Name</th>
                        <th class="px-4 py-2">Description</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody id="courses-table">
                    <!-- Table data will be populated here -->
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Get search input and button elements
        const searchInput = document.getElementById('search');
        const searchBtn = document.getElementById('search-btn');

        // Add event listener to search button
        searchBtn.addEventListener('click', () => {
            // Get search query
            const query = searchInput.value.trim();

            // Fetch data from backend with search query
            fetch('../backend/courses.php?search=' + query)
                .then(response => response.json())
                .then(data => {
                    // Populate table data
                    const tableBody = document.getElementById('courses-table');
                    tableBody.innerHTML = '';
                    data.forEach(course => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td class="px-4 py-2">${course.id}</td>
                            <td class="px-4 py-2">${course.name}</td>
                            <td class="px-4 py-2">${course.description}</td>
                            <td class="px-4 py-2">
                                <a href="edit_courses.php?id=${course.id}" class="text-indigo-500 hover:text-white">Edit</a>
                                <button class="text-red-500 hover:text-white" onclick="deleteCourse(${course.id})">Delete</button>
                            </td>
                        `;
                        tableBody.appendChild(row);
                    });
                })
                .catch(error => console.error(error));
        });

        // Add event listener to search input
        searchInput.addEventListener('input', () => {
            // Get search query
            const query = searchInput.value.trim();

            // Fetch data from backend with search query
            fetch('../backend/courses.php?search=' + query)
                .then(response => response.json())
                .then(data => {
                    // Populate table data
                    const tableBody = document.getElementById('courses-table');
                    tableBody.innerHTML = '';
                    data.forEach(course => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td class="px-4 py-2">${course.id}</td>
                            <td class="px-4 py-2">${course.name}</td>
                            <td class="px-4 py-2">${course.description}</td>
                            <td class="px-4 py-2">
                                <a href="edit_courses.php?id=${course.id}" class="text-indigo-500 hover:text-white">Edit</a>
                                <button class="text-red-500 hover:text-white" onclick="deleteCourse(${course.id})">Delete</button>
                            </td>
                        `;
                        tableBody.appendChild(row);
                    });
                })
                .catch(error => console.error(error));
        });

        // Function to delete course
        function deleteCourse(id) {
            // Confirm deletion
            if (confirm('Are you sure you want to delete this course?')) {
                // Send DELETE request to backend
                fetch('../backend/courses.php?id=' + id, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Update table data
                    const tableBody = document.getElementById('courses-table');
                    tableBody.innerHTML = '';
                    fetch('../backend/courses.php')
                        .then(response => response.json())
                        .then(data => {
                            data.forEach(course => {
                                const row = document.createElement('tr');
                                row.innerHTML = `
                                    <td class="px-4 py-2">${course.id}</td>
                                    <td class="px-4 py-2">${course.name}</td>
                                    <td class="px-4 py-2">${course.description}</td>
                                    <td class="px-4 py-2">
                                        <a href="edit_courses.php?id=${course.id}" class="text-indigo-500 hover:text-white">Edit</a>
                                        <button class="text-red-500 hover:text-white" onclick="deleteCourse(${course.id})">Delete</button>
                                    </td>
                                `;
                                tableBody.appendChild(row);
                            });
                        })
                        .catch(error => console.error(error));
                })
                .catch(error => console.error(error));
            }
        }
    </script>
</body>
</html>

This code includes a premium Tailwind UI layout with a dark color scheme, a search bar, and a table to display the list of courses. The table includes actions to edit and delete each course. The delete action is handled using an AJAX call to the backend to send a DELETE request. The search bar filters the table data in real-time.