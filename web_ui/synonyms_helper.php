<?php
function wordWithQualComparator($w1, $w2) {
  if ($w1["qual"] == $w2["qual"])
    return 0;
  return (($w1["qual"] > $w2["qual"]) ? -1 : 1);
}

function getWordsAndTheirSynonyms($words) {
  $mysql_conn = mysql_connect("localhost", "root", "toor");
  mysql_select_db("WEDT");
  $end = count($words);
  $next = 0;
  for($i = 0; $i < $end; ++$i) {
    $words_ret[$next  ]["syn"      ] = $words[$i];
    $words_rec[$next  ]["syn_id"   ] = -1;
    $words_ret[$next  ]["qual"     ] = 1.0;
    $words_ret[$next  ]["reason"   ] = $words[$i];
    $words_ret[$next++]["reason_id"] = -1;

    $q = "SELECT l.`literal`, l.`id`, s.`quality`, w.`id` FROM `literals` l0 ".
         "JOIN `words` w ON w.`literal_id` = l0.`id` ".
         "JOIN `synonyms` s ON w.`id` = s.`word_id` ".
         "JOIN `literals` l ON l.`id`=s.`literal_id` ".
         "WHERE l0.`literal`=\"".$words[$i]."\" ".
         "ORDER BY s.`quality` DESC";
    $results = mysql_query($q);
    while($row = mysql_fetch_array($results)) {
      $words_ret[$next  ]["syn"      ] = $row[0];
      $words_ret[$next  ]["syn_id"   ] = $row[1];
      $words_ret[$next  ]["qual"     ] = $row[2];
      $words_ret[$next  ]["reason"   ] = $words[$i];
      $words_ret[$next++]["reason_id"] = $row[3];    
    }
  }
  mysql_close($mysql_conn);
  usort($words_ret, "wordWithQualComparator");
  return $words_ret;
}
?>
