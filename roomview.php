
<?php
	include("top_header.php");
					echo '
					<div class="input-field col s12">
					<select name="rms" class="drop wid">
					<option value="" disabled selected>Pick a room</option>';
					$q = "select * from ".TBL_ROOMS;
					$result = mysql_query($q);
					for ($i=0; $i < mysql_num_rows($result); $i++){
						$num = mysql_result($result,$i,"room_number");
						$desc = mysql_result($result,$i,"description");
						echo '<option value="index.php?rm='.$num.'">'.$num.' - '.$desc.'</option>';
					}
					echo '</select></div>';	
?>
<script>
$('select[name="rms"]').change(function() {
    window.location.replace($(this).val());
});
</script>
<?php
	include "footer.php";
	?>
	
	
