<?php
session_start();
include('database/database.php');
include('partials/header.php');
include('partials/sidebar.php');

// Secure SQL Query
$sql = "SELECT * FROM barangay_official";
$params = [];
$types = "";

// Search functionality
if (!empty($_GET['search'])) {
    $search = "%" . trim($_GET['search']) . "%"; 
    $sql .= " WHERE full_name LIKE ? OR middle_name LIKE ? OR last_name LIKE ? OR age LIKE ? OR position LIKE ? OR sex LIKE ?";
    $types = "ssssss";
    $params = array_fill(0, 6, $search); 
}

// Prepare and execute the query
$stmt = $conn->prepare($sql);
if ($stmt) {
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params); 
    }
    $stmt->execute();
    $barangay_officials = $stmt->get_result();
} else {
    die("Error preparing query: " . $conn->error);
}

// Status messages
$status = $_SESSION['status'] ?? '';
unset($_SESSION['status']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay Official Management System</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <main id="main" class="main">
        <?php if ($status): ?>
            <div class="alert alert-<?php echo ($status == 'error') ? 'danger' : 'success'; ?> alert-dismissible fade show" role="alert">
                <?php 
                    if ($status == 'created') echo "New record has been created successfully!";
                    elseif ($status == 'updated') echo "Record has been updated successfully!";
                    elseif ($status == 'deleted') echo "Record has been deleted successfully!";
                    else echo "An error occurred.";
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="pagetitle">
            <h1>Barangay Official Management System</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                    <li class="breadcrumb-item">Tables</li>
                    <li class="breadcrumb-item">General</li>
                </ol>
            </nav>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <h5 class="card-title">Barangay Officials</h5>
                                <button class="btn btn-primary btn-sm mt-4" data-bs-toggle="modal" data-bs-target="#addModal">Add Barangay Official</button>
                            </div>

                            <!-- Search Form -->
                            <form method="GET" action="" class="mb-3">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" placeholder="Search..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                                    <button type="submit" class="btn btn-primary">Search</button>
                                </div>
                            </form>

                            <!-- Barangay Officials Table -->
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>First Name</th>
                                        <th>Middle Name</th>
                                        <th>Last Name</th>
                                        <th>Age</th>
                                        <th>Position</th>
                                        <th>Sex</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($barangay_officials->num_rows > 0): ?>
                                        <?php while ($row = $barangay_officials->fetch_assoc()): ?>
                                            <tr>
                                                <td><?php echo $row['id']; ?></td>
                                                <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                                                <td><?php echo htmlspecialchars($row['middle_name']); ?></td>
                                                <td><?php echo htmlspecialchars($row['last_name']); ?></td>
                                                <td><?php echo htmlspecialchars($row['age']); ?></td>
                                                <td><?php echo htmlspecialchars($row['position']); ?></td>
                                                <td><?php echo htmlspecialchars($row['sex']); ?></td>
                                                <td class="text-center">
                                                    <!-- Edit Button -->
                                                    <button class="btn btn-primary btn-sm edit-btn" 
                                                        data-id="<?php echo $row['id']; ?>" 
                                                        data-full-name="<?php echo $row['full_name']; ?>" 
                                                        data-middle-name="<?php echo $row['middle_name']; ?>" 
                                                        data-last-name="<?php echo $row['last_name']; ?>" 
                                                        data-age="<?php echo $row['age']; ?>" 
                                                        data-position="<?php echo $row['position']; ?>" 
                                                        data-sex="<?php echo $row['sex']; ?>" 
                                                        data-bs-toggle="modal" data-bs-target="#editModal">
                                                        Edit
                                                    </button>
                                                    <!-- View Button -->
                                                    <button class="btn btn-info btn-sm view-btn" 
                                                        data-id="<?php echo $row['id']; ?>" 
                                                        data-full-name="<?php echo $row['full_name']; ?>" 
                                                        data-middle-name="<?php echo $row['middle_name']; ?>" 
                                                        data-last-name="<?php echo $row['last_name']; ?>" 
                                                        data-age="<?php echo $row['age']; ?>" 
                                                        data-position="<?php echo $row['position']; ?>" 
                                                        data-sex="<?php echo $row['sex']; ?>" 
                                                        data-bs-toggle="modal" data-bs-target="#viewModal">
                                                        View
                                                    </button>
                                                    <!-- Delete Button -->
                                                    <button class="btn btn-danger btn-sm delete-btn" 
                                                        data-id="<?php echo $row['id']; ?>" 
                                                        data-full-name="<?php echo $row['full_name']; ?>" 
                                                        data-middle-name="<?php echo $row['middle_name']; ?>" 
                                                        data-last-name="<?php echo $row['last_name']; ?>" 
                                                        data-age="<?php echo $row['age']; ?>" 
                                                        data-position="<?php echo $row['position']; ?>" 
                                                        data-sex="<?php echo $row['sex']; ?>" 
                                                        data-bs-toggle="modal" data-bs-target="#deleteModal">
                                                        Delete
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="8" class="text-center">No records found</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                            <!-- End Table -->
                        </div>
                    </div>

                    <!-- Add Modal -->
                    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addModalLabel">Add Barangay Official</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="database/create.php" method="POST">
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="full_name" class="form-label">First Name</label>
                                            <input type="text" class="form-control" id="full_name" name="full_name" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="middle_name" class="form-label">Middle Name</label>
                                            <input type="text" class="form-control" id="middle_name" name="middle_name" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="last_name" class="form-label">Last Name</label>
                                            <input type="text" class="form-control" id="last_name" name="last_name" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="age" class="form-label">Age</label>
                                            <input type="number" class="form-control" id="age" name="age" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="position" class="form-label">Position</label>
                                            <input type="text" class="form-control" id="position" name="position" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="sex" class="form-label">Sex</label>
                                            <select class="form-control" id="sex" name="sex" required>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Add Official</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="editModal" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Barangay Official</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="database/update.php" method="POST">
                                    <div class="modal-body">
                                        <input type="hidden" id="edit-id" name="id">
                                        <div class="mb-3">
                                            <label class="form-label">First Name</label>
                                            <input type="text" class="form-control" id="edit-full-name" name="full_name" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Middle Name</label>
                                            <input type="text" class="form-control" id="edit-middle-name" name="middle_name" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Last Name</label>
                                            <input type="text" class="form-control" id="edit-last-name" name="last_name" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Age</label>
                                            <input type="number" class="form-control" id="edit-age" name="age" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Position</label>
                                            <input type="text" class="form-control" id="edit-position" name="position" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Sex</label>
                                            <select class="form-control" id="edit-sex" name="sex" required>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Delete Modal -->
                    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Are you sure you want to delete the following record?</p>
                                    <ul>
                                        <li><strong>First Name:</strong> <span id="modalFullName"></span></li>
                                        <li><strong>Middle Name:</strong> <span id="modalMiddleName"></span></li>
                                        <li><strong>Last Name:</strong> <span id="modalLastName"></span></li>
                                        <li><strong>Age:</strong> <span id="modalAge"></span></li>
                                        <li><strong>Position:</strong> <span id="modalPosition"></span></li>
                                        <li><strong>Sex:</strong> <span id="modalSex"></span></li>
                                    </ul>
                                    <!-- Hidden input to store the ID -->
                                    <input type="hidden" id="deleteId">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <!-- Confirm Delete Button -->
                                    <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- View Modal -->
                    <div class="modal fade" id="viewModal" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">View Barangay Official</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>First Name:</strong> <span id="view-full-name"></span></p>
                                    <p><strong>Middle Name:</strong> <span id="view-middle-name"></span></p>
                                    <p><strong>Last Name:</strong> <span id="view-last-name"></span></p>
                                    <p><strong>Age:</strong> <span id="view-age"></span></p>
                                    <p><strong>Position:</strong> <span id="view-position"></span></p>
                                    <p><strong>Sex:</strong> <span id="view-sex"></span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Edit Modal
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', () => {
                const id = button.getAttribute('data-id');
                const fullName = button.getAttribute('data-full-name');
                const middleName = button.getAttribute('data-middle-name');
                const lastName = button.getAttribute('data-last-name');
                const age = button.getAttribute('data-age');
                const position = button.getAttribute('data-position');
                const sex = button.getAttribute('data-sex');

                document.getElementById('edit-id').value = id;
                document.getElementById('edit-full-name').value = fullName;
                document.getElementById('edit-middle-name').value = middleName;
                document.getElementById('edit-last-name').value = lastName;
                document.getElementById('edit-age').value = age;
                document.getElementById('edit-position').value = position;
                document.getElementById('edit-sex').value = sex;
            });
        });

        // Delete Modal
        document.addEventListener("DOMContentLoaded", function () {
            // Attach click event to delete buttons
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', () => {
                    const id = button.getAttribute('data-id');
                    const fullName = button.getAttribute('data-full-name');
                    const middleName = button.getAttribute('data-middle-name');
                    const lastName = button.getAttribute('data-last-name');
                    const age = button.getAttribute('data-age');
                    const position = button.getAttribute('data-position');
                    const sex = button.getAttribute('data-sex');

                    // Populate the modal with data
                    document.getElementById('modalFullName').textContent = fullName;
                    document.getElementById('modalMiddleName').textContent = middleName;
                    document.getElementById('modalLastName').textContent = lastName;
                    document.getElementById('modalAge').textContent = age;
                    document.getElementById('modalPosition').textContent = position;
                    document.getElementById('modalSex').textContent = sex;
                    document.getElementById('deleteId').value = id;

                    // Show the modal
                    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
                    deleteModal.show();
                });
            });

            // Confirm Delete Button
            document.getElementById('confirmDelete').addEventListener('click', function () {
                const id = document.getElementById('deleteId').value;

                if (id) {
                    // Ask for final confirmation
                    const confirmDelete = confirm("Are you sure you want to delete this record? This action cannot be undone.");
                    if (confirmDelete) {
                        // Redirect to delete.php with the selected ID
                        window.location.href = `database/delete.php?id=${id}`;
                    }
                } else {
                    alert("No record selected for deletion."); // Show an error if no ID is found
                }
            });
        });

        // View Modal
        document.querySelectorAll('.view-btn').forEach(button => {
            button.addEventListener('click', () => {
                const fullName = button.getAttribute('data-full-name');
                const middleName = button.getAttribute('data-middle-name');
                const lastName = button.getAttribute('data-last-name');
                const age = button.getAttribute('data-age');
                const position = button.getAttribute('data-position');
                const sex = button.getAttribute('data-sex');

                document.getElementById('view-full-name').textContent = fullName;
                document.getElementById('view-middle-name').textContent = middleName;
                document.getElementById('view-last-name').textContent = lastName;
                document.getElementById('view-age').textContent = age;
                document.getElementById('view-position').textContent = position;
                document.getElementById('view-sex').textContent = sex;
            });
        });
    </script>
</body>
</html>