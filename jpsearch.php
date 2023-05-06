<html>
	<title>Search in J-Pilot</title>
<?php require 'jptop.php'; ?>
<body>
<h1>Seach in J-Pilot</h1>
<?php
/* jptables: search in J-Pilot data
   Elmar Klausmeier, 02-May-2023
*/
$srch = $_POST["searchstr"] ?? NULL;
$casesensitive = $_POST["Casesensitive"] ?? 0;
$day = $_POST['Day'] ?? NULL;
$dayValid = (isset($day) && strlen($day) === 10) ? true : false;
?>
	<form action="jpsearch.php" method="post">
		<label for=searchstr>Search for strings in J-Pilot:</label>
		<input type=text id=searchstr name=searchstr value="<?=$srch??''?>" autofocus>
		&nbsp; <input type=checkbox id=Casesensitive name=Casesensitive value=1 <?=$casesensitive? 'checked':''?>>
		Case sensitive
		<br><br>Search only for dates: <input type=date id=Day name=Day value="<?=$day??''?>">
		<br><br><input type=button value=Clear onclick="clearFrm()"> &nbsp; &nbsp; <input type=submit value=submit>
	</form>
<?php
//echo '<pre>home=' . $home . ', dbpath=' . $dbpath . "</pre>\n";
if (isset($srch) && strlen($srch) > 0 && strlen($srch) < 240  ||  $dayValid) {
	$db = new SQLite3($dbpath);
	if ($dayValid) {
		$stmt = $db->prepare(
			"select Id, 'Datebook', substr(Begin || '  ' || coalesce(Description,''),1,80) as Line "
			."from Datebook "
			."where Begin like :srch "
			."order by Begin"
		);
		$stmt->bindValue(":srch",$day.'%',SQLITE3_TEXT);	// add % right
	} else {
		if ($casesensitive) $db->exec("PRAGMA case_sensitive_like=true");
		$stmt = $db->prepare(
			"select Id, 'Addr', substr(coalesce(Lastname,Firstname,''),1,80) as Line "
			."from Addr "
			."where Lastname like :srch "
			."or Firstname like :srch "
			."or Phone1 like :srch "
			."or Phone2 like :srch "
			."or Phone3 like :srch "
			."or Phone4 like :srch "
			."or Phone5 like :srch "
			."or Address like :srch "
			."or City like :srch "
			."or State like :srch "
			."or Zip like :srch "
			."or Custom1 like :srch "
			."or Custom2 like :srch "
			."or Custom3 like :srch "
			."or Custom4 like :srch "
			."or Note like :srch "
			."union "
			."select Id, 'Datebook', substr(Begin || '  ' || coalesce(Description,''),1,80) as Line "
			."from Datebook "
			."where Description like :srch "
			."or Note like :srch "
			."union "
			."select Id, 'Memo', substr(Text,1,80) as Line "
			."from Memo "
			."where Text like :srch "
			."union "
			."select Id, 'ToDo', substr(Description,1,80) as Line "
			."from ToDo "
			."where Description like :srch "
			."or Note like :srch "
			."union "
			."select Id, 'Expense', substr(coalesce(Date,'') || ' ' "
			."    || coalesce(Amount,'') || coalesce(Vendor,'') "
			."    || coalesce(Note,''),1,80) as Line "
			."from Expense "
			."where Amount like :srch "
			."or Vendor like :srch "
			."or City like :srch "
			."or Attendees like :srch "
			."or Note like :srch "
		);
		$stmt->bindValue(":srch",'%'.$srch.'%',SQLITE3_TEXT);	// add % left+right
	}
	$result = $stmt->execute();

	echo "<table>\n";
	while (($r = $result->fetchArray()) !== false) {
		printf("\t<tr><td><a href=jpedit.php?%s?%d>%s</a></td><td>%s</td></tr>\n",$r[1],$r['Id'],$r[1],strip_tags($r['Line']));
	}
	echo "</table>\n";
}
?>
<script>
function clearFrm() {
	//alert("clearFrm()");
	document.getElementById('searchstr').value = "";
	document.getElementById('Day').value = "";
	document.getElementById('Casesensitive').checked = false;
}
</script>
</body>
</html>


