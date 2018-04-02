<?php
include "include/start.php";
$page_title = "Delete Event";
$id = $_REQUEST["id"];
$edit=false;


if (!$_SESSION["user_id"]) {
	mysql_close($link);
	header("Location: ".$path."login.php?return_to=".$PHP_SELF);
} elseif (!$_REQUEST["id"]) {
	mysql_close($link);
	header("Location: ".$path."index.php?msg="."No Event Selected to Edit");
} else {
	$q = "SELECT * from events where event_id =".$_REQUEST["id"];
	$query = mysql_query($q);
	$row = mysql_fetch_array($query);
		
	if (!$query) $msg .= "Database Error : ".$q;

	$squery = mysql_query("SELECT add_categories from users where user_id = ".$_SESSION["user_id"]." limit 1");
	$srow = mysql_fetch_row($squery);
	if ($srow[0] == 1) {
		$edit = true;
	} else {
		if ($row["user_id"] != $_SESSION["user_id"]) {
			$q = "select moderate from users_to_categories where category_id = ".$row[0]." and user_id = ".$_SESSION["user_id"];
			$mod = mysql_result(mysql_query($q),0,0);
			if ($mod < 2) {
				mysql_close($link);
				header("Location: ".$path."index.php?msg=Not Authorized to Edit Events in this Category]);
			}
		}
	}
		include "header.php";
	$q = "SELECT * from events where event_id = ".$id." limit 1";
	$query = mysql_query($q);
	if (!$query) echo "Database Error : ".$q;
	else {
		$cat = mysql_fetch_array($query);
		
?>
<h3><?php echo "Delete"; ?>: <?php echo strip_tags($cat["title"]); ?>?</h3>
<p class="warning"><?php echo "Are you sure you want to delete this event?"; ?></p>
<form action="admin_actions.php" method="post">
<?php include "include/hidden_fields.php"; ?>
<input type="hidden" name="id" id="id" value="<?php echo $cat["event_id"]; ?>">
<p><input type="submit" name="mode" value="<?php echo $lang["delete_event"]; ?>"></p>
<?php
	}
}

?>