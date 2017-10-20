<?php include("header.php");
?>

<h3>Tragen Sie Ihre Notiz ein</h3> 

<form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST"> <br>
  <textarea cols=60 rows=10 name="note" wrap=virtual></textarea> 
  <input type="submit" value=" Notiz absenden "> 
  
</form> 
		<form action="../index.php">

		<input style="width:150;height:32px" type="submit" value="Start Seite">
	</form>

<?php 
if(isset($_POST['note'])) 
{ 
   $fp = fopen("daten/index.txt","a"); 
   fputs($fp,nl2br($_POST['note'])."<p>\n"); 
   fclose($fp); 
} 
?> 

<h3><font color="#8000000">Notizen:</font></h3> 
<?php @readfile("daten/index.txt") ?> 

<?
include("footer.php"); ?>