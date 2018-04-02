<?php
	 include("top_header.php");
	 $page = "roomview.php";
	
	 global $database;	
	 
	 
							$q = "SELECT * FROM ".TBL_ROOMS;
							$result = $database->query($q);
							$num_rows = mysql_numrows($result);
							for($i=0; $i<$num_rows; $i++){
								$num = mysql_result($result,$i,"room_number");
								$desc = mysql_result($result,$i,"description");
								echo '<div class="room">
	<label>$num</label>
	<br> <label>$desc</label>
	<table width="500px">
		<tr data-time="00:00:00">
			<td style="width: 42px; height:20px;"><span>12am</span></td>
			<td></td>
		</tr>
		<tr data-time="00:30:00" >
			<td style="width: 42px; height:20px;"></td>
			<td ></td>
		</tr>
		
		<tr data-time="01:00:00">
			<td style="width: 42px; height:20px;"><span>1am</span></td>
			<td></td>
		</tr>
		<tr data-time="01:30:00" >
			<td style="width: 42px; height:20px;"></td>
			<td ></td>
		</tr>
		
		<tr data-time="02:00:00">
			<td style="width: 42px; height:20px;"><span>2am</span></td>
			<td></td>
		</tr>
		<tr data-time="02:30:00" >
			<td style="width: 42px; height:20px;"></td>
			<td ></td>
		</tr>
		
		<tr data-time="03:00:00">
			<td style="width: 42px; height:20px;"><span>3am</span></td>
			<td></td>
		</tr>
		<tr data-time="03:30:00" >
			<td style="width: 42px; height:20px;"></td>
			<td ></td>
		</tr>
		
		<tr data-time="04:00:00">
			<td style="width: 42px; height:20px;"><span>4am</span></td>
			<td></td>
		</tr>
		<tr data-time="04:30:00" >
			<td style="width: 42px; height:20px;"></td>
			<td ></td>
		</tr>
		
		<tr data-time="05:00:00">
			<td style="width: 42px; height:20px;"><span>5am</span></td>
			<td></td>
		</tr>
		<tr data-time="05:30:00" >
			<td style="width: 42px; height:20px;"></td>
			<td ></td>
		</tr>
		
		<tr data-time="06:00:00">
			<td style="width: 42px; height:20px;"><span>6am</span></td>
			<td></td>
		</tr>
		<tr data-time="06:30:00" >
			<td style="width: 42px; height:20px;"></td>
			<td ></td>
		</tr>
		
		<tr data-time="06:00:00">
			<td style="width: 42px; height:20px;"><span>7am</span></td>
			<td></td>
		</tr>
		<tr data-time="07:30:00" >
			<td style="width: 42px; height:20px;"></td>
			<td ></td>
		</tr>
		
		<tr data-time="08:00:00">
			<td style="width: 42px; height:20px;"><span>8am</span></td>
			<td></td>
		</tr>
		<tr data-time="08:30:00" >
			<td style="width: 42px; height:20px;"></td>
			<td ></td>
		</tr>
		
		<tr data-time="09:00:00">
			<td style="width: 42px; height:20px;"><span>9am</span></td>
			<td></td>
		</tr>
		<tr data-time="09:30:00" >
			<td style="width: 42px; height:20px;"></td>
			<td ></td>
		</tr>
		
		<tr data-time="10:00:00">
			<td style="width: 42px; height:20px;"><span>10am</span></td>
			<td></td>
		</tr>
		<tr data-time="10:30:00" >
			<td style="width: 42px; height:20px;"></td>
			<td ></td>
		</tr>
		
		<tr data-time="11:00:00">
			<td style="width: 42px; height:20px;"><span>11am</span></td>
			<td></td>
		</tr>
		<tr data-time="11:30:00" >
			<td style="width: 42px; height:20px;"></td>
			<td ></td>
		</tr>
		
		<tr data-time="12:00:00">
			<td style="width: 42px; height:20px;"><span>12pm</span></td>
			<td></td>
		</tr>
		<tr data-time="12:30:00" >
			<td style="width: 42px; height:20px;"></td>
			<td ></td>
		</tr>
		
		<tr data-time="13:00:00">
			<td style="width: 42px; height:20px;"><span>1pm</span></td>
			<td></td>
		</tr>
		<tr data-time="13:30:00" >
			<td style="width: 42px; height:20px;"></td>
			<td ></td>
		</tr>
		
		<tr data-time="14:00:00">
			<td style="width: 42px; height:20px;"><span>2pm</span></td>
			<td></td>
		</tr>
		<tr data-time="14:30:00" >
			<td style="width: 42px; height:20px;"></td>
			<td ></td>
		</tr>
		
		<tr data-time="15:00:00">
			<td style="width: 42px; height:20px;"><span>3pm</span></td>
			<td></td>
		</tr>
		<tr data-time="15:30:00" >
			<td style="width: 42px; height:20px;"></td>
			<td ></td>
		</tr>
		
		<tr data-time="16:00:00">
			<td style="width: 42px; height:20px;"><span>4pm</span></td>
			<td></td>
		</tr>
		<tr data-time="16:30:00" >
			<td style="width: 42px; height:20px;"></td>
			<td ></td>
		</tr>
		
		<tr data-time="17:00:00">
			<td style="width: 42px; height:20px;"><span>5pm</span></td>
			<td></td>
		</tr>
		<tr data-time="17:30:00" >
			<td style="width: 42px; height:20px;"></td>
			<td ></td>
		</tr>
		
		<tr data-time="18:00:00">
			<td style="width: 42px; height:20px;"><span>6pm</span></td>
			<td></td>
		</tr>
		<tr data-time="18:30:00" >
			<td style="width: 42px; height:20px;"></td>
			<td ></td>
		</tr>
		
		<tr data-time="19:00:00">
			<td style="width: 42px; height:20px;"><span>7pm</span></td>
			<td></td>
		</tr>
		<tr data-time="19:30:00" >
			<td style="width: 42px; height:20px;"></td>
			<td ></td>
		</tr>
		
		<tr data-time="20:00:00">
			<td style="width: 42px; height:20px;"><span>8pm</span></td>
			<td></td>
		</tr>
		<tr data-time="20:30:00" >
			<td style="width: 42px; height:20px;"></td>
			<td ></td>
		</tr>
		
		<tr data-time="21:00:00">
			<td style="width: 42px; height:20px;"><span>9pm</span></td>
			<td></td>
		</tr>
		<tr data-time="21:30:00" >
			<td style="width: 42px; height:20px;"></td>
			<td ></td>
		</tr>
		
		<tr data-time="22:00:00">
			<td style="width: 42px; height:20px;"><span>10pm</span></td>
			<td></td>
		</tr>
		<tr data-time="22:30:00" >
			<td style="width: 42px; height:20px;"></td>
			<td ></td>
		</tr>
		
		<tr data-time="23:00:00">
			<td style="width: 42px; height:20px;"><span>11pm</span></td>
			<td></td>
		</tr>
		<tr data-time="23:30:00" >
			<td style="width: 42px; height:20px;"></td>
			<td ></td>
		</tr>
	</table>
</div>';
							}
						
	?>

<?php
	include "footer.php";
	?>