<!DOCTYPE html>

<!---Need to do that thing you lost points for...
ADD messages
Need to make sure to sanitize data with htmlspechialchars-->

<?php
$current_page_id = "add";

// An array to deliver messages to the user.
$messages = array();
$invalid_addition = FALSE;

// open connection to database
$db = new PDO('sqlite:data.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

function exec_sql_query($db, $sql, $params) {
  try {
    $query = $db->prepare($sql);
    if ($query and $query->execute($params)) {
      return $query;
    }
  } catch (PDOException $e) {
    // catches if user inputs a wrong query that would break the database
    $invalid_addition = TRUE;
    return NULL;
  }
}

// --- INSERT FORM --- //

// filter and accept user input
if (isset($_POST["submit_insert"])) {
  $year = filter_input(INPUT_POST, 'year', FILTER_VALIDATE_INT);
  $name = filter_input(INPUT_POST, 'canoe_name', FILTER_SANITIZE_STRING);
  $team_lead = filter_input(INPUT_POST, 'team_lead', FILTER_SANITIZE_STRING);
  $tensile_strength = filter_input(INPUT_POST, 'tensile_strength', FILTER_VALIDATE_INT);
  $compressive_strength = filter_input(INPUT_POST, 'compressive_strength', FILTER_VALIDATE_INT);
  $weight = filter_input(INPUT_POST, 'weight', FILTER_VALIDATE_INT);
  $place = filter_input(INPUT_POST, 'placing', FILTER_SANITIZE_STRING);

  // cases for an invalid review (if numbers are negative)
  if ( $tensile_strength < 0 or $compressive_strength < 0 or $weight < 0) {
    $invalid_addition = TRUE;
  }

  if ($invalid_addition) {
    array_push($messages, "Failed to add review. Invalid number.");
  } else {

    // SQL to insert review
    $sql = 'INSERT INTO cornellconcretecanoe (name, year, team_lead, tensile_strength, compressive_strength, weight, place) VALUES (:name, :year, :team_lead, :tensile_strength, :compressive_strength, :weight, :place)';
    $params = array(":name"=>$name,
    ":year"=>$year,
    ":team_lead"=>$team_lead,
    ":tensile_strength"=>$tensile_strength,
    ":compressive_strength"=>$compressive_strength,
    ":weight"=>$weight,
    ":place"=>$place);

    $result = exec_sql_query($db, $sql, $params);

    if ($result) {
      array_push($messages, "Your review has been recorded. Thank you!");
    } else {
      array_push($messages, "Failed to add review.");
    }
  }
}
?>

<html>
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" type="text/css" href="styles/all.css" media="all" />
  <title>Add Boat Data</title>
</head>

<body>
  <?php include("includes/header.php"); ?>
  <div id="form-background" class="main" >
    <div class="container">
      <h1>Add a canoe!</h1>
      <?php
      // Write out any messages to the user.
      foreach ($messages as $message) {
        echo "<p>" . htmlspecialchars($message) . "</p>\n";
      }
      ?>

      <form method="post" action="add.php">
        <ul class="no-margin">

          <!-- YEAR -->
          <li class="add-form">
            <h2>Year:</h2>
            <select class="select-form" name="year" required>
              <option value="" selected disabled>Choose Year</option>
              <?php
              $current_year = date("Y");
              $valid_years = [];

              // add valid years to an array starting from founding year to current
              while ($current_year >= 2000) {
                array_push($valid_years, $current_year);
                $current_year -= 1;
              }

              // add valid years to drop down menu
              foreach ($valid_years as $valid_year) {
                echo "<option value=\"" . $valid_year . "\">" . $valid_year . "</option>";
              }
              ?>
            </select>
          </li>

          <!-- CANOE NAME -->
          <li class="add-form">
            <h2>Canoe Name:</h2>
            <input class="form-box" type="text" name="canoe_name" required/>
          </li>

          <!-- TEAM LEAD -->
          <li class="add-form">
            <h2>Team Lead:</h2>
            <li class="app-note">Please put FIRST LAST</li>
            <input class="form-box" type="text" name="team_lead" required>
          </li>

          <!-- TENSILE STRENGTH -->
          <li class="add-form">
            <h2>Tensile Strength:</h2>
            <input class="form-box" type="text" name="tensile_strength" required/>
          </li>

          <!-- COMPRESSIVE STRENGTH -->
          <li class="add-form">
            <h2>Compressive Strength:</h2>
            <input class="form-box" type="text" name="compressive_strength" required/>
          </li>

          <!-- WEIGHT -->
          <li class="add-form">
            <h2>Weight:</h2>
            <li class="app-note">Weight in pounds.</li>
            <input type="number" name="weight" required/>
          </li>

          <!-- PLACE -->
          <li class="add-form">
            <h2>Placing:</h2>
            <li class="app-note">Placing at NY Upstate ASCE Regional Competition.</li>
            <select class="select-form" name="placing" required>
              <option value="" selected disabled>Choose Place</option>
              <?php
              $places = ["1st", "2nd", "3rd", "4th", "5th", "6th", "7th", "8th", "9th", "10th", "did not place"];

              // add valid places to drop down menu
              foreach ($places as $place) {
                echo "<option value=\"" . $place . "\">" . $place . "</option>";
              }
              ?>
            </select>
          </li>
        </ul>

        <!-- SUBMIT APPLICATION BUTTON -->
        <div class="center">
          <button type="submit" name="submit_insert">Submit Entry</button>
        </div>

      </form>
    </div>
  </div>

  <?php include("includes/footer.php"); ?>
</body>
</html>
