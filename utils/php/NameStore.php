<?php

/**
 * Controls integrity of the names table
 * 
 */
class NameStore{

    private string $table;

    public function __construct($table_name = 'names'){
        $this->table = $table_name;
    }

    /*
        This abstracts the saving of data to the database.
        Simply create an assoc array of field values and call put
        It will decide whether to update or insert a record based on
        the supplied ids
        Include in the array overwrite:true to overwrite existing values.
    */
    public function put($new_data){

        global $mysqli;

        // have we been provided with an id?
        $current_data = null;
        
        // try and get the current row
        if(isset($new_data['id']) && $new_data['id']){
            $result = $mysqli->query("SELECT * FROM `{$this->table}` WHERE `id` = '{$new_data['id']}'");
            if($result->num_rows == 1){
                $current_data = $result->fetch_assoc();
            }
            $result->close();
        }


        // not managed it from the id so try and get it
        // from the wfo_id
        if(!$current_data && isset($new_data['wfo_id']) && $new_data['wfo_id']){
            $result = $mysqli->query("SELECT * FROM `{$this->table}` WHERE `id` = '{$new_data['wfo_id']}'");
            if($result->num_rows == 1){
                $current_data = $result->fetch_assoc();
            }elseif($result->num_rows > 1){
                throw new Exception("Multiple records for wfo_id {$new_data['wfo_id']}");
                $result->close();
                return;
            }
            $result->close();
        }
        
        // still not got it so try and load it from the gbif_id
        if(!$current_data && isset($new_data['gbif_id']) && $new_data['gbif_id']){
            $result = $mysqli->query("SELECT * FROM `{$this->table}` WHERE `id` = '{$new_data['gbif_id']}'");
            if($result->num_rows == 1){
                $current_data = $result->fetch_assoc();
            }elseif($result->num_rows > 1){
                throw new Exception("Multiple records for gbif_id {$new_data['gbif_id']}");
                $result->close();
                return;
            }
            $result->close();
        }

        // if we still haven't got current_data then we are 
        // creating a new record
        if(!$current_data){
            $current_data = $this->getBlankRecord();
        }

        $combined_data = $this->merge($current_data, $new_data);

        if($current_data['id']){
            return $this->update($combined_data);
        }else{
            return $this->insert($combined_data);
        }

    }

    private function update($data){

        global $mysqli;

        $stmt = $mysqli->prepare(
            "UPDATE `{$this->table}`
                SET
                    `rank` = ?,
                    `name` = ?,
                    `genus` = ?,
                    `species` = ?,
                    `authors` = ?,
                    `year` = ?,
                    `citation_micro` = ?,
                    `citation_full` = ?,
                    `citation_id` = ?,
                    `publication_id` = ?,
                    `basionym_id` = ?,
                    `ipni_id` = ?,
                    `wfo_id` = ?,
                    `gbif_id` = ?,
                    `note` = ?
                WHERE 
                    `id` = ?;        
        ");
        $stmt->bind_param(
            'sssssissssssssss',
            $data['rank'],
            $data['name'],
            $data['genus'],
            $data['species'],
            $data['authors'],
            $data['year'],
            $data['citation_micro'],
            $data['citation_full'],
            $data['citation_id'],
            $data['publication_id'],
            $data['basionym_id'],
            $data['ipni_id'],
            $data['wfo_id'],
            $data['gbif_id'],
            $data['note'],
            $data['id']
        );

        if($stmt->execute()){
            $stmt->close();
            return true;
        }else{
            throw new Exception("Counldn't update {$data['id']} " . $mysqli->error);
            $stmt->close();
            return false;
        }

    }

    private function insert($data){

        global $mysqli;
        $stmt = $mysqli->prepare(
            "INSERT INTO `{$this->table}`
            (
                `id`,
                `rank`,
                `name`,
                `genus`,
                `species`,
                `authors`,
                `year`,
                `citation_micro`,
                `citation_full`,
                `citation_id`,
                `publication_id`,
                `basionym_id`,
                `ipni_id`,
                `wfo_id`,
                `gbif_id`,
                `note`
            )VALUES(
                uuid(), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? 
            );"
        );
        $stmt->bind_param(
            'sssssisssssssss',
            $data['rank'],
            $data['name'],
            $data['genus'],
            $data['species'],
            $data['authors'],
            $data['year'],
            $data['citation_micro'],
            $data['citation_full'],
            $data['citation_id'],
            $data['publication_id'],
            $data['basionym_id'],
            $data['ipni_id'],
            $data['wfo_id'],
            $data['gbif_id'],
            $data['note']
        );

        if($stmt->execute()){
            $stmt->close();
            return true;
        }else{
            throw new Exception("Counldn't insert new record " . $mysqli->error);
            $stmt->close();
            return false;
        }

    }

    private function merge($current_data, $new_data){

        $overwrite = isset($new_data['overwrite']) && $new_data['overwrite'] ? true : false;

        $merged = array();
        
        foreach($current_data as $key => $current_val){

            if($overwrite){
                if(isset($new_data[$key])){
                    // if a new value is set and we are in overwrite then we replace the value
                    $merged[$key] = $new_data[$key];
                }else{
                    // no value set so use the current one
                    $merged[$key] = $current_data[$key];
                }
            }else{

                // we are not in overwrite mode so we only add new 
                // values if they empty in the current value
                if(!$current_data[$key] && isset($new_data[$key])){
                    $merged[$key] = $new_data[$key];
                }else{
                    $merged[$key] = $current_data[$key];
                }
            
            }
        
        }

        return $merged;
    }

    private function getBlankRecord(){
        return array(
            'id' => null,
            'rank' => null,
            'name' => null,
            'genus' => null,
            'species' => null,
            'authors' => null,
            'year' => null,
            'citation_micro' => null,
            'citation_full' => null,
            'citation_id' => null,
            'publication_id' => null,
            'basionym_id' => null,
            'ipni_id' => null,
            'wfo_id' => null,
            'gbif_id' => null,
            'note' => null
        );
    }






}