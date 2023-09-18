<?php

include_once 'nurse_controller.php';
include_once 'document_controller.php';
include_once 'nurse_model.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_nurse_and_document'])) {
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $document_name = $_POST['document_name'];
        $document_file = $_FILES['document_file']['name']; 
        $status = 'pending';

        // First add the nurse and get the ID
        $nurse_id = add_nurse_controller($first_name, $last_name);

        // Then add the document using the nurse ID
        add_document_controller($nurse_id, $document_name, $document_file, $status);
    }

    if (isset($_POST['add_document'])) {
        $nurse_id = $_POST['nurse_id'];
        $document_name = $_POST['document_name'];
        $document_file = $_FILES['document_file']['name']; 
        $status = 'pending';
        add_document_controller($nurse_id, $document_name, $document_file, $status);
    }

    if (isset($_POST['update_document_status'])) {
        $document_id = $_POST['document_id'];
        $status = $_POST['status'];
        $note = $_POST['note'];
        update_document_status_controller($document_id, $status, $note);
    }
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
<style>
    .btn-primary {
        background-color: #1d68a7;
    }
    .btn-link {
        color: #1d68a7;
    }
    select.form-select {
        width: auto;
        display: inline-block;
        margin-right: 10px;
    }
    .badge {
        font-size: 100%;
    }
</style>
</head>
<body>

<div class="container">
    <h1 class="my-4 text-center">Admin Dashboard</h1>

    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link active" data-bs-toggle="tab" href="#addNurse">Add New Nurse</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#listNurses">List of Nurses</a>
        </li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane fade show active" id="addNurse">
            <div class="mb-4 p-4 border rounded">
                <h2 class="mb-3">Add New Nurse and Upload Documents</h2>
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="first_name" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="last_name" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="document_name" class="form-label">Document Name</label>
                        <input type="text" class="form-control" id="document_name" name="document_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="document_file" class="form-label">Document File</label>
                        <input type="file" class="form-control" id="document_file" name="document_file" required>
                    </div>
                    <button type="submit" class="btn btn-primary" name="add_nurse_and_document">Add Nurse and Upload Document</button>
                </form>
            </div>
        </div>

        <div class="tab-pane fade" id="listNurses">
            <div class="mb-4 p-4 border rounded">
                <h2 class="mb-3">List of Nurses</h2>
                <div class="list-group">
                    <?php foreach ($nurses as $nurse): ?>
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between">
                                <span><?php echo $nurse['first_name'] . ' ' . $nurse['last_name']; ?> (ID: <?php echo $nurse['id']; ?>)</span>
                                <div>
                                    <button type="button" class="btn btn-link" data-bs-toggle="collapse" data-bs-target="#documents-<?php echo $nurse['id']; ?>">Toggle Documents</button>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDocumentModal-<?php echo $nurse['id']; ?>">Add New Document</button>
                                </div>
                            </div>
                            <div class="collapse" id="documents-<?php echo $nurse['id']; ?>">
                                <table class="table table-striped mt-2">
                                    <thead>
                                        <tr>
                                            <th>Document ID</th>
                                            <th>Document Name</th>
                                            <th>Status</th>
                                            <th>Note</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $documents = get_documents_by_nurse_id($nurse['id']);
                                        if($documents){
                                            foreach($documents as $document){
                                                echo '<tr>';
                                                echo '<td>'.$document['id'].'</td>';
                                                echo '<td>'.$document['document_name'].'</td>';
                                                echo '<td><span class="badge bg-'.($document['status'] == 'pending' ? 'warning' : ($document['status'] == 'compliant' ? 'success' : 'danger')).'">'.$document['status'].'</span></td>';
                                                echo '<td>'.$document['note'].'</td>';
                                                echo '<td>';
                                                echo '<form method="POST" style="display:inline-block;">';
                                                echo '<input type="hidden" name="document_id" value="'.$document['id'].'">';
                                                echo '<select name="status" class="form-select" required>';
                                                echo '<option value="pending" '.($document['status'] == 'pending' ? 'selected' : '').'>Pending</option>';
                                                echo '<option value="compliant" '.($document['status'] == 'compliant' ? 'selected' : '').'>Compliant</option>';
                                                echo '<option value="non-compliant" '.($document['status'] == 'non-compliant' ? 'selected' : '').'>Non-Compliant</option>';
                                                echo '</select>';
                                                echo '<input type="text" name="note" class="form-control form-control-sm d-inline-block" style="width: 200px;" value="'.$document['note'].'">';
                                                echo '<button type="submit" name="update_document_status" class="btn btn-sm btn-primary">Update</button>';
                                                echo '</form>';
                                                echo '<button type="button" class="btn btn-sm btn-danger">Delete</button>';
                                                echo '</td>';
                                                echo '</tr>';
                                            }
                                        } else {
                                            echo '<tr><td colspan="5">No documents found for this nurse.</td></tr>';
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Modal to add new document -->
                            <div class="modal fade" id="addDocumentModal-<?php echo $nurse['id']; ?>" tabindex="-1" aria-labelledby="addDocumentModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="addDocumentModalLabel">Add New Document for <?php echo $nurse['first_name'] . ' ' . $nurse['last_name']; ?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form method="POST" enctype="multipart/form-data">
                                                <input type="hidden" name="nurse_id" value="<?php echo $nurse['id']; ?>">
                                                <div class="mb-3">
                                                    <label for="document_name-<?php echo $nurse['id']; ?>" class="form-label">Document Name</label>
                                                    <input type="text" class="form-control" id="document_name-<?php echo $nurse['id']; ?>" name="document_name" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="document_file-<?php echo $nurse['id']; ?>" class="form-label">Document File</label>
                                                    <input type="file" class="form-control" id="document_file-<?php echo $nurse['id']; ?>" name="document_file" required>
                                                </div>
                                                <button type="submit" class="btn btn-primary" name="add_document">Upload Document</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
