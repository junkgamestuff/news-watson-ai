<?php

error_reporting(E_ALL);

include "includes/connection.php";

$src = $_GET["src"];


?>


<html>
<head>
	<title></title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
</head>
<body>


<?php
$x = 0;

$sql="SELECT distinct(name) FROM news_tone_scores WHERE src = '" . $src . "' ORDER BY name";
$rs=$mysqli->query($sql);
$rs->data_seek(0);
while($row = $rs->fetch_assoc()) {
	
	$name = $row["name"];
	$number = $x++;
?>

<?php print $name; ?><br>

<div id="tone-score-list-<?php echo $number; ?>"></div>
<script>
//$("#tone-score-list-<?php echo $number; ?>").load("tone_score_list.php?name=<?php print $name; ?>&src=<?php echo str_replace(" ","%20",$src); ?>");
</script>



<?php

}

?>


</body>
</html>

