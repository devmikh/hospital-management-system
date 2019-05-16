<?php
  session_start();

  if (isset($_SESSION['user_id'])) {
    $con = mysqli_connect("localhost", "root", "", "hospital_database");

    $query = mysqli_query($con, "select count(*) from doctor");
    $rec = mysqli_fetch_array($query);
    $numberOfDoctors = $rec[0];
  
    $query = mysqli_query($con, "select count(*) from patient");
    $rec = mysqli_fetch_array($query);
    $numberOfPatients = $rec[0];
  
    $query = mysqli_query($con, "select count(*) from room where availability = 'yes'");
    $rec = mysqli_fetch_array($query);
    $numberOfRooms = $rec[0];
  
    mysqli_close($con);
  } else {
      // Redirect to the login page
      header("Location: login.php");
  }
?>

<html>
  <head>
    <title>Dashboard</title>
    <link rel="stylesheet" type="text/css" href="css/main.css">
  </head>
  <body>
    <div class="header-menu">
      <div class="brand">
        <h1><a href="dashboard.php"><span class="current">Hospital++</span></a></h1>
      </div>
      <div class="options">
        <h3><a href="patients.php">Patients</a></h3>
        <h3><a href="doctors.php">Doctors</a></h3>
        <h3><a href="rooms.php">Rooms</a></h3>
      </div>
      <div class="logout">
        <h4><a href="logout.php">Logout</a></h4>
      </div>
    </div>
    <div class="main-content">
      <div class="bg-image-dashboard bg-image"></div>
      <div class="boxes">
        <div class="box">
          <h1>Number of Patients:</h1>
          <div class="amount"><?php print $numberOfPatients?></div>
        </div>
        <div class="box">
          <h1>Number of Doctors:</h1>
          <div class="amount"><?php print $numberOfDoctors?></div>
        </div>
        <div class="box">
          <h1>Rooms Available:</h1>
          <div class="amount"><?php print $numberOfRooms?></div>
        </div>
      </div>
    </div>
  </body>
</html>