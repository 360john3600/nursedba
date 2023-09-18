<?php
include 'config.php';

function add_document($nurse_id, $document_name, $document_file, $status) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO documents (nurse_id, document_name, document_file, status) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $nurse_id, $document_name, $document_file, $status);

    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

function get_all_documents() {
    global $conn;
    $result = $conn->query("SELECT * FROM documents");

    if ($result->num_rows > 0) {
        $documents = array();
        while($row = $result->fetch_assoc()) {
            $documents[] = $row;
        }
        return $documents;
    } else {
        return array();
    }
}

function get_document_by_id($id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM documents WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null;
    }
}

function update_document_status($id, $status, $note) {
    global $conn;
    $stmt = $conn->prepare("UPDATE documents SET status = ?, note = ? WHERE id = ?");
    $stmt->bind_param("ssi", $status, $note, $id);

    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

function delete_document($id) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM documents WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}
?>
