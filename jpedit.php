<html>
	<title>Edit J-Pilot record</title>
<?php require 'jptop.php'; ?>
<body>
<?php
/* jptables: J-Pilot data editing
   Elmar Klausmeier, 02-May-2023
*/
$cmd = $_POST["cmd"] ?? '';
$sqlIdx = 1;	// select statement
$qry = $_SERVER['QUERY_STRING'] ?? NULL;
if ($qry === NULL) {
	echo "Missing arguments. QUERY_STRING=$qry\n";
	goto L1;
}
if (($argv = explode('?',$qry)) < 2) {
	echo "Too few arguments.\n";
	goto L1;
}
$Id = intval($argv[1]);
$tableSQL = array(
	'Addr' => [1,
		"select Id, Category, Private, showPhone,"
		."    Lastname, Firstname, Title, Company,"
		."    PhoneLabel1, PhoneLabel2, PhoneLabel3, PhoneLabel4, PhoneLabel5,"
		."    Phone1, Phone2, Phone3, Phone4, Phone5,"
		."    Address, City, State, Zip, Country,"
		."    Custom1, Custom2, Custom3, Custom4, Note "
		."from Addr "
		."where Id = :Id",
		"delete from Addr where Id = :Id",
		"select 1 + max(Id) from Addr",
		"insert into Addr ("
		."    Id, Category, Private, showPhone,"	// 1
		."    Lastname, Firstname, Title, Company,"	// 5
		."    PhoneLabel1, PhoneLabel2, PhoneLabel3,"	// 9
		."    PhoneLabel4, PhoneLabel5,"		// 12
		."    Phone1, Phone2, Phone3, Phone4, Phone5,"	// 14
		."    Address, City, State, Zip, Country,"	// 19
		."    Custom1, Custom2, Custom3, Custom4,"	// 24
		."    Note, InsertDate"				// 28
		.") values ("
		."    :Id, :Category, :Private, :showPhone,"
		."    :Lastname, :Firstname, :Title, :Company,"
		."    :PhoneLabel1, :PhoneLabel2, :PhoneLabel3,"
		."    :PhoneLabel4, :PhoneLabel5,"
		."    :Phone1, :Phone2, :Phone3, :Phone4, :Phone5,"
		."    :Address, :City, :State, :Zip, :Country,"
		."    :Custom1, :Custom2, :Custom3, :Custom4,"
		."    :Note, strftime('%Y-%m-%dT%H:%M:%S', 'now')"
		.")",
		"update Addr set"
		."    Category=:Category, Private=:Private, showPhone=:showPhone,"	// 1
		."    Lastname=:Lastname, Firstname=:Firstname, Title=:Title, Company=:Company,"	// 4
		."    PhoneLabel1=:PhoneLabel1, PhoneLabel2=:PhoneLabel2, PhoneLabel3=:PhoneLabel3,"	// 8
		."    PhoneLabel4=:PhoneLabel4, PhoneLabel5=:PhoneLabel5,"		// 11
		."    Phone1=:Phone1, Phone2=:Phone2, Phone3=:Phone3, Phone4=:Phone4, Phone5=:Phone5,"	// 13
		."    Address=:Address, City=:City, State=:State, Zip=:Zip, Country=:Country,"	// 18
		."    Custom1=:Custom1, Custom2=:Custom2, Custom3=:Custom3, Custom4=:Custom4,"	// 23
		."    Note=:Note, UpdateDate = strftime('%Y-%m-%dT%H:%M:%S', 'now') "			// 27
		."where Id = :Id"],					// 28
	'Datebook' => [2,
		"select Id, Private, Timeless, Begin, End, Alarm,"
		."    Advance, AdvanceUnit, RepeatType, RepeatForever,"
		."    RepeatEnd, RepeatFreq, RepeatDay,"
		."    RepeatDaySu, RepeatDayMo, RepeatDayTu, RepeatDayWe,"
		."    RepeatDayTh, RepeatDayFr, RepeatDaySa,"
		."    Exceptions, Exception, Description, Note "
		."from Datebook "
		."where Id = :Id",
		"delete from Datebook where Id = :Id",
		"select 1 + max(Id) from Datebook",
		"insert into Datebook ("
		."    Id, Private, Timeless, Begin, End,"	// 1
		."    Alarm, Advance, AdvanceUnit, RepeatType,"	// 6
		."    RepeatForever, RepeatEnd, RepeatFreq,"	// 10
		."    RepeatDay,"				// 13
		."    RepeatDaySu, RepeatDayMo, RepeatDayTu,"	// 14
		."    RepeatDayWe, RepeatDayTh, RepeatDayFr,"	// 17
		."    RepeatDaySa, Exceptions, Exception,"	// 20
		."    Description, Note, InsertDate"		// 23
		.") values ("
		."    :Id, :Private, :Timeless, :Begin, :End,"
		."    :Alarm, :Advance, :AdvanceUnit, :RepeatType,"
		."    :RepeatForever, :RepeatEnd, :RepeatFreq,"
		."    :RepeatDay,"
		."    :RepeatDaySu, :RepeatDayMo, :RepeatDayTu,"
		."    :RepeatDayWe, :RepeatDayTh, :RepeatDayFr,"
		."    :RepeatDaySa, :Exceptions, :Exception,"
		."    :Description, :Note,"
		."    strftime('%Y-%m-%dT%H:%M:%S', 'now')"
		.")",
		"update Datebook set"
		."    Private=:Private, Timeless=:Timeless, Begin=:Begin, End=:End, "	// 1
		."    Alarm=:Alarm, Advance=:Advance, AdvanceUnit=:AdvanceUnit, RepeatType=:RepeatType, "	// 5
		."    RepeatForever=:RepeatForever, RepeatEnd=:RepeatEnd, RepeatFreq=:RepeatFreq, "	// 9
		."    RepeatDay=:RepeatDay, "				// 12
		."    RepeatDaySu=:RepeatDaySu, RepeatDayMo=:RepeatDayMo, RepeatDayTu=:RepeatDayTu, "	// 13
		."    RepeatDayWe=:RepeatDayWe, RepeatDayTh=:RepeatDayTh, RepeatDayFr=:RepeatDayFr, "	// 16
		."    RepeatDaySa=:RepeatDaySa, Exceptions=:Exceptions, Exception=:Exception, "	// 19
		."    Description=:Description, Note=:Note, "		// 22
		."    UpdateDate = strftime('%Y-%m-%dT%H:%M:%S', 'now') "
		."where Id = :Id"],		// 22
	'Memo' => [3,
		"select Id, Category, Private, Text "
		."from Memo "
		."where Id = :Id",
		"delete from Memo where Id = :Id",
		"select 1 + max(Id) from Memo",
		"insert into Memo ("
		."    Id, Category, Private, Text, "
		."    InsertDate "
		.") values ("
		."    :Id, :Category, :Private, :Text, "
		."    strftime('%Y-%m-%dT%H:%M:%S', 'now') "
		.")",
		"update Memo set"
		."    Category=:Category, Private=:Private, Text=:Text, "
		."    UpdateDate = strftime('%Y-%m-%dT%H:%M:%S', 'now') "
		."where Id = :Id"],
	'ToDo' => [4,
		"select Id, Category, Private, Indefinite, Due,"
		."    Priority, Complete, Description, Note "
		."from ToDo "
		."where Id = :Id",
		"delete from ToDo where Id = :Id",
		"select 1 + max(Id) from ToDo",
		"insert into ToDo ("
		."    Id, Category, Private, Indefinite, "	// 1
		."    Due, Priority, Complete, "			// 5
		."    Description, Note, "			// 8
		."    InsertDate "
		.") values ("
		."    :Id, :Category, :Private, :Indefinite, "
		."    :Due, :Priority, :Complete, "
		."    :Description, :Note, "
		."    strftime('%Y-%m-%dT%H:%M:%S', 'now') "
		.")",
		"update ToDo set"
		."    Category=:Category, Private=:Private, Indefinite=:Indefinite, "	// 1
		."    Due=:Due, Priority=:Priority, Complete=:Complete, "			// 4
		."    Description=:Description, Note=:Note, "			// 7
		."    UpdateDate = strftime('%Y-%m-%dT%H:%M:%S', 'now') "
		."where Id = :Id"],				// 9
	'Expense' => [5,
		"select Id, Category, Date, Type, Payment, Currency,"
		."    Amount, Vendor, City, Attendees, Note "
		."from Expense "
		."where Id = :Id",
		"delete from Expense where Id = :Id",
		"select 1 + max(Id) from Expense",
		"insert into Expense ("
		."    Id, Category, Date, Type, Payment, "	// 1
		."    Currency, Amount, Vendor, City, "		// 6
		."    Attendees, Note, InsertDate "				// 10
		.") values ("
		."    :Id, :Category, :Date, :Type, :Payment, "
		."    :Currency, :Amount, :Vendor, :City, "
		."    :Attendees, :Note, "
		."    strftime('%Y-%m-%dT%H:%M:%S', 'now') "
		.")",
		"update Expense set"
		."    Category=:Category, Date=:Date, Type=:Type, Payment=:Payment, "	// 1
		."    Currency=:Currency, Amount=:Amount, Vendor=:Vendor, City=:City, "		// 5
		."    Attendees=:Attendees, Note=:Note, "				// 9
		."    UpdateDate = strftime('%Y-%m-%dT%H:%M:%S', 'now') "
		."where Id = :Id"]		// 11
);
$sqlIndex = array('Delete' => 2, /*'Copy' => 3,*/ 'Insert' => 4, 'Update' => 5);
if (!array_key_exists($argv[0],$tableSQL)) {
	echo "<pre>Illegal table: argv0=$argv[0]</pre>\n";
	goto L1;
}
$tablenr = $tableSQL[$argv[0]][0];
echo "<h1>Edit J-Pilot " . $argv[0] . " record</h1>\n";

$db = new SQLite3($dbpath,SQLITE3_OPEN_READWRITE);
$sqlIdx = $sqlIndex[$cmd] ?? 1;	// SELECT is default if we do not understand the command
//echo '<pre>sqlIdx: '; var_dump($sqlIdx); echo "</pre>\n";

if ($sqlIdx === 4) {	// Special handling for INSERT: first get max(Id) from table
	$result = $db->query($tableSQL[$argv[0]][3]);
	$r = $result->fetchArray();
	printf("<pre>INSERT: Id=%d, r[0]=%d</pre>\n",$Id,$r[0]);
	$Id = $r[0];
	$result->finalize();
}
$stmt = $db->prepare($tableSQL[$argv[0]][$sqlIdx]);
if ($sqlIdx === 4  ||  $sqlIdx === 5) {	//$cmd === 'Insert' || $cmd === 'Update'
	if ($tablenr === 1) {	// Addr
		$stmt->bindValue(":Category",$_POST['Category'],SQLITE3_INTEGER);
		$stmt->bindValue(":Private",$_POST['Private'] ?? 0,SQLITE3_INTEGER);
		// showPhone not useful right now
		$stmt->bindValue(":Lastname",$_POST['Lastname']);
		$stmt->bindValue(":Firstname",$_POST['Firstname']);
		$stmt->bindValue(":Title",$_POST['Title']);
		$stmt->bindValue(":Company",$_POST['Company']);
		$stmt->bindValue(":PhoneLabel1",$_POST['PhoneLabel1'],SQLITE3_INTEGER);
		$stmt->bindValue(":PhoneLabel2",$_POST['PhoneLabel2'],SQLITE3_INTEGER);
		$stmt->bindValue(":PhoneLabel3",$_POST['PhoneLabel3'],SQLITE3_INTEGER);
		$stmt->bindValue(":PhoneLabel4",$_POST['PhoneLabel4'],SQLITE3_INTEGER);
		$stmt->bindValue(":PhoneLabel5",$_POST['PhoneLabel5'],SQLITE3_INTEGER);
		$stmt->bindValue(":Phone1",$_POST['Phone1']);
		$stmt->bindValue(":Phone2",$_POST['Phone2']);
		$stmt->bindValue(":Phone3",$_POST['Phone3']);
		$stmt->bindValue(":Phone4",$_POST['Phone4']);
		$stmt->bindValue(":Phone5",$_POST['Phone5']);
		$stmt->bindValue(":Address",$_POST['Address']);
		$stmt->bindValue(":City",$_POST['City']);
		$stmt->bindValue(":State",$_POST['State']);
		$stmt->bindValue(":Zip",$_POST['Zip']);
		$stmt->bindValue(":Country",$_POST['Country']);
		$stmt->bindValue(":Custom1",$_POST['Custom1']);
		$stmt->bindValue(":Custom2",$_POST['Custom2']);
		$stmt->bindValue(":Custom3",$_POST['Custom3']);
		$stmt->bindValue(":Custom4",$_POST['Custom4']);
		$stmt->bindValue(":Note",$_POST['Note']);
	} else if ($tablenr === 2) {	// Datebook
		$stmt->bindValue(":Private",$_POST['Private'] ?? 0,SQLITE3_INTEGER);
		$stmt->bindValue(":Begin",$_POST['Begin']);
		$stmt->bindValue(":End",$_POST['End']);
		$stmt->bindValue(":RepeatFreq",$_POST['RepeatFreq'],SQLITE3_INTEGER);
		$stmt->bindValue(":Description",$_POST['Description']);
		$stmt->bindValue(":Note",$_POST['Note']);
	} else if ($tablenr === 3) {	// Memo
		printf("<pre>Category=%s, Private=%s, Text=|%s|</pre>\n",$_POST['Category'],$_POST['Private'],$_POST['Text']);
		$stmt->bindValue(":Category",$_POST['Category'],SQLITE3_INTEGER);
		$stmt->bindValue(":Private",$_POST['Private'] ?? 0,SQLITE3_INTEGER);
		$stmt->bindValue(":Text",$_POST['Text']);
	} else if ($tablenr === 4) {	// ToDo
		$stmt->bindValue(":Category",$_POST['Category'],SQLITE3_INTEGER);
		$stmt->bindValue(":Private",$_POST['Private'] ?? 0,SQLITE3_INTEGER);
		$stmt->bindValue(":Due",$_POST['Begin']);
		$stmt->bindValue(":Priority",$_POST['Priority'],SQLITE3_INTEGER);
		$stmt->bindValue(":Complete",$_POST['Complete'],SQLITE3_INTEGER);
		$stmt->bindValue(":Description",$_POST['Description']);
		$stmt->bindValue(":Note",$_POST['Note']);
	} else if ($tablenr === 5) {	// Expense
		$stmt->bindValue(":Category",$_POST['Category'],SQLITE3_INTEGER);
		$stmt->bindValue(":Date",$_POST['Date']);
		$stmt->bindValue(":Type",$_POST['Type'],SQLITE3_INTEGER);
		$stmt->bindValue(":Payment",$_POST['Payment'],SQLITE3_INTEGER);
		$stmt->bindValue(":Currency",$_POST['Currency'],SQLITE3_INTEGER);
		$stmt->bindValue(":Amount",$_POST['Amount']);
		$stmt->bindValue(":Vendor",$_POST['Vendor']);
		$stmt->bindValue(":City",$_POST['City']);
		$stmt->bindValue(":Attendees",$_POST['Attendees']);
		$stmt->bindValue(":Note",$_POST['Note']);
	}
}
$stmt->bindValue(":Id",$Id,SQLITE3_INTEGER);
$result = $stmt->execute();
if ($sqlIdx === 2)	{	//$cmd === 'Delete'
	echo '<p>' . $tableSQL[$argv[0]][$sqlIdx] . "</p>\n";
	printf("<p>%s record deleted:  %s.</p>\n",$argv[0], $result->finalize() ? 'successful' : 'failed');
	goto L1;
} else if ($sqlIdx === 4) {	//$cmd === 'Insert'
	echo '<pre>' . $argv[0] . " record inserted</pre>\n";
	goto L1;
} else if ($sqlIdx === 5) {	//$cmd === 'Update'
	echo '<pre>' . $argv[0] . " record updated</pre>\n";
	echo '<pre>'; var_dump($_POST); echo "</pre>\n";
	goto L1;
}
if (($r = $result->fetchArray()) === false) {
	printf("<pre>No data found: tablenr=%d, table=%s, Id=%d</pre>\n",$tablenr, $argv[0], $Id);
	goto L1;
}
?>

<!-- &nbsp; <input type=submit name="cmd" value="Copy"> -->

<form name=jpedit id=jpedit action="jpedit.php?<?=$_SERVER['QUERY_STRING']?>" method="post">
	<input type=button value=Clear onclick="clearFrm()">
	&nbsp; <input type=submit name="cmd" value="&#9888; Delete">
	&nbsp; <input type=reset value="Reset">
	&nbsp; <input type=submit name="cmd" value="Insert">
	&nbsp; <input type=submit name="cmd" value="Update">
	&nbsp; <input type=button value=Search onclick="window.location.href='./jpsearch.php';">
	<br><br>
	<table>
<?php
function phoneLabelx(array $r, array $phoneLabel, int $label) : string {
	$phoneLabelText = 'PhoneLabel' . $label;
	$s = '<select name='.$phoneLabelText.' id='.$phoneLabelText.">\n";
	foreach ($phoneLabel as $key => $value) {
		$s .= sprintf("\t\t\t<option value=\"%s\"%s>%s</option>\n", $key, $key == $r[$phoneLabelText] ? ' selected' : '', $value);
	}
	return $s .= "\t\t\t</select>";
}
function category(array $cat, string $catSel) : string {
	$s = "<select id=Category name=Category>>\n";
	foreach ($cat as $key => $value) {
		$s .= sprintf("\t\t\t<option value=\"%s\"%s>%s</option>\n", $key, $key == $catSel ? ' selected' : '', $value);
	}
	return $s .= "\t\t\t</select>";
}
function queryLabel(SQLite3 $db, string $id, string $label, string $table) : array {
	$qLabel = $db->query("select $id, $label from $table order by $id");
	$a = array();
	while (($p = $qLabel->fetchArray()) !== false) {
		$a[$p[$id]] = $p[$label];
	}
	return $a;
}
function telephoneCall(array $r, string $label) : string {
	$phone = 'Phone' . $label;
	$phoneLabelText = 'PhoneLabel' . $label;
	if (!isset($r[$phone]) || strlen($r[$phone]) === 0) return "";	// nothing to call
	return ($r[$phoneLabelText] == 4) ?	// is it an e-mail?
		" &nbsp; <a href=\"mailto:$r[$phone]\">&#x2709;</a>"
		: " &nbsp; <a href=\"tel:$r[$phone]\">&#9742;</a>";
}

if ($tablenr === 1) {	// Addr
	$phoneLabel = queryLabel($db,'Id','Label','PhoneLabel');
	$addrLabel = queryLabel($db,'Id','Label','AddrLabel');
	$addrCategory = queryLabel($db,'Id','Label','AddrCategory');
?>
		<tr><td>Id</td><td><input type=text id=Id name=Id disabled value="<?=$r['Id']?>"></td></tr>
		<tr><td>Category</td><td><?=category($addrCategory,$r['Category'])?></td></tr>
		<tr><td>Private</td><td><input type=checkbox id=Private name=Private value=1 <?=($r['Private'] == 1) ? 'checked':''?>></td></tr>
		<tr><td><?=$addrLabel[0]?></td><td><input type=text id=Lastname name=Lastname value="<?=$r['Lastname']?>"></td></tr>
		<tr><td><?=$addrLabel[1]?></td><td><input type=text id=Firstname name=Firstname value="<?=$r['Firstname']?>"></td></tr>
		<tr><td><?=$addrLabel[13]?></td><td><textarea id=Title name=Title cols=64><?=$r['Title']?></textarea></td></tr>
		<tr><td><?=$addrLabel[2]?></td><td><input type=text id=Company name=Company value="<?=$r['Company']?>"></td></tr>
		<tr><td><?=phoneLabelx($r,$phoneLabel,1)?></td><td><input type=text id=Phone1 name=Phone1 value="<?=$r['Phone1']?>"><?=telephoneCall($r,1)?></td></tr>
		<tr><td><?=phoneLabelx($r,$phoneLabel,2)?></td><td><input type=text id=Phone2 name=Phone2 value="<?=$r['Phone2']?>"><?=telephoneCall($r,2)?></td></tr>
		<tr><td><?=phoneLabelx($r,$phoneLabel,3)?></td><td><input type=text id=Phone3 name=Phone3 value="<?=$r['Phone3']?>"><?=telephoneCall($r,3)?></td></tr>
		<tr><td><?=phoneLabelx($r,$phoneLabel,4)?></td><td><input type=text id=Phone4 name=Phone4 value="<?=$r['Phone4']?>"><?=telephoneCall($r,4)?></td></tr>
		<tr><td><?=phoneLabelx($r,$phoneLabel,5)?></td><td><input type=text id=Phone5 name=Phone5 value="<?=$r['Phone5']?>"><?=telephoneCall($r,5)?></td></tr>
		<tr><td><?=$addrLabel[8]?></td><td><textarea id=Address name=Address rows=7 cols=64><?=$r['Address']?></textarea></td></tr>
		<tr><td><?=$addrLabel[9]?></td><td><input type=text id=City name=City value="<?=$r['City']?>"></td></tr>
		<tr><td><?=$addrLabel[10]?></td><td><input type=text id=State name=State value="<?=$r['State']?>"></td></tr>
		<tr><td><?=$addrLabel[11]?></td><td><input type=text id=Zip name=Zip value="<?=$r['Zip']?>"></td></tr>
		<tr><td><?=$addrLabel[12]?></td><td><input type=text id=Country name=Country value="<?=$r['Country']?>"></td></tr>
		<tr><td><?=$addrLabel[14]?></td><td><input type=text id=Custom1 name=Custom1 value="<?=$r['Custom1']?>"></td></tr>
		<tr><td><?=$addrLabel[15]?></td><td><textarea id=Custom2 name=Custom2 rows=3 cols=64><?=$r['Custom2']?></textarea></td></tr>
		<tr><td><?=$addrLabel[16]?></td><td><input type=text id=Custom3 name=Custom3 value="<?=$r['Custom3']?>"></td></tr>
		<tr><td><?=$addrLabel[17]?></td><td><input type=text id=Custom4 name=Custom4 value="<?=$r['Custom4']?>"></td></tr>
		<tr><td><?=$addrLabel[18]?></td><td><textarea id=Note name=Note rows=38 cols=64><?=$r['Note']?></textarea></td></tr>
<?php
} else if ($tablenr === 2) {	// Datebook
?>
		<tr><td>Id</td><td><input type=text id=Id name=Id disabled value="<?=$r['Id']?>"></td></tr>
		<tr><td>Private</td><td><input type=checkbox id=Private name=Private value=1 <?=($r['Private'] == 1) ? 'checked':''?>></td></tr>
		<tr><td>Begin</td><td><input type=text id=Begin name=Begin value="<?=$r['Begin']?>"></td></tr>
		<tr><td>End</td><td><input type=text id=End name=End value="<?=$r['End']?>"></td></tr>
		<tr><td>RepeatFreq</td><td><input type=text id=RepeatFreq name=RepeatFreq value="<?=$r['RepeatFreq']?>"></td></tr>
		<tr><td>Description</td><td><textarea id=Description name=Description rows=8 cols=64><?=$r['Description']?></textarea></td></tr>
		<tr><td>Note</td><td><textarea id=Note name=Note rows=16 cols=64><?=$r['Note']?></textarea></td></tr>
<?php
} else if ($tablenr === 3) {	// Memo
	$memoCategory = queryLabel($db,'Id','Label','MemoCategory');
?>
		<tr><td>Id</td><td><input type=text id=Id name=Id disabled value="<?=$r['Id']?>"></td></tr>
		<tr><td>Private</td><td><input type=checkbox id=Private name=Private value=1 <?=($r['Private'] == 1) ? 'checked':''?>></td></tr>
		<tr><td>Category</td><td><?=category($memoCategory,$r['Category'])?></td></tr>
		<tr><td>Text</td><td><textarea id=Text name=Text rows=256 cols=64><?=$r['Text']?></textarea></td></tr>
<?php
} else if ($tablenr === 4) {	// ToDo
	$toDoCategory = queryLabel($db,'Id','Label','ToDoCategory');
?>
		<tr><td>Id</td><td><input type=text id=Id name=Id disabled value="<?=$r['Id']?>"></td></tr>
		<tr><td>Category</td><td><?=category($toDoCategory,$r['Category'])?></td></tr>
		<tr><td>Private</td><td><input type=checkbox id=Private name=Private value=1 <?=($r['Private'] == 1) ? 'checked':''?>></td></tr>
		<tr><td>Due</td><td><input type=text id=Due name=Due value="<?=$r['Due']?>"></td></tr>
		<tr><td>Priority</td><td><input type=number min=1 max=5 id=Priority name=Priority value="<?=$r['Priority']?>"></td></tr>
		<tr><td>Complete</td><td><input type=number min=0 max=1 id=Complete name=Complete value="<?=$r['Complete']?>"></td></tr>
		<tr><td>Description</td><td><textarea id=Description name=Description rows=9 cols=64><?=$r['Description']?></textarea></td></tr>
		<tr><td>Note</td><td><textarea id=Note name=Note rows=9 cols=64><?=$r['Note']?></textarea></td></tr>
<?php
} else if ($tablenr === 5) {	// Expense
	$expenseCategory = queryLabel($db,'Id','Label','ExpenseCategory');
	$expenseType = queryLabel($db,'Id','Label','ExpenseType');
	$expensePayment = queryLabel($db,'Id','Label','ExpensePayment');
	$expenseCurrency = queryLabel($db,'Id','Label','ExpenseCurrency');
?>
		<tr><td>Id</td><td><input type=text id=Id name=Id disabled value="<?=$r['Id']?>"></td></tr>
		<tr><td>Category</td><td><?=category($expenseCategory,$r['Category'])?></td></tr>
		<tr><td>Date</td><td><input type=text id=Date name=Begin value="<?=$r['Date']?>"></td></tr>
		<tr><td>Type</td><td><?=category($expenseType,$r['Type'])?></td></tr>
		<tr><td>Payment</td><td><?=category($expensePayment,$r['Payment'])?></td></tr>
		<tr><td>Currency</td><td><?=category($expenseCurrency,$r['Currency'])?></td></tr>
		<tr><td>Amount</td><td><input type=number id=Amount name=Amount value="<?=$r['Amount']?>"></td></tr>
		<tr><td>Vendor</td><td><textarea id=Vendor name=Vendor cols=64><?=$r['Vendor']?></textarea></td></tr>
		<tr><td>City</td><td><input type=text id=City name=City value="<?=$r['City']?>"></td></tr>
		<tr><td>Attendees</td><td><textarea id=Attendees name=Attendees cols=64><?=$r['Attendees']?></textarea></td></tr>
		<tr><td>Note</td><td><textarea id=Note name=Note rows=9 cols=64><?=$r['Note']?></textarea></td></tr>
<?php
}
?>
	</table>
</form>
<?php
L1:
?>
<script>
function clearFrm() {
	//alert("clearFrm()");
	var frm = document.getElementById('jpedit');
	var nelem = frm.elements.length;
	for (i=0; i<nelem; ++i) {
		//alert("i="+i+", type="+frm.elements[i].type+", name="+frm.elements[i].name+", value="+frm.elements[i].value);
		if (frm.elements[i].name == "Id") continue;
		else if (frm.elements[i].type == "text" || frm.elements[i].type == "textarea")
			frm.elements[i].value = "";
		else if (frm.elements[i].type == "checkbox")
			frm.elements[i].checked = false;
	}
}
</script>
</body>
</html>


