<header>

  <nav>
    <ul id="nav">
      <h1>Cornell Concrete Canoe</h1>
      <?php

        $pages = ["index"=>"Home", "canoes"=>"Competition Canoes", "add"=>"Add Data",
          "search"=>"Search Past Canoes"];

        foreach ($pages as $page=>$page_name){

        //create li tag that contains a hyperlink to the file named
        //condition: if current page is same as key, then add id attribute

        if ($current_page_id == $page) {
          echo "<li><a href='" . $page . ".php' id='current-page'" . ">" . $page_name . "</a></li>";
        } else {
          echo "<li><a href='" . $page . ".php'>" . $page_name . "</a></li>";
        }

      }
      ?>
    </ul>
  </nav>
</header>
