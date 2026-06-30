**create_طلاب-students.php**

<?php
// Session validation
if (!isset($_SESSION['mod_slug'])) {
    header('Location: ../index.php');
    exit;
}

// Include header and navigation
include '../includes/header.php';
include '../includes/navigation.php';

// Include form validation library
include '../includes/validation.php';

// Define form validation rules
$validation_rules = [
    'name' => 'required',
    'email' => 'required|email',
    'phone' => 'required|numeric',
    'address' => 'required',
];

// Validate form data
if (isset($_POST['submit'])) {
    $validation = new Validation();
    $validation->set_rules($validation_rules);
    if ($validation->run($_POST)) {
        // Form data is valid, proceed with insertion
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];

        // Insert new record
        $query = "INSERT INTO students (name, email, phone, address) VALUES ('$name', '$email', '$phone', '$address')";
        $result = mysqli_query($conn, $query);

        if ($result) {
            // Record inserted successfully, redirect back to list page
            header('Location: list_' . $_SESSION['mod_slug'] . '.php');
            exit;
        } else {
            // Error inserting record, display error message
            $error_message = 'Error inserting record';
        }
    } else {
        // Form data is invalid, display error messages
        $error_message = $validation->error_array();
    }
}

// Include form view
include '../views/create_طلاب-students.php';
?>

<!-- Include footer -->
<?php include '../includes/footer.php'; ?>


**create_طلاب-students.php (continued)**

<!-- create_طلاب-students.php (HTML) -->
<div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-12">
    <div class="bg-white rounded-lg shadow-md p-4">
        <h2 class="text-slate-900 font-bold text-lg mb-4">Create New Student</h2>
        <form id="create-student-form" method="post">
            <div class="mb-4">
                <label for="name" class="text-slate-900 font-bold text-sm mb-2">Name:</label>
                <input type="text" id="name" name="name" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" placeholder="Enter student name">
            </div>
            <div class="mb-4">
                <label for="email" class="text-slate-900 font-bold text-sm mb-2">Email:</label>
                <input type="email" id="email" name="email" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" placeholder="Enter student email">
            </div>
            <div class="mb-4">
                <label for="phone" class="text-slate-900 font-bold text-sm mb-2">Phone:</label>
                <input type="tel" id="phone" name="phone" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" placeholder="Enter student phone number">
            </div>
            <div class="mb-4">
                <label for="address" class="text-slate-900 font-bold text-sm mb-2">Address:</label>
                <textarea id="address" name="address" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" placeholder="Enter student address"></textarea>
            </div>
            <button type="submit" id="submit-btn" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Create Student</button>
        </form>
    </div>
</div>

<!-- Include JavaScript file for form submission -->
<script src="../js/create_طلاب-students.js"></script>


**create_طلاب-students.js**
javascript
// Get form element
const form = document.getElementById('create-student-form');

// Add event listener to form submission
form.addEventListener('submit', (e) => {
    e.preventDefault();

    // Get form data
    const formData = new FormData(form);

    // Send AJAX request to server
    fetch('../backend/طلاب-students.php', {
        method: 'POST',
        body: formData,
    })
    .then((response) => response.json())
    .then((data) => {
        if (data.success) {
            // Redirect back to list page
            window.location.href = 'list_' + sessionStorage.getItem('mod_slug') + '.php';
        } else {
            // Display error message
            alert(data.error);
        }
    })
    .catch((error) => {
        console.error(error);
    });
});


**Note:** This code assumes that you have a `backend/طلاب-students.php` file that handles the form submission and inserts the new record into the database. You will need to create this file and implement the necessary logic to insert the record. Additionally, you will need to modify the JavaScript code to match your specific form fields and validation rules.