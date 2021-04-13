<?php

require_once('../../../open_secrets.php'); // outside the github root

// create and initialise the database connection
$mysqli = new mysqli($db_host, $db_user, $db_password, $db_database);  

// connect to the database
if ($mysqli->connect_error) {
  echo $mysqli->connect_error;
}

if (!$mysqli->set_charset("utf8")) {
  echo printf("Error loading character set utf8: %s\n", $mysqli->error);
}

// fields
$fields = array(
    'id',
    'rank',
    'name',
    'genus',
    'species',
    'authors',
    'author_ids',
    'year',
    'status',
    'citation_micro',
    'citation_full',
    'citation_id',
    'publication_id',
    'basionym_id',
    'type_id',
    'ipni_id',
    'wfo_id',
    'gbif_id',
    'indexfungorum_id',
    'note'
);

// ranks
$ranks_higher = array('phylum', 'order', 'family', 'section', 'subgenus'); // single words
$ranks_lower = array('genus','species', 'subspecies', 'variety', 'form'); // binomials and trinomials and genus
$ranks_all = array_merge($ranks_higher, $ranks_lower);
