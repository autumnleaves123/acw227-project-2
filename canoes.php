<!DOCTYPE html>

<?php
  $current_page_id = "canoes";

  // open connection to database
  $db = new PDO('sqlite:data.sqlite');
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  function exec_sql_query($db, $sql, $params) {
    $query = $db->prepare($sql);
    if ($query and $query->execute($params)) {
      return $query;
    }
    return NULL;
  }

  // query all data from the database
  $sql = "SELECT * FROM cornellconcretecanoe";
  $params = array();

  $records = exec_sql_query($db,$sql,$params);
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
    <div id="canoe-page">
      <h1>Past Competition Canoes</h1>

      <div>
        <?php
          // TODO cycle through fileds and output dataneed to cycle through all fields
          foreach ($records as $entry) {
            echo "<label>Canoe Name:</label><span> " . htmlspecialchars($entry["name"]) . "</span><br>";
            echo "<label>Year:</label><span> " . htmlspecialchars($entry["year"]) . "</span><br>";
            echo "<label>Team Lead:</label><span> " . htmlspecialchars($entry["team_lead"]) . "</span><br>";
            echo "<label>Compressive Strength:</label><span> " . htmlspecialchars($entry["compressive_strength"]) . "</span><br>";
            echo "<label>Tensile Strength:</label><span> " . htmlspecialchars($entry["tensile_strength"]) . "</span><br>";
            echo "<label>Weight:</label><span> " . htmlspecialchars($entry["weight"]) . "</span><br>";
            echo "<label>Place:</label><span> " . htmlspecialchars($entry["place"]) . "</span><br><br><br>";
          }
        ?>
      </div>

    </div>
      <?php include("includes/footer.php"); ?>
  </body>
</html>
