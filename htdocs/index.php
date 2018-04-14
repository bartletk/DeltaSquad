<!DOCTYPE html>

<?php
	$page = "index.php";

	
	$sem=0;
	
	include "include/start.php";
	$canview = true;
	//if no access, then kick them out!

	if ($script) {
		include "modules/".$script;
		} else {
		include ('top_header.php');	
	}
	
echo "Calendar Views"; ?>:&nbsp;&nbsp;&nbsp;
<?php
	
	$q = "SELECT module_id, link_name from modules where active = 1 order by sequence";
	$query = mysql_query($q);
	if (!$query) $msg .= "Database Error : ".$q;
	else {
		$i = false;
		while($row = mysql_fetch_row($query)) {
			if ($i == true) echo " | ";
			echo "<a href=\"index.php?o=".$row[0]."&c=".$c."&m=".$m."&a=".$a."&y=".$y."&w=".$w."&sem=".$sem."\"";
			if ($o == $row[0]) echo " class=\"selected\"";
			echo ">".$row[1]."</a>";
			$i = true;
		}
	}
	
	echo '
	<select name="option">
	<option value="index.php?o='.$o.'&c='.$c.'&m='.$m.'&a='.$a.'&y='.$y.'&w='.$w.'&sem=">View My Courses</option>
	<option value="index.php?o='.$o.'&c='.$c.'&m='.$m.'&a='.$a.'&y='.$y.'&w='.$w.'&sem=1">View Semester 1 </option>
	<option value="index.php?o='.$o.'&c='.$c.'&m='.$m.'&a='.$a.'&y='.$y.'&w='.$w.'&sem=2">View Semester 2 </option>
	<option value="index.php?o='.$o.'&c='.$c.'&m='.$m.'&a='.$a.'&y='.$y.'&w='.$w.'&sem=3">View Semester 3 </option>
	<option value="index.php?o='.$o.'&c='.$c.'&m='.$m.'&a='.$a.'&y='.$y.'&w='.$w.'&sem=4">View Semester 4 </option>
	<option value="index.php?o='.$o.'&c='.$c.'&m='.$m.'&a='.$a.'&y='.$y.'&w='.$w.'&sem=5">View Semester 5 </option>
	</select>
	';
	
	?>
	
<script>
$('select[name="option"]').change(function() {
    window.location.replace($(this).val());
});
</script>
<?php
	include('footer.php');
?>

</body>
</html>
