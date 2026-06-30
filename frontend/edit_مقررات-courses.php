**edit_مقررات-courses.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Get course ID from URL
$id = $_GET['id'];

// Fetch course details via AJAX
$course = json_decode(file_get_contents('../backend/مقررات-courses.php?id=' . $id), true);

// Check if course exists
if (empty($course)) {
    echo 'Course not found';
    exit;
}

// Set page title and mod slug
$page_title = 'Edit Course';
$mod_slug = 'courses';

// Include header and navigation
include 'header.php';
include 'navigation.php';
?>

<!-- Main content -->
<main class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
    <h1 class="text-3xl font-bold leading-tight text-slate-900 mb-4"><?= $page_title ?></h1>
    <form id="edit-course-form" class="bg-white rounded-lg shadow-md p-4">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label for="name" class="block text-sm font-medium text-slate-900">Course Name</label>
                <input type="text" id="name" name="name" class="block w-full p-2 mt-1 text-sm text-slate-900 bg-white border border-slate-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" value="<?= $course['name'] ?>">
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-slate-900">Course Description</label>
                <textarea id="description" name="description" class="block w-full p-2 mt-1 text-sm text-slate-900 bg-white border border-slate-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"><?= $course['description'] ?></textarea>
            </div>
            <div>
                <label for="price" class="block text-sm font-medium text-slate-900">Course Price</label>
                <input type="number" id="price" name="price" class="block w-full p-2 mt-1 text-sm text-slate-900 bg-white border border-slate-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" value="<?= $course['price'] ?>">
            </div>
            <div>
                <label for="duration" class="block text-sm font-medium text-slate-900">Course Duration</label>
                <input type="text" id="duration" name="duration" class="block w-full p-2 mt-1 text-sm text-slate-900 bg-white border border-slate-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" value="<?= $course['duration'] ?>">
            </div>
        </div>
        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Update Course</button>
    </form>
</main>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/axios@0.27.2/dist/axios.min.js"></script>
<script>
    // Fetch course details via GET
    axios.get('../backend/مقررات-courses.php?id=<?= $id ?>')
        .then(response => {
            // Populate form fields
            document.getElementById('name').value = response.data.name;
            document.getElementById('description').value = response.data.description;
            document.getElementById('price').value = response.data.price;
            document.getElementById('duration').value = response.data.duration;
        })
        .catch(error => console.error(error));

    // Submit form via AJAX PUT
    document.getElementById('edit-course-form').addEventListener('submit', event => {
        event.preventDefault();
        axios.put('../backend/مقررات-courses.php', {
            id: <?= $id ?>,
            name: document.getElementById('name').value,
            description: document.getElementById('description').value,
            price: document.getElementById('price').value,
            duration: document.getElementById('duration').value
        })
            .then(response => {
                // Redirect to list page
                window.location.href = 'list_<?= $mod_slug ?>.php';
            })
            .catch(error => console.error(error));
    });
</script>
<?php
// Include footer
include 'footer.php';
?>


**backend/مقررات-courses.php**

<?php
// Check if course ID is set
if (!isset($_GET['id'])) {
    echo 'Invalid course ID';
    exit;
}

// Fetch course details from database
$course = get_course_details($_GET['id']);

// Output course details as JSON
echo json_encode($course);
?>

// Function to fetch course details from database
function get_course_details($id) {
    // Database connection code here
    // ...
    // Return course details
    return array(
        'id' => $id,
        'name' => 'Course Name',
        'description' => 'Course Description',
        'price' => 99.99,
        'duration' => '1 month'
    );
}
?>