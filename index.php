<?php

error_reporting(E_ALL);

include "includes/connection.php";

?>


<html>
<head>
	<title></title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

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



.tone-table {
}

.tone-table-cell {
	float:left;
	border:1px solid black;
	padding:20px;
	margin:4px;

}

.more-info-wrapper {
	background-color: #eeeeee;
	margin:4px;
}

</style>


<div class="tone-table">
<?php

$x = 0;
$sql="SELECT src FROM news_tone_scores GROUP BY src";
$rs=$mysqli->query($sql);
$rs->data_seek(0);
while($row = $rs->fetch_assoc()) {
	
	$src = $row["src"];
	$num = $x++;
?>
<!-- adding a little comment to test -->
<div class="more-info-wrapper">	
<div id="more-info" class="tone-table-cell"><a href="view_tone.php?src=<?php echo $src; ?>"><?php echo $src; ?></a></div>
<div id="more-info-tone-<?php echo $num; ?>" class="tone-table-cell"></div>
<br clear="all">
<script>
$("#more-info-tone-<?php echo $num; ?>").load("tone_list.php?src=<?php echo str_replace(" ","%20",$src); ?>");
</script>
</div>
<?php

}

?>

</div>




</body>
</html>