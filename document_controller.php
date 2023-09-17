<?php
include 'document_model.php';

function add_document_controller($nurse_id, $document_name, $document_file, $status) {
    // Handle file upload
    $target_dir = "uploads/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    $target_file = $target_dir . basename($document_file);
    move_uploaded_file($_FILES["document_file"]["tmp_name"], $target_file);

    if(add_document($nurse_id, $document_name, $target_file, $status)) {
        echo "Document added successfully.";
    } else {
        echo "Failed to add document.";
    }
}

function list_all_documents_controller() {
    $documents = get_all_documents();
    foreach ($documents as $document) {
        echo "ID: " . $document['id'] . " - Nurse ID: " . $document['nurse_id'] . " - Document Name: " . $document['document_name'] . " - Status: " . $document['status'] . "<br>";
        
        echo '<form method="POST" style="margin-bottom: 20px;">
                <input type="hidden" name="document_id" value="' . $document['id'] . '">
                <label for="status">Update Status: </label>
                <select name="status" id="status" required>
                    <option value="pending" ' . ($document['status'] == 'pending' ? 'selected' : '') . '>Pending</option>
                    <option value="verified" ' . ($document['status'] == 'verified' ? 'selected' : '') . '>Verified</option>
                    <option value="rejected" ' . ($document['status'] == 'rejected' ? 'selected' : '') . '>Rejected</option>
                </select>
                <br>
                <label for="note">Note: </label>
                <input type="text" name="note" id="note" value="' . ($document['note'] ?? '') . '">
                <br>
                <button type="submit" name="update_document_status">Update Status</button>
              </form>';
    }
}

function update_document_status_controller($id, $status, $note) {
    if(update_document_status($id, $status, $note)) {
        echo "Document status updated successfully.";
    } else {
        echo "Failed to update document status.";
    }
}

function get_documents_by_nurse_id($nurse_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM documents WHERE nurse_id = ?");
    $stmt->bind_param("i", $nurse_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $documents = array();
    while ($row = $result->fetch_assoc()) {
        $documents[] = $row;
    }
    return $documents;
}
?>
