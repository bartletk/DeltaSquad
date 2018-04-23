<!DOCTYPE html>

<?php
	$page = "index.php";

	if (!isset($sem)){
	$sem=0;
	}
	if (!isset($studentCWID)){
	$studentCWID=0;
	}
	if (!isset($rm)){
	$rm=0;
	}
	if (!isset($cm) || !$session->isAdmin()){
	$cm=0;
	}
	include "include/start.php";
	$canview = true;
	//if no access, then kick them out!

	if ($script) {
		include "modules/".$script;
		} else {
		include ('top_header.php');	
	}
	
	?>
	
<script>
$('select[name="option"]').change(function() {
    window.location.replace($(this).val());
});
</script>
<?php
	include('footer.php');
?>
<meta http-equiv="refresh" content="60">
</body>
</html>
