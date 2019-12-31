<?php
error_reporting(E_ALL);

include "includes/connection.php";

$update_date = date("Y-m-d H:i:s");

$sql="SELECT * FROM watson_keys";
$rs=$mysqli->query($sql);
$rs->data_seek(0);
while($row = $rs->fetch_assoc()) {
	$api_key = $row["api_key"];
}


$stmt = $mysqli->prepare("INSERT INTO update_date_time (update_date, source) VALUES (?,?)");
        $stmt -> bind_param('ss',$update_date,$tone_source);
        $stmt -> execute();	

$trend_id = $tid = mt_rand(111111111,999999999);




$sql="SELECT * FROM news_sources";
$rs1=$mysqli->query($sql);
$rs1->data_seek(0);
while($row = $rs1->fetch_assoc()) {
  $pub = $row["pub"];
  $src = $row["src"];
  $rss = $row["rss"];


//$pub = "newsmax";
//$src = "politics";
//$rss = "https://rss.nytimes.com/services/xml/rss/nyt/Politics.xml";
//$rss = "https://www.buzzfeed.com/politics.xml";
//$rss = "http://feeds.bbci.co.uk/news/politics/rss.xml";
//$rss = "http://rss.cnn.com/rss/cnn_allpolitics.rss";
//$rss = "https://www.politico.com/rss/politics08.xml";
//$rss = "https://www.latimes.com/politics/rss2.0.xml";
//$rss = "https://www.cnbc.com/id/10000113/device/rss/rss.html";
//$rss = "http://api.npr.org/query?id=1014&title=NPR%20Politics&output=RSS&apiKey=MDEyMDk1NTU4MDEzNzc4Nzk2Mjk5YTQ3MQ001";
//$rss = "https://thehill.com/taxonomy/term/29/feed";
//$rss = "https://www.newsmax.com/rss/Politics/1/";
$doc = new DOMDocument();
$doc->load($rss);
$items = $doc->getElementsByTagName("item");
  

    foreach($items as $item) {

      $title = $item->getElementsByTagName("title");
      $title = $title->item(0)->nodeValue;
      $title = strip_tags($title);
      echo $title;

      $description = $item->getElementsByTagName("description");
      $description = $description->item(0)->nodeValue;
      $description = strip_tags($description);
      //echo $description;

      $link = $item->getElementsByTagName("link");
      $link = $link->item(0)->nodeValue;

      $creator = $item->getElementsByTagName("creator");
      $creator = $creator->item(0)->nodeValue;
 
      $pub_date = $item->getElementsByTagName("pubDate");
      $pub_date = $pub_date->item(0)->nodeValue;
      $pub_date = date_create($pub_date);
      $pub_date = date_format($pub_date, "Y-m-d H:i:s");



 	$stmt = $mysqli->prepare("INSERT INTO news_tone (title,description,link,creator,pub_date,pub,src,update_date,trend_id) VALUES (?,?,?,?,?,?,?,?,?)");
                $stmt -> bind_param('sssssssss',$title,$description,$link,$creator,$pub_date,$pub,$src,$update_date,$trend_id);
                $stmt -> execute();	

                $error = $mysqli->errno . ' ' . $mysqli->error;

            
}


$sql="SELECT * FROM news_tone ORDER BY ID DESC LIMIT 1";
$rs=$mysqli->query($sql);
$rs->data_seek(0);
while($row = $rs->fetch_assoc()) {
	$update_date = $row["update_date"];

}

$file = fopen(__DIR__ . "/tone-file/tone-" . $pub . "-" . $src . ".json","w");
$description = array();
$sql="SELECT * FROM news_tone WHERE src = '" . $src . "' AND pub = '" . $pub . "'";
$rs=$mysqli->query($sql);
$rs->data_seek(0);
while($row = $rs->fetch_assoc()) { 
	$description[] = strip_tags($row["description"]);
			}

$description = implode(",", $description);
$description = str_replace(array("\n", "\r"), '', $description);
$description = str_replace("\"","",$description);

fwrite($file,"{");
fwrite($file,"\"text\":");
fwrite($file,"\"");
fwrite($file,$description);
fwrite($file,"\"");
fwrite($file,"}");

exec("curl -o " . __DIR__ . "/tone-output/tone-" . $pub . "-" . $src . ".json -X POST -u 'apikey:" . $api_key . "' --header 'Content-Type: application/json' --data-binary @" . __DIR__ . "/tone-file/tone-" . $pub . "-" . $src . ".json 'https://gateway.watsonplatform.net/tone-analyzer/api/v3/tone?version=2017-09-21'");

$path = __DIR__ ."/tone-output/tone-" . $pub . "-" . $src . ".json";
$json_file = file_get_contents($path);
$json = json_decode($json_file);

	foreach ($json->document_tone->tones as $tones) {
		$score = $tones->score;
		$name = $tones->tone_name;

		$stmt = $mysqli->prepare("INSERT INTO news_tone_scores (score,name,update_date,src,pub) VALUES (?,?,?,?,?)");
        $stmt -> bind_param('sssss',$score,$name,$update_date,$src,$pub);
        $stmt -> execute();	

$sql="SELECT * FROM news_tone_scores WHERE src = '" . $src . "' AND pub = '" . $pub . "'";
$rs=$mysqli->query($sql);
$rs->data_seek(0);
while($row = $rs->fetch_assoc()) {
	$score = $row["score"];
	}

}

	foreach ($json->sentences_tone as $sentences_tones) {
		$text_str = $sentences_tones->text;
		$tid = mt_rand(111111111,999999999);


		$stmt = $mysqli->prepare("INSERT INTO news_sentences (text_str, update_date, tid, src, pub) VALUES (?,?,?,?,?)");
        $stmt -> bind_param('ssiss',$text_str,$update_date,$tid,$src,$pub);
        $stmt -> execute();	

		foreach ($sentences_tones->tones as $sub_tones) {

			$score = $sub_tones->score;
			$tone_id = $sub_tones->tone_id;

			$stmt = $mysqli->prepare("INSERT INTO news_sentences_scores (text_str, score, name, update_date, tid, src, pub) VALUES (?,?,?,?,?,?,?)");
        	$stmt -> bind_param('sssssss',$text_str,$score, $tone_id,$update_date,$tid,$src,$pub);
        	$stmt -> execute();	

		}
		

	}

}

?>
