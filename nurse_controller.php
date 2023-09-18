<?php
include 'nurse_model.php';

function add_nurse_controller($first_name, $last_name) {
    $nurse_id = add_nurse($first_name, $last_name);
    if($nurse_id) {
        echo "Nurse added successfully with ID: $nurse_id";
        return $nurse_id;
    } else {
        echo "Failed to add nurse.";
        return false;
    }
}

function list_all_nurses_controller() {
    $nurses = get_all_nurses();
    foreach ($nurses as $nurse) {
        echo "ID: " . $nurse['id'] . " - Name: " . $nurse['first_name'] . " " . $nurse['last_name'] . "<br>";
    }
}

function get_nurse_details_controller($id) {
    $nurse = get_nurse_by_id($id);
    if ($nurse) {
        echo "ID: " . $nurse['id'] . " - Name: " . $nurse['first_name'] . " " . $nurse['last_name'];
    } else {
        echo "No nurse found with ID: $id";
    }
}
?>
