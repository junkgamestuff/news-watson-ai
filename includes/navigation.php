<?php 
$src = $_GET["src"]; 
$tone = @$_GET["tone"]; 

?>

<a href="/">Home</a> | 
<a href="view_tone.php?src=<?php echo $src; ?>">View tones for #<?php echo $src; ?></a> | 
<a href="view_all_tone_sentences.php?src=<?php echo $src; ?>">View all sentences for #<?php echo $src; ?></a>