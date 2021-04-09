<?php
/**
 * 
 * This will create/update the data files from the mysql db
 * 
 */

require_once('config.php');
require_once('NameStore.php');

echo "\nRunning import of CSV files.\n";

$ops = getopt('t:');

// have we got a version
if(isset($ops['t']) && $ops['t']){
    $table = $ops['t']; 
}else{
    $table = 'names';
}

echo "Table name set to: '$table'\n";

// check the table exists
$result = $mysqli->query("show tables like '$table';");

if($result->num_rows == 1){
    echo "Table exists.\n";
}else{
    echo "Table does not exist. Creating.\n";
    $sql = file_get_contents('../mysql/names_create.sql');
    $sql = str_replace('__TABLE_NAME__', $table, $sql);
    $result = $mysqli->query($sql);
}

// get a list of the csv files
$files = array_merge(glob("../../data/names/*/*.csv"), glob("../../data/names/*.csv"));

// get a connection to the db
$store = new NameStore($table);

// work through them and import
foreach ($files as $filename) {

     echo "$filename \n";

    // open file
    $in = fopen($filename, 'r');
    
    // drop the first line
    $header = fgetcsv($in, 1000, ',');

    // read the next line
    while(($line = fgetcsv($in, 2000)) !== false){
        $input = array_combine($header, $line);
        
        try{
            $store->put($input);
        }catch(Exception $e){
            echo $e->getMessage();
        }
        

    }
    
}


