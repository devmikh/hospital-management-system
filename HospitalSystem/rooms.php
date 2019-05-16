<?php
  session_start();

  if (isset($_SESSION['user_id'])) {

    $con = mysqli_connect("localhost", "root", "", "hospital_database");
 
  } else {
      // Redirect to the login page
      header("Location: login.php");
  }
?>

<html>
  <head>
    <title>Rooms</title>
    <link rel="stylesheet" type="text/css" href="css/main.css">
  </head>
  <body>
    <div class="header-menu">
      <div class="brand">
        <h1><a href="dashboard.php">Hospital++</a></h1>
      </div>
      <div class="options">
        <h3><a href="patients.php">Patients</a></h3>
        <h3><a href="doctors.php">Doctors</a></h3>
        <h3><a href="rooms.php"><span class="current">Rooms</span></a></h3>
      </div>
      <div class="logout">
        <h4><a href="logout.php">Logout</a></h4>
      </div>
    </div>
    <div class="main-content">
      <div class="bg-image-doctors bg-image"></div>
      <div class="content">
        <div class="form">
          <h2>Add New Room</h2>
          <form action="process_room.php" method="POST">
            <label>Room Number</label>
            <input type="text" placeholder="Room Number" name="roomNumber"><br>
            <input type='submit' value='Save' name='save'>
          </form>
        </div>
        <div class="data">
          <table>
            <tr>
              <th>Room Number</th>
              <th>Available</th>
              <th>Action</th>
            </tr>
            <?php
              $con = mysqli_connect("localhost", "root", "", "hospital_database");
              $sql = "SELECT * FROM room ORDER BY number";

              $result = mysqli_query($con, $sql);
              $rooms = array();
              while($row = $result->fetch_assoc()){
                $rooms[] = $row;
              }
              

              for ($i = 0; $i < count($rooms); $i++){
                print "<tr>";
                print "<td>".$rooms[$i]["number"]."</td>";
                print "<td>".$rooms[$i]["availability"]."</td>";
                print "<td><a class='delete_btn' href='process_room.php?delete=".$rooms[$i]["number"]."'>Delete</a>";
                print "</td>";

                print "</tr>";
              }
              mysqli_close($con);
            ?>
          </table>
        </div>
    </div>
  </body>
</html>