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
