<?php
/**
 * 
 * This will create/update the data files from the mysql db
 * 
 */

require_once('config.php');

// higher taxa
$field_list = "`" . implode("`,`", $fields) . "`";
$rank_list = "'" . implode("','", $ranks_higher) . "'";
$sql = "SELECT $field_list from `names` WHERE `rank` IN ($rank_list) ORDER BY `name`";
$result = $mysqli->query($sql);

echo "Creating higher taxa file.\n";
echo "Total rows: " . $result->num_rows . "\n";

$out = fopen('../../data/names/higher_names.csv', 'w');

fputcsv($out, $fields); // header row

while($row = $result->fetch_assoc()){
    fputcsv($out, $row);
}

fclose($out);


// genera and below
$rank_list = "'" . implode("','", $ranks_lower) . "'";
$sql = "SELECT $field_list from `names` WHERE `rank` IN ($rank_list) ORDER BY concat_ws(' ', `genus`, `species`, `name`)";
$mysqli->real_query($sql); // don't fetch complete result set
$result = $mysqli->use_result();

echo "Creating lower taxa file.\n";
echo "Total rows: " . $result->num_rows . "\n";

$out = false;
while($row = $result->fetch_assoc()){

    // if has changed then switch the file we are writing to
    if(!$row['genus'] && $row['rank'] == 'genus'){
        
        if($out) fclose($out); // close any open file

        // work out the subfolder
        $dir = '../../data/names/' . strtoupper(substr($row['name'], 0, 1));
        if(!file_exists($dir)) mkdir($dir);

        // open a file in it
        $out = fopen( $dir . '/' . $row['name'] . '.csv', 'w');
        
        fputcsv($out, $fields); // write a header row
        echo $row['name'] . "\n";
        
    }

    // write this row
    fputcsv($out, $row);

}

fclose($out);
echo "All done!\n";

