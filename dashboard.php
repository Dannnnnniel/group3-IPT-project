<?php
session_start();
include('database/database.php');
include('partials/header.php');
include('partials/sidebar.php');

// Secure SQL Query
$sql = "SELECT * FROM barangay_official WHERE 1=1";

if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $sql .= " AND (full_name LIKE ? OR age LIKE ? OR position LIKE ? OR sex LIKE ?)";
}

$barangay_officials = $conn->query($sql);   
$status = '';
if (isset($_SESSION['status'])) {
    $status = $_SESSION['status'];
    unset($_SESSION['status']);
}
?>

<main id="main" class="main"> 
    <?php if ($status == 'created'): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            New Record has been created successfully!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php elseif ($status == 'updated'): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Record has been updated successfully!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php elseif ($status == 'error'): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            An error occurred 
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php elseif ($status == 'deleted'): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Record has been deleted successfully!
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
                            <h5 class="card-title">Default Table</h5>
                            <!-- Add Barangay Official Button -->
                            <button class="btn btn-primary btn-sm mt-4" data-bs-toggle="modal" data-bs-target="#addModal">Add Barangay Official</button>
                        </div>

                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Age</th>
                                    <th scope="col">Position</th>
                                    <th scope="col">Sex</th>
                                    <th scope="col" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($barangay_officials->num_rows > 0): ?>
                                    <?php while ($row = $barangay_officials->fetch_assoc()): ?>
                                        <tr>
                                            <th scope="row"><?php echo $row['id']; ?></th>
                                            <td><?php echo $row['full_name']; ?></td>
                                            <td><?php echo $row['age']; ?></td>
                                            <td><?php echo $row['position']; ?></td>
                                            <td><?php echo $row['sex']; ?></td>
                                            <td class="d-flex justify-content-center">
                                                <button class="btn btn-primary btn-sm me-2" data-bs-toggle="modal" data-bs-target="#edit<?php echo $row['id']; ?>">Edit</button>
                                                <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#delete<?php echo $row['id']; ?>">Delete</button>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center">No records found</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                        <!-- End Table -->
                    </div>
                </div>



                
                <!-- Add Modal -->
                 <!--AYAW NIYO PAG LABUTAN YADI KAY OKAY NA SIYA-->
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
                                        <label for="full_name" class="form-label">Full Name</label>
                                        <input type="text" class="form-control" id="full_name" name="full_name" required>
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
            </div>
        </div>
    </section>
</main>

<?php include('partials/footer.php'); ?>