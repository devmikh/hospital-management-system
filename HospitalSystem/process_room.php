<?php
  $con = mysqli_connect("localhost", "root", "", "hospital_database");

  if (isset($_POST["save"])){
    $roomNumber = $_POST["roomNumber"];

    $sql = "INSERT INTO room (number, availability)
            VALUES ($roomNumber, 'yes')";
    mysqli_query($con, $sql);

    header("location: rooms.php");
  }

  if (isset($_GET["delete"])){
    $roomNumber = $_GET["delete"];

    // $sql = "SELECT * FROM room_patient WHERE room_number = $roomNumber";
    // $result = mysqli_query($con, $sql);

    // if(mysqli_fetch_array($result) !== false){
      
    // }
    
    $sql = "DELETE FROM room WHERE number=$roomNumber";
    mysqli_query($con, $sql);

    $sql = "SELECT patient_id FROM room_patient WHERE room_number=".$roomNumber;
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_array($result);
    $patientId = $row["patient_id"];

    $sql = "DELETE FROM patient WHERE id=".$patientId;
    mysqli_query($con, $sql);

    $sql = "DELETE FROM room_patient WHERE patient_id=".$patientId;
    mysqli_query($con, $sql);

    header("location: rooms.php");
  }

  
?>