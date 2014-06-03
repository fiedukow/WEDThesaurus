<?php
function getWordsAndTheirSynonyms($words) {
  $mysql_conn = mysql_connect("localhost", "root", "toor");
  mysql_select_db("WEDT");
  $end = count($words);
  $next = count($words);
  for($i = 0; $i < $end; ++$i) {
    $q = "SELECT l.`literal` FROM `literals` l0 ".
         "JOIN `words` w ON w.`literal_id` = l0.`id` ".
         "JOIN `synonyms` s ON w.`id` = s.`word_id` ".
         "JOIN `literals` l ON l.`id`=s.`literal_id` ".
         "WHERE l0.`literal`=\"".$words[$i]."\" ".
         "ORDER BY s.`quality` DESC";
    $results = mysql_query($q);
    while($row = mysql_fetch_array($results)) {
      $words[$last_to_syn++] = $row[0];
    }
  }
  mysql_close($mysql_conn);
  return $words;
}
?>
