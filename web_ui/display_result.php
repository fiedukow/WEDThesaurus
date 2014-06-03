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
<div id="search_results">
<?php
if (isset($_GET['id'])) {
  $sql_conn = mysql_connect('localhost', 'root', 'toor');
  mysql_select_db('WEDT');
  mysql_query("SET NAMES utf8");
  $result = mysql_query("SELECT `id`, `title`, `text` FROM `texts` WHERE `id`='".$_GET['id']."'");
  if ($row = mysql_fetch_array($result)) {
?>
<div class="result">
<h1><?php echo $row['title']; ?></h1>
<hr>
<?php
foreach (explode("\n", $row['text']) as $line) { ?>
  <p><?php echo $line; ?></p>
<?php
    }
?>
<form method="get" action="./">
  <input type="hidden" name="q" value="<?php echo $_GET['q']; ?>">
  <input type="submit" value="<< Back to search results">
</form>
</div>
<?php
  } else {
    echo "404";
  }
} else {
  echo "404";
}
?>
</div>
</body>
</html>

