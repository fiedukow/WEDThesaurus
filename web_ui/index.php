<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="Author" content="fiedukow">
  <meta http-equiv="Content-Language" content="pl"> 
  <title>Greatest search engine ever was.</title>
  <link rel="Stylesheet" type="text/css" href="style.css">
</head>
<body>
<div id="search_bar">
  <form method="get">
    <input type="text" name="q" value="<?php echo $_GET['q']; ?>">
    <input type="submit" value="Search in DB">
  </form>
</div>
<div id="search_results">
<?php
if (isset($_GET['q'])) {
  require_once("Zend/Search/Lucene.php");
  require_once("./synonyms_helper.php");
  $index = Zend_Search_Lucene::open('/LuceneIndex/WEDT_MAIN');
  $founded = array();
  $words = getWordsAndTheirSynonyms(explode(' ', $_GET['q']));
  foreach($words as $word) {
    $query = Zend_Search_Lucene_Search_QueryParser::parse($word.'~', 'utf-8');
    $hits = $index->find($query);
    foreach ($hits as $hit) {
      $document = $hit->getDocument();
      if (in_array($document->getFieldValue("id"), $founded))
        continue;
      else
        array_push($founded, $document->getFieldValue("id"));
?>
<div class="result">
<h1><a href="./display_result.php?q=<?php echo $_GET['q']; ?>&id=<?php echo $document->getFieldValue('id'); ?>"><?php echo $document->getFieldValue('title'); ?></a></h1>
<hr>
<p><?php echo $document->getFieldValue('shortcut').' (...)'; ?></p>
</div>
<?php
    }
  }
}
?>
</div>
</body>
</html>
