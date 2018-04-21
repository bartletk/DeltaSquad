			jQuery(function($){
				<?php
					if(isset($_GET['hash'])){
						$hash = $_GET['hash'];
						} else {
						$hash = '';
					}
				?>
				jp_hash = ('<?php echo $hash; ?>'.length)?'<?php echo $hash; ?>':window.location.hash;
				if(jp_hash){
					$.ajax({
						type: "POST",
						url: 'process.php',
						data: 'login_with_hash=1&hash='+jp_hash,
						success: function(msg){
							if(msg){
								alert(msg);
								window.location.href = "main.php";
								} else {
								alert("Invalid Hash");
							}
						}
					});
				}
			});