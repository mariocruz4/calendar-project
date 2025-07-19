<?php

// 2. Include the connection file to connect to the database
include "connection.php";

$successMsg = "";
$errorMsg = "";
$eventsFromDB = []; // Initialize an empty array to hold events

# Handle Add Appointment
if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST["action"] ?? "") === "add") {
    $course = trim($_POST["course_name"] ?? "");
    $intructor = trim($_POST["instructor_name"] ?? "");
    $start = $_POST["start_date"] ?? "";
    $end = $_POST["end_date"] ?? "";
    $startTime = $_POST["start_time"] ?? "";
    $endTime = $_POST["end_time"] ?? "";

    if ($course && $instructor && $start && $end) {
        $stmt = $conn->prepare(
            "INSERT INTO appointments (course_name, instructor_name, start_date, end_date, start_time, end_time) VALUES (?, ?, ?, ?, ?, ?)"
        )

        $stmt->bind_param("ssssss", $course, $intructor, $start, $end, $startTime, $endTime);

        $stmt->execute();

        $stmt->close();

        header("Location: ".$_SERVER["PHP_SELF"]."?success=1");
        exit;
    } else {
        header("Location: ".$_SERVER["PHP_SELF"]."error");
        exit;
    }
}

#Handle Edit Appointment
if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST["action"] ?? "") === "edit") {

    $id = $_POST["event_id"] ?? null;
    $course = trim($_POST["course_name"] ?? "");
    $intructor = trim($_POST["instructor_name"] ?? "");
    $start = $_POST["start_date"] ?? "";
    $end = $_POST["end_date"] ?? "";
    $startTime = $_POST["start_time"] ?? "";
    $endTime = $_POST["end_time"] ?? "";

    if ($id && $course && $intructor && $start && $end) {
        $stmt = $conn->prepare(
            "UPDATE appointments SET course_name = ?, instructor_name = ?, start_date = ?, end_date = ?, start_time = ?, end_time = ? WHERE id = ?"
        );

        $stmt->bind_param("ssssssi", $course, $intructor, $start, $end, $startTime, $endTime, $id);

        $stmt->execute();

        $stmt->close();

        header("Location: ".$_SERVER["PHP_SELF"]."?success=2");
        exit;
    } else {
        header("Location: ".$_SERVER["PHP_SELF"]."error");
        exit;
    }
}


# Handle Delete Appointment
if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST["action"] ?? "") === "delete") {
    $id = $_POST["event_id"] ?? null;

    if($id) {
        $stmt = $conn->prepare("DELETE FROM appointments WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        header("Location: ".$_SERVER["PHP_SELF"]."?success=3");
        exit;
    }
}

# Succes & Error Messages
if (isset($_GET["success"])) {
    $successMsg = match ($_GET["success"]) {
        '1' => "✅ Appointment added successfully.",
        '2' => "✅ Appointment updated successfully.",
        '3' => "🗑️ Appointment deleted successfully.",
        default => ""
    };
}

if (isset($_GET["error"])) {
    $errorMsg = "❗ Failed to add appointment. Please fill all fields."
}


// Fetch all appointments and Spread Overt Date Range
$result = $conn->query("SELECT * FROM appointments);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $start = new DateTime($row["start_date"]);
        $end = new DateTime($row["end_date"]);

        while ($start <= $end) {
            $eventsFromDB[] = [
                "id" => $row["id"],
                "title" => "{$row["course_name"]} - {$row["instructor_name"]}", "date" => $start->format("Y-m-d"), "start" => $row["start_date"], "end" => $row["end_date"], "start_time" => $row["start_time"], "end_time" => $row["end_time"]
            ];

            $start->modify("+1 day");
        }
    }
}

$conn->close();