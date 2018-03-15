<!DOCTYPE html>

<!---Need to do that thing you lost points for...
ADD messages
Need to make sure to sanitize data with htmlspechialchars-->

<?php
$current_page_id = "search";

// An array to deliver messages to the user.
$messages = array();

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

// --- SEARCH FORM --- //

// An array to deliver messages to the user.
$messages = array();

// Search Form

const SEARCH_FIELDS = [
  "name" => "By Canoe Name",
  "year" => "By Year",
  "weight" => "By Weight",
  "place" => "By Ranking"
];

if (isset($_GET['search']) and isset($_GET['category'])) {
  $do_search = TRUE;

  // TODO: filter input for 'search' and 'category'
  $category = filter_input(INPUT_GET, 'category', FILTER_SANITIZE_STRING);
  $search = trim(filter_input(INPUT_GET, 'search', FILTER_SANITIZE_STRING));

  // check that category is a valid field in our table
  if (in_array($category, array_keys(SEARCH_FIELDS))) {
    $search_field = $category;
  } else {
    $search_field = null;
    array_push($messages, "Invalid category for search.");
    $do_search = FALSE;
  }

} else {
  // No search provided, so set the product to query to NULL
  $do_search = FALSE;
  $category = NULL;
  $search = NULL;
}
?>

<html>
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" type="text/css" href="styles/all.css" media="all" />
    <title>Search Boat Data</title>
  </head>

  <body id="search-page">
    <?php include("includes/header.php"); ?>
    <div class="search-main" >
      <div class="container">
        <h1 class="white-text">Look up past canoes!</h1>
        <p class="white-text">Search by stats.</p>

        <?php
        // Write out any messages to the user.
        foreach ($messages as $message) {
          echo "<p><strong>" . htmlspecialchars($message) . "</strong></p>\n";
        }
        ?>

        <form action="search.php" method="get">
          <select id="search-dropdown" name="category">
            <option value="" selected disabled>Search By</option>
            <?php
            foreach(SEARCH_FIELDS as $field_name => $label){
              ?>
              <option value="<?php echo $field_name;?>"><?php echo $label;?></option>
              <?php
            }
            ?>
          </select>
          <input id="search-bar" type="text" name="search"/>
          <button id="search-button" type="submit">Search</button>
        </form>

        <!--kind of like div is here-->
        <?php
        if ($do_search) {

          echo "<h2 id='search-heading'>Search Results</h2><div class='white-text'";

          // Be careful to filter $search_field above. If you're not careful, you can seriously break your database.
          $sql = "SELECT * FROM cornellconcretecanoe WHERE " . $search_field . " LIKE '%' || :search || '%';" ;
          $params = array(":search"=>$search);

          $records = exec_sql_query($db, $sql, $params)->fetchAll();
          if (isset($records) and !empty($records)) {
            // TODO cycle through fileds and output dataneed to cycle through all fields
            foreach ($records as $entry) {
              echo "<label>Canoe Name:</label><span> " . $entry["name"] . "</span><br>";
              echo "<label>Year:</label><span> " . $entry["year"] . "</span><br>";
              echo "<label>Team Lead:</label><span> " . $entry["team_lead"] . "</span><br>";
              echo "<label>Compressive Strength:</label><span> " . $entry["compressive_strength"] . "</span><br>";
              echo "<label>Tensile Strength:</label><span> " . $entry["tensile_strength"] . "</span><br>";
              echo "<label>Weight:</label><span> " . $entry["weight"] . "</span><br>";
              echo "<label>Place:</label><span> " . $entry["place"] . "</span><br><br><br>";
            }
          } else {
            echo "<p>No reviews.</p>";
          }
        }
        ?>
      </div>
    </div>
  </div>
  <?php include("includes/footer.php"); ?>
  </body>
</html>
