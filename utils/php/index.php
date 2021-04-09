<?php

    require_once('config.php');

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Open Plant Names: Data Browser</title>
    <meta name="description" content="Simple page to browse the MySQL database.">

    <style>
    body{
        font-family: Sans-Serif;
        padding: 1em;
    }
    th{
        text-align: right;
    }
    </style>
</head>

<body onload="document.getElementById('search').focus();">
  <h1>Open Plant Names: Data Browser</h1>
  <form method="GET" >
    <p>Enter the first few letters of the name. When there are fewer than 2,000 results a list will be displayed.</p>
    <input 
        type="text"
        id="search"
        name="search" 
        value="<?php echo @$_GET['search']  ?>"
        onfocus="this.selectionStart = this.selectionEnd = this.value.length;"    
    /><input type="submit" />
  </form>

<?php

    $search = @$_GET['search'];

    // render the search results
    if($_GET['search'] && !isset($_GET['id'])){
        
        // we have search 
        

        // count the rows
        $result = $mysqli->query("SELECT count(*) as n FROM `names` where concat_ws(' ', `genus`, `species`, `name`) like '$search%';");
        $row = $result->fetch_assoc();
        $result->close();
        if($row['n'] > 2000 ){
            $n = number_format($row['n']);
            echo "<p>{$n}s records returned. Add more letters to make the search more specific.</p>";
        }elseif($row['n'] < 1){
            echo "Nothing found";
        }else{
            echo "<ul>";
            $sql = "SELECT concat_ws(' ', `genus`, `species`, `name`) as name_string, `names`.* FROM `names` where concat_ws(' ', `genus`, `species`, `name`) like '$search%';";
            $mysqli->real_query($sql); // don't fetch complete result set
            $result = $mysqli->use_result();
            while($row = $result->fetch_assoc()){
                echo "<li>";
                echo "<a href=\"?search=$search&id={$row['id']}\">{$row['name_string']}</a> {$row['authors']} {$row['citation_micro']}" ;
                echo "</li>";
            }
            echo "</ul>";
        }

    }

    if(isset($_GET['id']) && preg_match('/[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}/', $_GET['id'])){
        $id =  $_GET['id'];
        $result = $mysqli->query("SELECT * FROM `names` WHERE id = '$id'");
        $row = $result->fetch_assoc();

        if($row['rank'] == 'species'){
            $name = "<i>{$row['genus']} {$row['name']}</i>";
        }elseif(in_array( $row['rank'], array('subspecies', 'variety', 'form'))){
            $name = "<i>{$row['genus']} {$row['species']}</i> {$row['rank']} <i>{$row['name']}</i>";
        }elseif(in_array($row['rank'], array('subgenus', 'section'))){
            $name = "<i>{$row['genus']} {$row['name']}</i>";
        }else{
            $name = $row['name'];
        }

        echo "<h2>$name {$row['authors']}</h2>";
        echo "<h3>Data</h3>";
        echo "<table>";
        render_table_row('ID', $row['id']);
        render_table_row('Rank', $row['rank']);
        render_table_row('Name', $row['name']);
        render_table_row('Genus', $row['genus']);
        render_table_row('Species', $row['species']);
        render_table_row('Authors', $row['authors']);
        render_table_row('Year', $row['year']);
        render_table_row('Micro Citation', $row['citation_micro']);
        render_table_row('Full Citation', $row['citation_full']);

        if($row['basionym_id']){
            $basionym = "<a href=\"?search=$search&id={$row['basionym_id']}\">{$row['basionym_id']}</a>";
        }else{
            $basionym = "";
        }
        render_table_row('Basionym', $basionym);
        
        render_table_row('IPNI ID', "<a target=\"opn-ipni\" href=\"https://www.ipni.org/n/{$row['ipni_id']}\">{$row['ipni_id']}</a>" );

        render_table_row('WFO ID', "<a target=\"opn-wfo\" href=\"http://www.worldfloraonline.org/taxon/{$row['wfo_id']}\">{$row['wfo_id']}</a>" );

        render_table_row('GBIF ID', "<a target=\"opn-gbif\" href=\"https://www.gbif.org/es/species/{$row['gbif_id']}\">{$row['gbif_id']}</a>" );

        echo "</table>";
    }
?>


</body>
</html>

<?php
    function render_table_row($title, $value){
        echo "<tr>";
        echo "<th>$title:</th>";
        echo "<td>$value</td>";
        echo "</tr>";
    }
?>