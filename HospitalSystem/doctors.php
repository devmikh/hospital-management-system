<?php
  session_start();

  if (isset($_SESSION['user_id'])) {

    $id = 0;
    $fname = "";
    $lname = "";

    $update = false;

    $con = mysqli_connect("localhost", "root", "", "hospital_database");

    if (isset($_GET["edit"])){
      $id = $_GET["edit"];
  
      $update = true;

      $sql = "SELECT * FROM doctor WHERE id = $id";
  
      $result = mysqli_query($con, $sql);
        $row = $result->fetch_array();
        $id = $row["id"];
        $fname = $row["first_name"];
        $lname = $row["last_name"];

    }  
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
        <h1><a href="dashboard.php">Hospital++</a></h1>
      </div>
      <div class="options">
        <h3><a href="patients.php">Patients</a></h3>
        <h3><a href="doctors.php"><span class="current">Doctors</span></a></h3>
        <h3><a href="rooms.php">Rooms</a></h3>
      </div>
      <div class="logout">
        <h4><a href="logout.php">Logout</a></h4>
      </div>
    </div>
    <div class="main-content">
      <div class="bg-image-doctors bg-image"></div>
      <div class="content">
        <div class="form">
          <h2>Add New Doctor</h2>
          <form action="process_doctor.php" method="POST">
            <input type="hidden" name="id" value="<?php print $id?>">
            <label>First Name</label>
            <input type="text" placeholder="First Name" name="fname" value="<?php print $fname ?>"><br>
            <label>Last Name</label><br>
            <input type="text" placeholder="Last Name" name="lname" value="<?php print $lname ?>"><br>
            <?php
              if ($update == false){
                print "<input type='submit' value='Save' name='save'>";
              }else{
                print "<input type='submit' value='Update' name='update'>";
              }
            ?>
          </form>
        </div>
        <div class="data">
          <table>
            <tr>
              <th>ID</th>
              <th>First Name</th>
              <th>Last Name</th>
              <th>Action</th>
            </tr>
            <?php
              $con = mysqli_connect("localhost", "root", "", "hospital_database");
              $sql = "SELECT * FROM doctor ORDER BY id DESC";

              $result = mysqli_query($con, $sql);
              $doctors = array();
              while($row = $result->fetch_assoc()){
                $doctors[] = $row;
              }
              

              for ($i = 0; $i < count($doctors); $i++){
                print "<tr>";
                print "<td>".$doctors[$i]["id"]."</td>";
                print "<td>".$doctors[$i]["first_name"]."</td>";
                print "<td>".$doctors[$i]["last_name"]."</td>";
                print "<td><a class='edit_btn' href='doctors.php?edit=".$doctors[$i]["id"]."'>Edit</a>";
                print "<a class='delete_btn' href='process_doctor.php?delete=".$doctors[$i]["id"]."'>Delete</a>";
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