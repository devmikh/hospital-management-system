<?php
  session_start();

  if (isset($_SESSION['user_id'])) {

    $id = 0;
    $fname = "";
    $lname = "";
    $diagnosis = "";
    $doctor = "";
    $room = "";
    $gender = "male";

    $update = false;

    $con = mysqli_connect("localhost", "root", "", "hospital_database");

    if (isset($_GET["edit"])){
      $id = $_GET["edit"];
  
      $update = true;

      $sql = "SELECT * FROM patient WHERE id = $id";
  
      $result = mysqli_query($con, $sql);
        $row = $result->fetch_array();
        $id = $row["id"];
        $fname = $row["first_name"];
        $lname = $row["last_name"];
        $gender = $row["gender"];
        $diagnosis = $row["diagnosis"];

      $sql = "SELECT room_number FROM room_patient WHERE patient_id = $id";
      $result = mysqli_query($con, $sql);
      $row = $result->fetch_array();
      $room = $row[0];
      
      $sql = "SELECT doctor_id FROM doctor_patient WHERE patient_id = $id";
      $result = mysqli_query($con, $sql);
      $row = $result->fetch_array();
      $doctor = $row[0];

    }  
  } else {
      // Redirect to the login page
      header("Location: login.php");
  }
?>

<html>
  <head>
    <title>Patients</title>
    <link rel="stylesheet" type="text/css" href="css/main.css">
  </head>
  <body>
    <div class="header-menu">
      <div class="brand">
        <h1><a href="dashboard.php">Hospital++</a></h1>
      </div>
      <div class="options">
        <h3><a href="patients.php"><span class="current">Patients</span></a></h3>
        <h3><a href="doctors.php">Doctors</a></h3>
        <h3><a href="rooms.php">Rooms</a></h3>
      </div>
      <div class="logout">
        <h4><a href="logout.php">Logout</a></h4>
      </div>
    </div>
    <div class="main-content">
      <div class="bg-image-patients bg-image"></div>
      <div class="content">
        <div class="form">
          <h2>Add New Patient</h2>
          <form action="process_patient.php" method="POST">
            <input type="hidden" name="id" value="<?php print $id?>">
            <label>First Name</label>
            <input type="text" placeholder="First Name" name="fname" value="<?php print $fname ?>"><br>
            <label>Last Name</label>
            <input type="text" placeholder="Last Name" name="lname" value="<?php print $lname ?>"><br>
            <label>Gender</label><br>
            <div class="radio-group">
              <?php
                if ($gender == "male"){
                  print "<input type='radio' name='gender' value='male' checked>
                  <label>Male</label>
                  <input type='radio' name='gender' value='female'>
                  <label>Female</label>";
                }else{
                  print "<input type='radio' name='gender' value='male'>
                  <label>Male</label>
                  <input type='radio' name='gender' value='female' checked>
                  <label>Female</label>";
                }
              ?>
              <br>
            </div>
            <label>Diagnosis</label>
            <input type="text" placeholder="Diagnosis" name="diagnosis" value="<?php print $diagnosis ?>"><br>
            <label>Doctor</label><br>

            

            <select name="doctor">
               <?php
                $con = mysqli_connect("localhost", "root", "", "hospital_database");

                $sql = "SELECT * FROM doctor ORDER BY id DESC";

                $result = mysqli_query($con, $sql);

                while ($row = mysqli_fetch_array($result)){
                  if ($update == true && $row['id'] == $doctor){
                    print "<option value='".$row['id']."' selected>".$row['first_name']." ".$row['last_name']."</option>";
                  }else{
                    print "<option value='".$row['id']."'>".$row['first_name']." ".$row['last_name']."</option>";
                  }
                }
              ?>
            </select>

            
            
            <br>

            <label>Room</label><br>
            <select name="room">
               <?php
                $con = mysqli_connect("localhost", "root", "", "hospital_database");

                $sql = "SELECT * FROM room WHERE availability = 'yes' ORDER BY number";

                $result = mysqli_query($con, $sql);

                if ($update == true){
                  print "<option value='$room' selected>".$room."</option>";
                }

                while ($row = mysqli_fetch_array($result)){
                  print "<option value='".$row['number']."'>".$row['number']."</option>";
                }
              ?>
            </select>

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
              <th>Gender</th>
              <th>Diagnosis</th>
              <th>Doctor</th>
              <th>Room</th>
              <th>Action</th>
            </tr>
            <?php
              $con = mysqli_connect("localhost", "root", "", "hospital_database");
              $sql = "SELECT * FROM patient ORDER BY id DESC";

              $result = mysqli_query($con, $sql);
              $patients = array();
              while($row = $result->fetch_assoc()){
                $patients[] = $row;
              }
              

              for ($i = 0; $i < count($patients); $i++){
                // get patient's doctor
                $sql = "SELECT first_name, last_name 
                        FROM doctor, doctor_patient 
                        WHERE doctor.id = doctor_patient.doctor_id 
                        AND doctor_patient.patient_id =".$patients[$i]['id'];

                $result = mysqli_query($con, $sql);
                $row = mysqli_fetch_row($result);
                $doctor = $row[0]." ".$row[1];

                // get patient's room
                $sql = "SELECT number
                        FROM room, room_patient 
                        WHERE room.number = room_patient.room_number 
                        AND room_patient.patient_id =".$patients[$i]['id'];

                $result = mysqli_query($con, $sql);
                $row = mysqli_fetch_row($result);
                $room = $row[0];

                print "<tr>";
                print "<td>".$patients[$i]["id"]."</td>";
                print "<td>".$patients[$i]["first_name"]."</td>";
                print "<td>".$patients[$i]["last_name"]."</td>";
                print "<td>".$patients[$i]["gender"]."</td>";
                print "<td>".$patients[$i]["diagnosis"]."</td>";
                print "<td>".$doctor."</td>";
                print "<td>".$room."</td>";
                print "<td><a class='edit_btn' href='patients.php?edit=".$patients[$i]["id"]."'>Edit</a>";
                print "<a class='delete_btn' href='process_patient.php?delete=".$patients[$i]["id"]."'>Delete</a>";
                print "</td>";

                print "</tr>";
              }
              mysqli_close($con);
            ?>
          </table>
        </div>
      </div>
    </div>
  </body>
</html>