<?php
  $con = mysqli_connect("localhost", "root", "", "hospital_database");

  if (isset($_POST["save"])){
    $fname = $_POST["fname"];
    $lname = $_POST["lname"];
    $gender = $_POST["gender"];
    $diagnosis = $_POST["diagnosis"];
    $doctor_id = $_POST["doctor"];
    $room = $_POST["room"];

    $sql = "INSERT INTO patient (first_name, last_name, gender, diagnosis)
            VALUES ('$fname', '$lname', '$gender', '$diagnosis')";
    mysqli_query($con, $sql);

    $patient_id = mysqli_insert_id($con);

    $sql = "INSERT INTO doctor_patient (doctor_id, patient_id) 
            VALUES ($doctor_id, $patient_id)";

    mysqli_query($con, $sql);

    $sql = "INSERT INTO room_patient (room_number, patient_id)
            VALUES ($room, $patient_id)";

    mysqli_query($con, $sql);

    $sql = "UPDATE room SET availability = 'no' where number = $room";
    mysqli_query($con, $sql);

    header("location: patients.php");
  }

  if (isset($_POST["update"])){
    $id = $_POST["id"];
    $fname = $_POST["fname"];
    $lname = $_POST["lname"];
    $gender = $_POST["gender"];
    $diagnosis = $_POST["diagnosis"];
    $doctor = $_POST["doctor"];
    $room = $_POST["room"];

    // retrieve old room
    $sql = "SELECT room_number FROM room_patient WHERE patient_id = $id";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_row($result);
    $oldRoom = $row[0];

    $sql = "UPDATE patient SET first_name = '$fname', last_name = '$lname', gender = '$gender', diagnosis = '$diagnosis' WHERE id = $id";
    mysqli_query($con, $sql);

    $sql = "UPDATE doctor_patient SET doctor_id = '$doctor' WHERE patient_id = $id";
    mysqli_query($con, $sql);

    $sql = "UPDATE room_patient SET room_number = '$room' WHERE patient_id = $id";
    mysqli_query($con, $sql);

    $sql = "UPDATE room set availability = 'no' WHERE number = $room";
    mysqli_query($con, $sql);

    if ($room != $oldRoom){
      $sql = "UPDATE room SET availability = 'yes' WHERE number = $oldRoom";
      mysqli_query($con, $sql);
    }
    header('location: patients.php');
  }

  if (isset($_GET["delete"])){
    $id = $_GET["delete"];
    $sql = "DELETE FROM patient WHERE id=$id";

    mysqli_query($con, $sql);

    $sql = "DELETE FROM doctor_patient WHERE patient_id = $id";
    mysqli_query($con, $sql);

    $sql = "SELECT room_number from room_patient where patient_id = $id";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_row($result);
    $room = $row[0];

    $sql = "DELETE FROM room_patient WHERE patient_id = $id";
    mysqli_query($con, $sql);

    $sql = "UPDATE room SET availability = 'yes' where number = $room";
    mysqli_query($con, $sql);

    header("location: patients.php");
  }

  
?>