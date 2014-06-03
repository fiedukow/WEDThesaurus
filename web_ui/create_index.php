<ul>
<?php
include("Zend/Search/Lucene.php");
echo "<li>Creating index...</li>\n";
$index = Zend_Search_Lucene::create('/LuceneIndex/WEDT_MAIN');
echo "<li>Opening index...</li>\n";
$index = Zend_Search_Lucene::open('/LuceneIndex/WEDT_MAIN');
$sql_conn = mysql_connect('localhost', 'root', 'toor');
mysql_select_db('WEDT');
mysql_query("SET NAMES utf8");
$result = mysql_query("SELECT `id`, `title`, `text` FROM `texts`");
echo "<li>Reading from DB</li>\n<ul>\n";
while($row=mysql_fetch_array($result)) {
  echo "<li>".$row['title']."</li>\n";
  $doc = new Zend_Search_Lucene_Document();
  $doc->addField(Zend_Search_Lucene_Field::keyword('id', $row['id']));
  $doc->addField(Zend_Search_Lucene_Field::text('title', $row['title'], 'utf-8'));
  $doc->addField(Zend_Search_Lucene_Field::unIndexed('shortcut', substr($row['text'], 0, 255), 'utf-8'));
  $doc->addField(Zend_Search_Lucene_Field::unStored('text', $row['text'], 'utf-8'));
  $index->addDocument($doc);
}
echo "</ul>\n";
mysql_close($sql_conn);

?>
</ul>
