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
    table{
        width: 80%;
    }
    th{
        text-align: right;
        vertical-align: top;
    }
    .scroll-list{
        height: 400px;
        overflow: auto;
        border: solid gray 1px;
    }

    .scroll-list div{
        padding-left: 1em;
        padding-top: 0.3em;
    }

    .author-box{
        border: solid gray 1px;
        height: 122px;
        width: 200px;
        float: left;
        padding: 0.5em;
        margin-right: 0.5em;
        color: white;
        background-color: gray;
        border-radius: 8px;
        text-shadow: 2px 2px 8px black;
    }

    .author-box p{
        margin-top: 0.3em;
        margin-bottom: 0.3em;
    }

    .author-box a{
        text-decoration: none;
    }

    .small{
        font-size: 80%;
        color: white;
    }

    </style>

    <script>
    function toggler(myDivId) {
        console.log(myDivId);
         var x = document.getElementById(myDivId);
         console.log(x.style.display);
         if (x.style.display === "none") {
            x.style.display = "block";
            loadAuthors(myDivId);
         } else {
            x.style.display = "none";
         }
    }

    function loadAuthors(divId){

        let className = divId + "_author";
        let authorDivs = document.getElementsByClassName(className);
        for (let i = 0; i < authorDivs.length; i++) {
            let div = authorDivs.item(i);
            let authorId = div.dataset.author;
            let dataUri = 'https://www.wikidata.org/wiki/Special:EntityData/' + authorId + '.json';
            fetch(dataUri)
                .then(response => response.json())
                .then(data => {

                    // label
                    let label = data.entities[authorId].labels.en.value;
                    let node = document.createElement("p");
                    let txt = document.createTextNode(label);
                    node.appendChild(txt);
                    div.appendChild(node);

                    // description
                    if(data.entities[authorId].descriptions && data.entities[authorId].descriptions.en){
                        let description = data.entities[authorId].descriptions.en.value;
                        div.appendChild(document.createElement("p").appendChild(document.createTextNode(description)));
                    }

                    // is there a picture?
                    if(data.entities[authorId].claims && data.entities[authorId].claims.P18){
                        let file = data.entities[authorId].claims.P18[0].mainsnak.datavalue.value;
                        let uri = 'http://commons.wikimedia.org/wiki/Special:FilePath/' + file;
                        //let img = document.createElement('img');
                        //img.setAttribute('src', uri);
                        div.style.backgroundImage = "url('"+ uri +"')";
                        div.style.backgroundSize = 'contain';
                        div.style.backgroundRepeat = 'no-repeat';
                        div.style.backgroundPosition = 'right';

                        console.log(uri);
                    }
                    
                    // Thomas%20Nuttall.jpg

                    

                    console.log(data.entities[authorId].claims.P18[0]);

                }).finally(
                    () => {div.style.display = "block";}
                );
            console.log(dataUri);
        }

       // 
    }
    </script>
</head>

<body>
  
<?php
echo "|";
foreach(range('A','Z') as $letter) {
    echo " <a href=\"index.php?letter=$letter\">$letter</a> |";
}
?>
  <h1>Open Plant Names: Data Browser</h1>
 
<?php
    if(@$_GET['letter']){

        // load the higher taxa names into memory
        $higher_names = array();
        $in = fopen('../../data/names/higher_names.csv', 'r');
        $head = fgetcsv($in, 2000);
        while($line = fgetcsv($in, 2000)){
            $higher_names[] = $line;
        }
        fclose($in);

        $letter = $_GET['letter'];

        $name_index = array_search('name', $fields);

        echo "<h2>Higher Names</h2>";
        echo "<div class=\"scroll-list\">";
        foreach($higher_names as $row){
            $name = $row[$name_index];
            if(strpos( $name , $letter ) === 0){
                echo_row($row);
            }
        }
        echo "</div>";

        // load the genera starting with that name

        echo "<h2>Genera</h2>";
        $files = glob("../../data/names/$letter/*.csv");

        echo "<div class=\"scroll-list\">";
        foreach($files as $file){
            $info = pathinfo($file);
            $genus = $info['filename'];
            echo "<div><a href=\"index.php?genus=$file\">$genus</a></div>";
        }
        echo "</div>";

    }elseif(@$_GET['genus']){

        $file = $_GET['genus'];

        $info = pathinfo($file);
        $genus_name = $info['filename'];

        $genus_rows = array();
        $in = fopen($file, 'r');
        $head = fgetcsv($in, 2000);
        while($line = fgetcsv($in, 2000)){
            $genus_rows[] = $line;
        }
        fclose($in);

        echo "<h2><i>$genus_name</i></h2>";
        echo "<div>";
        foreach($genus_rows as $row){
            echo_row($row);

        }
        echo "</div>";

    }else{
        echo "<p>This is a simple way of exploring the files without having to install a database.</p>";
        echo "<p>To start pick a letter from the top of the page.</p>";
    }

    
?>


</body>
</html>

<?php

function echo_header(){

    global $fields;

    echo "<thead>";
    echo "<tr>";
    echo "<th>&nbsp;</th>";
    foreach($fields as $field){
        echo "<th>$field</th>";
    }
    echo "</tr>";
    echo "</thead>";

}

function echo_row($row){

    global $fields;

    // load them into an assoc array to make things simpler
    $values = array();
    foreach ($fields as $field) {
        $values[$field] = $row[array_search($field, $fields)];
    }

    $infra_ranks = array('subspecies', 'variety', 'form');
    switch ($values['rank']) {
        case 'subspecies':
            $name_display = "<i>{$values['genus']} {$values['species']}</i> subsp. <i>{$values['name']}</i> {$values['authors']}";
            break;
        case 'variety':
            $name_display = "<i>{$values['genus']} {$values['species']}</i> var. <i>{$values['name']}</i> {$values['authors']}";
            break;
        case 'form':
            $name_display = "<i>{$values['genus']} {$values['species']}</i> f. <i>{$values['name']}</i> {$values['authors']}";
            break;
        case 'species':
            $name_display = "<i>{$values['genus']} {$values['name']}</i> {$values['authors']}";
            break;
        default:
            $name_display = "<i>{$values['name']}</i> {$values['authors']}";
            break;
    }

    echo "<div>";
    $div_id = 'DIV' . str_replace('-', '', $values['id']);

    echo "<a href=\"#\" onclick=\"event.preventDefault(); toggler('$div_id')\">$name_display</a>";

    echo "<div id=\"$div_id\" style=\"display: none\">";

        echo "<table>";
        echo "<tr><th>ID</th><td>{$values['id']}</td></tr>";
        echo "<tr><th>Rank</th><td>{$values['rank']}</td></tr>";
        echo "<tr><th>Name</th><td>{$values['name']}</td></tr>";
        echo "<tr><th>Genus</th><td>{$values['genus']}</td></tr>";
        echo "<tr><th>Species</th><td>{$values['species']}</td></tr>";
        echo "<tr><th>authors</th><td>{$values['authors']}</td></tr>";
        
        // authors with ids
        echo "<tr><th>Author IDs</th><td>";
        $author_ids = explode(',', $values['author_ids']);

        foreach ($author_ids as $ai) {
            if(!$ai) continue;
            $div_class = $div_id . '_author';
            echo "<a href=\"https://www.wikidata.org/wiki/$ai\" target=\"wikidata\"><div data-author=\"$ai\" class=\"$div_class author-box\" style=\"display: none\"><span class=\"small\">Wikidata: $ai</span></div></a>";
        }       
        
        echo "</td></tr>";

        echo "<tr><th>Year</th><td>{$values['year']}</td></tr>";
        echo "<tr><th>Status</th><td>{$values['status']}</td></tr>";
        echo "<tr><th>Citation (micro)</th><td>{$values['citation_micro']}</td></tr>";
        echo "<tr><th>Citation (full)</th><td>{$values['citation_full']}</td></tr>";
        echo "<tr><th>Citation ID</th><td>{$values['citation_id']}</td></tr>";
        echo "<tr><th>Publication ID</th><td>{$values['publication_id']}</td></tr>";
        echo "<tr><th>Basionym ID</th><td>{$values['basionym_id']}</td></tr>";
        echo "<tr><th>Type ID</th><td>{$values['type_id']}</td></tr>";
        echo "<tr><th>IPNI ID</th><td>{$values['ipni_id']}</td></tr>";
        echo "<tr><th>WFO ID</th><td>{$values['wfo_id']}</td></tr>";
        echo "<tr><th>GBIF ID</th><td>{$values['gbif_id']}</td></tr>";
        echo "<tr><th>Indexfungorum ID</th><td>{$values['indexfungorum_id']}</td></tr>";
        echo "<tr><th>Note</th><td>{$values['note']}</td></tr>";
        echo "</table>";

    echo "</div>";

    echo "</div>";
}

?>