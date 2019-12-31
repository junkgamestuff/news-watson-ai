<?php

error_reporting(E_ALL);

include "includes/connection.php";

$src = $_GET["src"];


?>

<html>
<head>
	<title></title>
</head>
<body>

<style>

	table {
  border-collapse: collapse;
  width:70%;
}

	table, th, td {
  border: 1px solid black;
}

td {
	padding:10px;
	width:40px;
}



</style>

<?php include "includes/navigation.php"; ?>
<br>
<br>


<table><tr><td colspan="3">
Overall score is checked every hour on a sample of news feeds. Instances show how many times that sentiment appeared in aggregated results.
</td></tr>
<tr><td>Sentiment</td>
<td>Instances</td>
<td>Average</td>
</tr>
<?php



$sql="SELECT DISTINCT name FROM news_tone_scores WHERE src = '" . $src . "'";
$rs=$mysqli->query($sql);
$rs->data_seek(0);
while($row = $rs->fetch_assoc()) {
	
	$name = $row["name"];
	echo "<tr><td><a href='view_tone_sentences.php?tone=" . strtolower($name) . "&src=". $src . "'>" . $name . "</a>";

	$score = array();
	$sql2="SELECT * FROM news_tone_scores WHERE name = '" . $name . "' AND src = '" . $src . "'";
	$rs2=$mysqli->query($sql2);
  	$rs2->data_seek(0);
	while($row = $rs2->fetch_assoc())

  			{
			 	$score[] = $row["score"];
			 	
				}
				echo "</td><td>" . count($score) . "</td>";
				$score = array_filter($score);
    			$score = array_sum($score)/count($score);
    			$score = round((float)$score * 100) . '%';

    			echo "<td>" . $score . "</td>";

}

	echo "</tr>";


?>


</body>
</html>
