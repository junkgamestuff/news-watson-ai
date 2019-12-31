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
<?php echo $_GET["tone"]; ?>

<table>
<?php

$sql="SELECT * FROM news_sentences WHERE src = '" . $src . "'";
$rs=$mysqli->query($sql);
$rs->data_seek(0);
while($row = $rs->fetch_assoc()) {
	
	$tid = $row["tid"];
	$text_str = $row["text_str"];

	echo "<tr><td>";
	$sql="SELECT * FROM news_sentences_scores WHERE tid = " . $tid  . "";
	$rs2=$mysqli->query($sql);
	$rs2->data_seek(0);
	while($row = $rs2->fetch_assoc()) {

		$name = ucfirst($row["name"]);
		$score = $row["score"];
		$score = round((float)$score * 100) . '%';
		
		echo $name . " - " . $score . "<br>";





}

	echo "</td><td>";

	echo $text_str;


	echo "</td></tr>";

}
?>
</table>

</body>
</html>
