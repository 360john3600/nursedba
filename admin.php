<?php
include_once 'nurse_controller.php';
include_once 'document_controller.php';
include_once 'nurse_model.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_nurse'])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    add_nurse_controller($first_name, $last_name);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_document'])) {
    $nurse_id = $_POST['nurse_id'];
    $document_name = $_POST['document_name'];
    $document_file = $_FILES['document_file']['name']; 
    $status = 'pending';
    add_document_controller($nurse_id, $document_name, $document_file, $status);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_document_status'])) {
    $document_id = $_POST['document_id'];
    $status = $_POST['status'];
    $note = $_POST['note'];
    update_document_status_controller($document_id, $status, $note);
}

$nurses = get_all_nurses();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<title>Admin Dashboard</title>
</head>
<body>

<div class="container">
    <h1 class="my-4 text-center">Admin Dashboard</h1>

    <div class="mb-4 p-4 border rounded">
        <h2 class="mb-3">Add New Nurse</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="first_name" class="form-label">First Name</label>
                <input type="text" class="form-control" id="first_name" name="first_name" required>
            </div>
            <div class="mb-3">
                <label for="last_name" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="last_name" name="last_name" required>
            </div>
            <button type="submit" class="btn btn-primary" name="add_nurse">Add Nurse</button>
        </form>
    </div>

    <div class="mb-4 p-4 border rounded">
        <h2 class="mb-3">List of Nurses</h2>
        <div class="list-group">
            <?php list_all_nurses_controller(); ?>
        </div>
    </div>

    <div class="mb-4 p-4 border rounded">
        <h2 class="mb-3">Upload New Document</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="nurse_id" class="form-label">Select Nurse</label>
                <select class="form-control" id="nurse_id" name="nurse_id" required>
                    <?php 
                    foreach ($nurses as $nurse) {
                        echo '<option value="' . $nurse['id'] . '">' . $nurse['first_name'] . ' ' . $nurse['last_name'] . ' (ID: ' . $nurse['id'] . ')</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="document_name" class="form-label">Document Name</label>
                <input type="text" class="form-control" id="document_name" name="document_name" required>
            </div>
            <div class="mb-3">
                <label for="document_file" class="form-label">Document File</label>
                <input type="file" class="form-control" id="document_file" name="document_file" required>
            </div>
            <button type="submit" class="btn btn-primary" name="add_document">Upload Document</button>
        </form>
    </div>

    <div class="mb-4 p-4 border rounded">
        <h2 class="mb-3">Nurse Overview</h2>
        <?php foreach ($nurses as $nurse): ?>
            <div class="mb-4 p-3 border rounded">
                <h3><?php echo $nurse['first_name'] . ' ' . $nurse['last_name']; ?> (ID: <?php echo $nurse['id']; ?>)</h3>
                <div>
                    <?php 
                    // Assuming get_documents_by_nurse_id is a function that retrieves all documents for a specific nurse
                    $documents = get_documents_by_nurse_id($nurse['id']); 
                    if($documents){
                        echo '<table class="table">
                                <thead>
                                    <tr>
                                        <th>Document ID</th>
                                        <th>Document Name</th>
                                        <th>Status</th>
                                        <th>Note</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>';
                        foreach($documents as $document){
                            echo '<tr>
                                    <td>'.$document['id'].'</td>
                                    <td>'.$document['document_name'].'</td>
                                    <td>'.$document['status'].'</td>
                                    <td>'.$document['note'].'</td>
                                    <td>
                                        <form method="POST" style="display:inline-block;">
                                            <input type="hidden" name="document_id" value="'.$document['id'].'">
                                            <select name="status" required>
                                                <option value="pending" '.($document['status'] == 'pending' ? 'selected' : '').'>Pending</option>
                                                <option value="verified" '.($document['status'] == 'verified' ? 'selected' : '').'>Verified</option>
                                                <option value="rejected" '.($document['status'] == 'rejected' ? 'selected' : '').'>Rejected</option>
                                            </select>
                                            <input type="text" name="note" value="'.$document['note'].'">
                                            <button type="submit" name="update_document_status">Update</button>
                                        </form>
                                    </td>
                                  </tr>';
                        }
                        echo '  </tbody>
                              </table>';
                    } else {
                        echo "No documents found for this nurse.";
                    }
                    ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
