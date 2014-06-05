<?php
function wordWithQualComparator($w1, $w2) {
  if ($w1["qual"] == $w2["qual"])
    return 0;
  return (($w1["qual"] > $w2["qual"]) ? -1 : 1);
}

function displaySummary($word_id) {
  $mysql_conn = mysql_connect("localhost", "root", "toor");
  mysql_select_db("WEDT");
  $q = "SELECT COUNT( * ) , SUM( display_count ) ".
       "FROM  `synonyms` ".
       "WHERE  `word_id` = ".$word_id." ".
       "GROUP BY  `word_id`";
  $results = mysql_query($q);
  if ($row = mysql_fetch_array($results)) {
    $stat["count"] = $row[0];
    $stat["sum"] = $row[1];
  }
  mysql_close($mysql_conn);
  return $stat;
}

function qual($base_qual, $own_counter, $stats) {
  $BASE_COUNTER_MODIFIER = 50;
  $total_sum = $stats["count"]*$BASE_COUNTER_MODIFIER + $stats["sum"];
  $total_own = $own_counter + $BASE_COUNTER_MODIFIER;
  $standard_count = $total_sum/$stats["count"];
  $qual_mod = $total_own/$standard_count;
  return min($base_qual * $qual_mod, 1.0);
}

function getWordsAndTheirSynonyms($words) {
  $mysql_conn = mysql_connect("localhost", "root", "toor");
  mysql_select_db("WEDT");
  $end = count($words);
  $next = 0;
  for($i = 0; $i < $end; ++$i) {
    $q = "SELECT l.`literal`, l.`id`, s.`quality`, s.`display_count`, w.`id` ".
         "FROM `literals` l0 ".
         "JOIN `words` w ON w.`literal_id` = l0.`id` ".
         "JOIN `synonyms` s ON w.`id` = s.`word_id` ".
         "JOIN `literals` l ON l.`id`=s.`literal_id` ".
         "WHERE l0.`literal`=\"".$words[$i]."\" ".
         "ORDER BY s.`quality` DESC";
    $results = mysql_query($q);
    $any_syn = false;
    while($row = mysql_fetch_array($results)) {
      $any_syn = true;
      $words_ret[$next  ]["syn"      ] = $row[0];
      $words_ret[$next  ]["syn_id"   ] = $row[1];
      $words_ret[$next  ]["qual"     ] = qual($row[2],
                                              $row[3],
                                              displaySummary($row[4]));
      $words_ret[$next  ]["reason"   ] = $words[$i];
      $words_ret[$next++]["reason_id"] = $row[4];
    }
    if (!$any_syn) {
      $words_ret[$next  ]["syn"      ] = $words[$i];
      $words_rec[$next  ]["syn_id"   ] = -1;
      $words_ret[$next  ]["qual"     ] = 1.0;
      $words_ret[$next  ]["reason"   ] = $words[$i];
      $words_ret[$next++]["reason_id"] = -1;
    }
  }
  mysql_close($mysql_conn);
  usort($words_ret, "wordWithQualComparator");
  return $words_ret;
}

function selectedSynonym($word_id, $syn_l_id) {
  $mysql_conn = mysql_connect("localhost", "root", "toor");
  mysql_select_db("WEDT");
  $q = "UPDATE `synonyms` SET `display_count` = `display_count` + 1 WHERE ".
       "`word_id` = '".$word_id."' AND `literal_id` = '".$syn_l_id."'";
  mysql_query($q);
  mysql_close($mysql_conn);
}
?>
