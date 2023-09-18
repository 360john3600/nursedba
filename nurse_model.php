<?php
include 'config.php';

function add_nurse($first_name, $last_name) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO nurses (first_name, last_name) VALUES (?, ?)");
    $stmt->bind_param("ss", $first_name, $last_name);

    if ($stmt->execute()) {
        return $conn->insert_id; // Return the ID of the newly created nurse
    } else {
        return false;
    }
}

function get_all_nurses() {
    global $conn;
    $result = $conn->query("SELECT * FROM nurses");

    if ($result->num_rows > 0) {
        $nurses = array();
        while($row = $result->fetch_assoc()) {
            $nurses[] = $row;
        }
        return $nurses;
    } else {
        return array();
    }
}

function get_nurse_by_id($id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM nurses WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null;
    }
}
?>
