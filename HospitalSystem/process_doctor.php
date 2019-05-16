<?php
  $con = mysqli_connect("localhost", "root", "", "hospital_database");

  if (isset($_POST["save"])){
    $fname = $_POST["fname"];
    $lname = $_POST["lname"];

    $sql = "INSERT INTO doctor (first_name, last_name)
            VALUES ('$fname', '$lname')";
    mysqli_query($con, $sql);

    header("location: doctors.php");
  }

  if (isset($_POST["update"])){
    $id = $_POST["id"];
    $fname = $_POST["fname"];
    $lname = $_POST["lname"];

    $sql = "UPDATE doctor SET first_name = '$fname', last_name = '$lname' WHERE id = $id";
    mysqli_query($con, $sql);
    header('location: doctors.php');
  }

  if (isset($_GET["delete"])){
    $id = $_GET["delete"];
    $sql = "DELETE FROM doctor WHERE id=$id";

    mysqli_query($con, $sql);

    
    $sql = "SELECT patient_id FROM doctor_patient WHERE doctor_id=$id";
    $result = mysqli_query($con, $sql);
    while ($row = mysqli_fetch_array($result)){
      $sql = "DELETE FROM patient WHERE id=".$row['patient_id'];
      mysqli_query($con, $sql);

      $sql = "SELECT room_number FROM room_patient WHERE patient_id=".$row['patient_id'];
      $roomResult = mysqli_query($con, $sql);
      $roomRow = mysqli_fetch_array($roomResult);
      $room = $roomRow['room_number'];

      $sql = "UPDATE room SET availability = 'yes' where number = $room";


      mysqli_query($con, $sql);

      $sql = "DELETE from room_patient WHERE patient_id=".$row['patient_id'];
      mysqli_query($con, $sql);
    }

    $sql = "DELETE FROM doctor_patient WHERE doctor_id=$id";
    mysqli_query($con, $sql);

    header("location: doctors.php");
  }

  
?>