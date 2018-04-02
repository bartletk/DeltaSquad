<?php


include "include/start.php";
if(get_magic_quotes_gpc()) {
  	$id = mysql_real_escape_string(stripslashes($_REQUEST["id"]));
	$dir = mysql_real_escape_string(stripslashes($_REQUEST["dir"]));
	$file = mysql_real_escape_string(stripslashes($_REQUEST["file"]));

} else {
  	$id = mysql_real_escape_string($_REQUEST["id"]);
	$dir = mysql_real_escape_string(stripslashes($_REQUEST["dir"]));
	$file = mysql_real_escape_string(stripslashes($_REQUEST["file"]));
}

$edit = false;
function updateProfile($id) {
	global $table_prefix, $edit, $link, $common_get, $lang;
	if(get_magic_quotes_gpc()) {
    	$email = mysql_real_escape_string(stripslashes($_POST["email"]));
		$new_password = mysql_real_escape_string(stripslashes($_POST["new_password"]));
		
     } else {
       	$email = mysql_real_escape_string($_POST["email"]);
		$new_password = mysql_real_escape_string($_POST["new_password"]);
     }
	
	if ($_POST["new_password"]) {
		$password = md5($_POST["new_password"]);
		$query = mysql_query("UPDATE users set password = '".$password."'WHERE user_id ='".$id."'");
		if (!$query) $msg = Database Error, Password not updated;
		
	}
	if (!$edit) {
		$query = mysql_query("UPDATE users set email = '".$_POST["email"]."' WHERE user_id =".$id);
		if (!$query) {
			$msg .= Database Error, Password not updated;
		} else {
			$msg .= Information Updated;
		}
	} else {
		
		$view = $_POST["view"] ? 1 : 0;
		$post = $_POST["post"] ? 1 : 0;
		$add_users = $_POST["add_users"] ? 1 : 0;
		$add_groups = $_POST["add_users"] ? 1 : 0;
		$add_categories = $_POST["add_categories"] ? 1 : 0;
		$query = mysql_query("UPDATE users set email = '".$_POST["email"]."', view = ".$view.", post = ".$post.", add_users = ".$add_users.", add_groups = ".$add_groups.", add_categories = ".$add_categories." WHERE user_id =".$id);
		if (!$query) $msg .= Database Error, User not updated;
	
		// clean out current user to categories table of user
		$query = mysql_query("DELETE from users_to_categories where user_id = ".$id);
		
		//now add back the ones the user has access to
		if ($_POST["category"]) {
			while (list($key) = each($_POST["category"])) {
				$mod = $_POST["cpost"][$key] == 2 ? 2 : 1;
				if ($_POST["cmoderate"][$key] == 3) $mod = 3;
			
				$query = mysql_query("INSERT INTO users_to_categories (user_id, category_id, moderate) values (".$id.", ".$key.", ".$mod.")");
			}
		}
		
		// clean out current user to groups table of user
		$query = mysql_query("DELETE from users_to_groups where user_id = ".$id);
		
		//now add back the ones the user has access to
		if ($_POST["group"]) {
			while (list($key) = each($_POST["group"])) {
				$mod = $_POST["gpost"][$key] == 2 ? 2 : 1;
				if ($_POST["gmoderate"][$key] == 3) $mod = 3;
				//$subscribe = $_POST["gsubscribe"][$key] == 1 ? 1 : 0;
				$subscribe = 0;
				$query = mysql_query("INSERT INTO users_to_groups (user_id, group_id, moderate, subscribe) values (".$id.", ".$key.", ".$mod.", ".$subscribe.")");
				
			}
		}
		$msg .= User Updated;
		
		
	}
	
	mysql_close($link);
	header("Location: user_profile.php?msg=".$msg."&id=".$id."&".$common_get);
}

function addProfile() {
	global $table_prefix, $edit, $link, $common_get, $lang;
	if(get_magic_quotes_gpc()) {
    	$email = mysql_real_escape_string(stripslashes($_POST["email"]));
		$new_password = mysql_real_escape_string(stripslashes($_POST["new_password"]));
		
     } else {
       	$email = mysql_real_escape_string($_POST["email"]);
		$new_password = mysql_real_escape_string($_POST["new_password"]);
     }
	
	if (!$edit) {
		$msg = You are not authorized to add users;
	} else {
		if ((!$new_password) || (!$email)) {
				$msg = Username and Password are required;
		} else {
			$query = mysql_query("SELECT email from users where email = '".$email."'");
			if (mysql_num_rows($query) > 0) {
				$msg = E-mail address already exists;
			} else {
				$password = md5($new_password);
				$query = mysql_query("INSERT INTO users (email, password) values ('".$email."', '".$password."')");
				if (!$query) $msg .= Database Error, User not updated;
				$id = mysql_insert_id();
				$view = $_POST["view"] ? 1 : 0;
				$post = $_POST["post"] ? 1 : 0;
				$add_users = $_POST["add_users"] ? 1 : 0;
				$add_categories = $_POST["add_categories"] ? 1 : 0;
				$add_groups = $_POST["add_groups"] ? 1 : 0;
				$query = mysql_query("UPDATE users set add_users = ".$add_users.", add_categories = ".$add_categories.", view = ".$view.", post = ".$post.", add_groups = ".$add_groups." WHERE user_id =".$id);
				if (!$query) $msg .= Database Error, User not updated;
		
				//now add back the ones the user has access to
				if ($_POST["category"]) {
					while (list($key) = each($_POST["category"])) {
						$mod = 0;
						if ($_POST["cmoderate"][$key]) $mod = 3;
						elseif ($_POST["cpost"][$key]) $mod = 2;
						else $mod = 1;
						$query = mysql_query("INSERT INTO users_to_categories (user_id, category_id, moderate) values (".$id.", ".$key.", ".$mod.")");
					}
				}
				if ($_POST["group"]) {
					while (list($key) = each($_POST["group"])) {
						$sub = 0;
						$mod = 0;
						if ($_POST["gmoderate"][$key]) $mod = 3;
						elseif ($_POST["gpost"][$key]) $mod = 2;
						else $mod = 1;
						$sub = $_POST["gsubscribe"][$key] == 1 ? 1 : 0;
						$query = mysql_query("INSERT INTO users_to_groups (user_id, group_id, moderate, subscribe) values (".$id.", ".$key.", ".$mod.", ".$sub.")");
					}
				}
			}
		}
	}
	if (!$msg) $msg = User Updated;
	mysql_close($link);
	header("Location: edit_users.php?msg=".$msg."&".$common_get);
}

function addCategory() {
	global $table_prefix, $link, $edit_categories, $common_get;
	if (!$edit_categories) {
		$msg = "You are not authorized to edit categories.";
	} else {
		if(get_magic_quotes_gpc()) {
            $name = mysql_real_escape_string(stripslashes($_POST["name"]));
			$sub_of = mysql_real_escape_string(stripslashes($_POST["sub_of"]));
			$sequence = mysql_real_escape_string(stripslashes($_POST["sequence"]));
			$description = mysql_real_escape_string(stripslashes($_POST["description"]));
			$color = mysql_real_escape_string(stripslashes($_POST["color"]));
			$background = mysql_real_escape_string(stripslashes($_POST["background"]));
        } else {
          	$name = mysql_real_escape_string($_POST["name"]);
			$sub_of = mysql_real_escape_string($_POST["sub_of"]);
			$sequence = mysql_real_escape_string($_POST["sequence"]);
			$description = mysql_real_escape_string($_POST["description"]);
			$color = mysql_real_escape_string($_POST["color"]);
			$background = mysql_real_escape_string($_POST["background"]);
        }
		$q = "INSERT INTO categories (name, sub_of, sequence, description, color, background) values ('".$name."', '".$sub_of."', '".$sequence."', '".$description."', '".$color."', '".$background."')";
		$query = mysql_query($q);
		if (!$query) $msg = Database Error, Category Not Added;
		else $msg = Category Added;
	}
	mysql_close($link);
	header("Location: edit_categories.php?msg=".$msg."&".$common_get);
}
function editCategory($id) {
	global $table_prefix, $link, $edit_categories, $common_get, $lang;
	if (!$edit_categories) {
		$msg = "You are not authorized to edit categories.";
	} else {
		if(get_magic_quotes_gpc()) {
            $name = mysql_real_escape_string(stripslashes($_POST["name"]));
			$sub_of = mysql_real_escape_string(stripslashes($_POST["sub_of"]));
			$sequence = mysql_real_escape_string(stripslashes($_POST["sequence"]));
			$description = mysql_real_escape_string(stripslashes($_POST["description"]));
			$color = mysql_real_escape_string(stripslashes($_POST["color"]));
			$background = mysql_real_escape_string(stripslashes($_POST["background"]));
        } else {
          	$name = mysql_real_escape_string($_POST["name"]);
			$sub_of = mysql_real_escape_string($_POST["sub_of"]);
			$sequence = mysql_real_escape_string($_POST["sequence"]);
			$description = mysql_real_escape_string($_POST["description"]);
			$color = mysql_real_escape_string($_POST["color"]);
			$background = mysql_real_escape_string($_POST["background"]);
        }
		$query = mysql_query("UPDATE categories set name = '".$name."', sub_of = '".$sub_of."', sequence = '".$sequence."', description = '".$description."', color = '".$color."', background = '".$background."' where category_id =".$id);
		if (!$query) $msg = Database Error, Category Not Added;
		else $msg = Category Updated;
	}
	mysql_close($link);
	header("Location: edit_categories.php?msg=".$msg."&".$common_get);
}

function deleteCategory($id) {
	global $table_prefix, $link, $edit_categories, $common_get, $lang;
	if (!$edit_categories) {
		$msg = "You are not authorized to edit categories.";
	} else {
		if(get_magic_quotes_gpc()) {
            $sub_of = mysql_real_escape_string(stripslashes($_POST["sub_of"]));
        } else {
          	$sub_of = mysql_real_escape_string($_POST["sub_of"]);
        }
		$query = mysql_query("UPDATE categories set sub_of = '".$sub_of."' where sub_of =".$id);
		if (!$query) $msg = Database Error, Dependant Categories Not Updated;
		$query = mysql_query("UPDATE events set category_id = '".$sub_of."' where category_id =".$id);
		if (!$query) $msg .= Database Error, Dependant Events Not Updated ;
		$query = mysql_query("DELETE from users_to_categories where category_id = ".$id);
		if (!$query) $msg .= Database Error, User Table Not Updated;
		$query = mysql_query("DELETE from categories where category_id = ".$id);
		if (!$query) $msg .= Database Error, Category Not Deleted;
		if (!$msg) $msg = Category Deleted;
	}
	mysql_close($link);
	header("Location: edit_categories.php?msg=".$msg."&".$common_get);
}

function addGroup() {
	global $table_prefix, $link, $edit_groups, $common_get;
	if (!$edit_groups) {
		$msg = "You are not authorized to edit groups.";
	} else {
		if(get_magic_quotes_gpc()) {
            $name = mysql_real_escape_string(stripslashes($_POST["name"]));
			$sub_of = mysql_real_escape_string(stripslashes($_POST["sub_of"]));
			$sequence = mysql_real_escape_string(stripslashes($_POST["sequence"]));
        } else {
          	$name = mysql_real_escape_string($_POST["name"]);
			$sub_of = mysql_real_escape_string($_POST["sub_of"]);
			$sequence = mysql_real_escape_string($_POST["sequence"]);
        }
		$q = "INSERT INTO groups (name, sub_of, sequence) values ('".$name."', '".$sub_of."', '".$sequence."')";
		$query = mysql_query($q);
		if (!$query) $msg = $lang["database_error_group_not_updated"];
		else $msg = Group Added;
	}
	mysql_close($link);
	header("Location: edit_groups.php?msg=".$msg."&".$common_get);
}
function editGroup($id) {
	global $table_prefix, $link, $edit_groups, $common_get, $lang;
	if (!$edit_groups) {
		$msg = "You are not authorized to edit groups.";
	} else {
		if(get_magic_quotes_gpc()) {
            $name = mysql_real_escape_string(stripslashes($_POST["name"]));
			$sub_of = mysql_real_escape_string(stripslashes($_POST["sub_of"]));
			$sequence = mysql_real_escape_string(stripslashes($_POST["sequence"]));
        } else {
          	$name = mysql_real_escape_string($_POST["name"]);
			$sub_of = mysql_real_escape_string($_POST["sub_of"]);
			$sequence = mysql_real_escape_string($_POST["sequence"]);
        }
		$query = mysql_query("UPDATE groups set name = '".$name."', sub_of = '".$sub_of."', sequence = '".$sequence."' where group_id =".$id);
		if (!$query) $msg = Database Error, Category Not Added;
		else $msg = Group Updated;
	}
	mysql_close($link);
	header("Location: edit_groups.php?msg=".$msg."&".$common_get);
}

function deleteGroup($id) {
	global $table_prefix, $link, $edit_groups, $common_get, $lang;
	if (!$edit_groups) {
		$msg = "You are not authorized to edit groups.";
	} else {
		if(get_magic_quotes_gpc()) {
    		$sub_of = mysql_real_escape_string(stripslashes($_POST["sub_of"]));
		} else {
       		$sub_of = mysql_real_escape_string($_POST["sub_of"]);
		}
		$query = mysql_query("UPDATE groups set sub_of = '".$sub_of."' where sub_of =".$id);
		if (!$query) $msg = Database Error, Dependant Groups Not Updated;
		$query = mysql_query("UPDATE events set group_id = '".$sub_of."' where group_id =".$id);
		if (!$query) $msg .= Database Error, Dependant Events Not Updated ;
		$query = mysql_query("DELETE from users_to_groups where group_id = ".$id);
		if (!$query) $msg .= Database Error, User Table Not Updated;
		$query = mysql_query("DELETE from groups where group_id = ".$id);
		if (!$query) $msg .= Database Error,Group Not Deleted;
		if (!$msg) $msg = Group Deleted;
	}
	mysql_close($link);
	header("Location: edit_groups.php?msg=".$msg."&".$common_get);
}

function addLink() {
	global $table_prefix, $link, $common_get, $lang, $edit_categories;
	if (!$edit_categories) {
		$msg = "You are not authorized to edit links.";
	} else {
		if(get_magic_quotes_gpc()) {
            $company = mysql_real_escape_string(stripslashes($_POST["company"]));
			$address1 = mysql_real_escape_string(stripslashes($_POST["address1"]));
			$address2 = mysql_real_escape_string(stripslashes($_POST["address2"]));
			$city = mysql_real_escape_string(stripslashes($_POST["city"]));
			$state = mysql_real_escape_string(stripslashes($_POST["state"]));
			$zip = mysql_real_escape_string(stripslashes($_POST["zip"]));
			$phone = mysql_real_escape_string(stripslashes($_POST["phone"]));
			$fax = mysql_real_escape_string(stripslashes($_POST["fax"]));
			$contact = mysql_real_escape_string(stripslashes($_POST["contact"]));
			$email = mysql_real_escape_string(stripslashes($_POST["email"]));
			$url = mysql_real_escape_string(stripslashes($_POST["url"]));
			$description = mysql_real_escape_string(stripslashes($_POST["description"]));
        } else {
          	$company = mysql_real_escape_string($_POST["company"]);
			$address1 = mysql_real_escape_string($_POST["address1"]);
			$address2 = mysql_real_escape_string($_POST["address2"]);
			$city = mysql_real_escape_string($_POST["city"]);
			$state = mysql_real_escape_string($_POST["state"]);
			$zip = mysql_real_escape_string($_POST["zip"]);
			$phone = mysql_real_escape_string($_POST["phone"]);
			$fax = mysql_real_escape_string($_POST["fax"]);
			$contact = mysql_real_escape_string($_POST["contact"]);
			$email = mysql_real_escape_string($_POST["email"]);
			$url = mysql_real_escape_string($_POST["url"]);
			$description = mysql_real_escape_string($_POST["description"]);
        }
		
		
		$query = mysql_query("INSERT INTO links (company, address1, address2, city, state, zip, phone, fax, contact, email, url, description) values ('".$company."', '".$address1."', '".$address2."', '".$city."', '".$state."', '".$zip."', '".$phone."', '".$fax."', '".$contact."', '".$email."', '".$url."', '".$description."')");
		if (!$query) $msg = Database Error, Link Not Added;
		else $msg = Link Added;
		mysql_close($link);
	}
	header("Location: edit_links.php?msg=".$msg."&".$common_get);
}
function editLink($id) {
	global $table_prefix, $link, $common_get, $lang, $edit_categories;
	if (!$edit_categories) {
		$msg = "You are not authorized to edit links.";
	} else {
		if(get_magic_quotes_gpc()) {
            $company = mysql_real_escape_string(stripslashes($_POST["company"]));
			$address1 = mysql_real_escape_string(stripslashes($_POST["address1"]));
			$address2 = mysql_real_escape_string(stripslashes($_POST["address2"]));
			$city = mysql_real_escape_string(stripslashes($_POST["city"]));
			$state = mysql_real_escape_string(stripslashes($_POST["state"]));
			$zip = mysql_real_escape_string(stripslashes($_POST["zip"]));
			$phone = mysql_real_escape_string(stripslashes($_POST["phone"]));
			$fax = mysql_real_escape_string(stripslashes($_POST["fax"]));
			$contact = mysql_real_escape_string(stripslashes($_POST["contact"]));
			$email = mysql_real_escape_string(stripslashes($_POST["email"]));
			$url = mysql_real_escape_string(stripslashes($_POST["url"]));
			$description = mysql_real_escape_string(stripslashes($_POST["description"]));
        } else {
          	$company = mysql_real_escape_string($_POST["company"]);
			$address1 = mysql_real_escape_string($_POST["address1"]);
			$address2 = mysql_real_escape_string($_POST["address2"]);
			$city = mysql_real_escape_string($_POST["city"]);
			$state = mysql_real_escape_string($_POST["state"]);
			$zip = mysql_real_escape_string($_POST["zip"]);
			$phone = mysql_real_escape_string($_POST["phone"]);
			$fax = mysql_real_escape_string($_POST["fax"]);
			$contact = mysql_real_escape_string($_POST["contact"]);
			$email = mysql_real_escape_string($_POST["email"]);
			$url = mysql_real_escape_string($_POST["url"]);
			$description = mysql_real_escape_string($_POST["description"]);
        }
		$q = "UPDATE links set company = '".$company."', address1 = '".$address1."', address2 = '".$address2."', city = '".$city."', state = '".$state."', zip = '".$zip."', phone = '".$phone."', fax = '".$fax."', contact = '".$contact."', email = '".$email."', url ='".$url."', description ='".$description."' where link_id =".$id;
		$query = mysql_query($q);
		if (!$query) $msg = Database Error, Link not Updated;
		else $msg = Link Updated;
		mysql_close($link);
	}
	header("Location: edit_links.php?msg=".$msg."&".$common_get);
}

function deleteLink($id) {
	global $table_prefix, $link, $common_get, $lang, $edit_categories;
	if (!$edit_categories) {
		$msg = "You are not authorized to edit links.";
	} else {
		$query = mysql_query("UPDATE events set venue_id = 0 where venue_id =".$id);
		if (!$query) $msg .= Database Error, Dependant Events Not Updated;
		$query = mysql_query("UPDATE events set contact_id = 0 where contact_id =".$id);
		$query = mysql_query("DELETE from links where link_id = ".$id);
		if (!$query) $msg .= Database Error, Link Not Deleted;
		if (!$msg) $msg = Link Deleted;
		mysql_close($link);
	}
	header("Location: edit_links.php?msg=".$msg."&".$common_get);
}

function deleteUser($id) {
	global $table_prefix, $link, $common_get, $lang, $edit;
	if (!$edit) {
		$msg = You are not authorized to add users;
	} else {
		$sub_of = addslashes($_POST["sub_of"]);
		$query = mysql_query("DELETE from users_to_categories where user_id = ".$id);
		if (!$query) $msg .= Database Error, User Table Not Updated;
		$query = mysql_query("DELETE from users where user_id = ".$id);
		if (!$query) $msg .= Database Error, User Not Deleted;
		if (!$msg) $msg = User Deleted;
	}
	mysql_close($link);
	header("Location: edit_users.php?msg=".$msg."&".$common_get);
}

function deleteEvent($id) {
	global $table_prefix, $edit_categories, $link, $common_get, $lang, $c;
	if ($edit_categories) {
		$edit = true;
	} elseif ($row["user_id"] == $_SESSION["user_id"]) {
		$edit = true;
	} else {
		$q = "select moderate from users_to_categories where category_id = ".$c." and user_id = ".$_SESSION["user_id"];
		$mod = mysql_result(mysql_query($q),0,0);
		if ($mod >= 2) {
			$edit = true;
		}
	}
	if ($edit == true) {
		$query = mysql_query("DELETE from dates where event_id = ".$id);
		if (!$query) $msg .= Database Error, Dates Table Not Updated;
		$query = mysql_query("DELETE from events where event_id = ".$id);
		if (!$query) $msg .= Database Error, Event Not Deleted;
		if (!$msg) $msg = Event Deleted;
	} else {
		$msg = "Not Authorized to Edit Events in this Category";
	}
	mysql_close($link);
	header("Location: index.php?msg=".$msg."&".$common_get);
}

function updateModules() {
	global $table_prefix, $link, $edit, $common_get, $lang;
	if (!$edit) {
		$msg = You are not authorized to add users;
	} else {
		
		while (list($key, $val) = each($_POST["link_name"])) {
			if(get_magic_quotes_gpc()) {
            	$key = mysql_real_escape_string(stripslashes($key));
				$val = mysql_real_escape_string(stripslashes($val));
			
        	} else {
          		$key = mysql_real_escape_string($key);
				$val = mysql_real_escape_string($val);
			
       		}
			
			if ($_POST["delete"][$key]) {
				$del = mysql_query("delete from modules where module_id = ".$key);
			} else {
				
				if(get_magic_quotes_gpc()) {
	            	$active = mysql_real_escape_string(stripslashes($_POST["active"][$key]));
					$sequence = mysql_real_escape_string(stripslashes($_POST["sequence"][$key]));
					$year = mysql_real_escape_string(stripslashes($_POST["year"][$key]));
					$month = mysql_real_escape_string(stripslashes($_POST["month"][$key]));
					$week = mysql_real_escape_string(stripslashes($_POST["week"][$key]));
					$day = mysql_real_escape_string(stripslashes($_POST["day"][$key]));
				
	        	} else {
	          		$active = mysql_real_escape_string($_POST["active"][$key]);
					$sequence = mysql_real_escape_string($_POST["sequence"][$key]);
					$year = mysql_real_escape_string($_POST["year"][$key]);
					$month = mysql_real_escape_string($_POST["month"][$key]);
					$week = mysql_real_escape_string($_POST["week"][$key]);
					$day = mysql_real_escape_string($_POST["day"][$key]);
				
	       		}
				
				$update = mysql_query("update modules set link_name = '".$val."',  name = '".$_POST["name"][$key]."', active = '".$active."', sequence = '".$sequence."', year = '".$year."', month = '".$month."', week = '".$week."', day = '".$day."' where module_id =".$key);
			}
			$msg = Modules Updated;
		}
	}
	mysql_close($link);
	header("Location: modules.php?msg=".$msg."&".$common_get);
}

function installModule($dir,$file) {
	global $table_prefix, $link, $edit, $common_get, $lang;
	if (!$edit) {
		$msg = You are not authorized to add users;
	} else {
		if ($file) {
			$script = file_get_contents($dir."/".$file);
			$pa = xml_parser_create();
			xml_parse_into_struct($pa, $script, $vals, $index);
			xml_parser_free($pa);
			while (list($k, $v) = each($index)) {
				$mod[$k] = $vals[$index[$k][0]][value];
			}
			$query = mysql_query("INSERT INTO modules (link_name, name, script) values('".$mod["LINK_NAME"]."','".$mod["NAME"]."','".$file."')");
				
		} else {
			$msg = Modules Updated;
		}
		
	}
	mysql_close($link);
	header("Location: modules.php?msg=".$msg."&".$common_get);
}

				
function approve($event_id) {
	global $table_prefix, $lang, $edit_groups, $link;
	if (!$edit_groups) {
		$q = "select moderate from groups_to_events where event_id = ".$event_id." and user_id = ".$_SESSION["user_id"]."";
		$query = mysql_query($q);
		if (mysql_num_rows($query) > 0) {
			$mod = mysql_result($query,0,0);
			if ($mod > 2) $moderate = true;
		} else {
			$moderate = false;
		}
	} else {
		$moderate = true;
	}
	if ($moderate) {
		$sq = "update events set status_id = 4, quick_approve = NULL where event_id = '".$event_id."'";
		$squery = mysql_query($sq);
		if ($squery) {
			$msg = "Event Updated";
			include "include/notify.php";
			notify_group($event_id);
		}
		else $msg = "Database Error: $sq";
	} else {
		$msg = "Not Authorized to Approve Event";
	}
	header("Location: index.php?msg=".$msg."&".$common_get);

}



if (!$_SESSION["user_id"]) {
	mysql_close($link);
	header("Location: login.php");
} else {
	$query = mysql_query("SELECT add_users, add_categories, add_groups, post from users where user_id = ".$_SESSION["user_id"]." limit 1");
	$row = mysql_fetch_row($query);
	if ($row[2] == 1) {
		$edit_groups = true;
	}
	if ($row[1] == 1) {
		$edit_categories = true;
	}
	if ($row[3] == 1) {
		$post = true;
	}
	if ($row[0] == 1) {
		$edit = true;
	} 
	
	if (($id != $_SESSION["user_id"])&& !$post && !$edit && !$edit_categories && !$edit_groups){
	
		mysql_close($link);
		$msg =  You are not authorized to perform this action;
		header("Location: index.php?msg=".$msg."&id=".$id."&".$common_get);
	}
	 
	switch ($_REQUEST["mode"]) {
	case Update Profile;
		
		updateProfile($id);
		break;
	
	case Add Category;
		addCategory();
		break;
		
	case "Edit Category"; 
		editCategory($id);
		break;
		
	case "Delete Category"; 
		deleteCategory($id);
		break;
		
	case "Add Group";
		addGroup();
		break;
		
	case "Edit Group"; 
		editGroup($id);
		break;
		
	case "Delete Group"; 
		deleteGroup($id);
		break;
	
	case "Add Link";
		addLink();
		break;
		
	case $lang["edit_link"]; 
		editLink($id);
		break;
		
	case $lang["delete_link"]; 
		deleteLink($id);
		break;
	
	case "Delete User"; 
		deleteUser($id);
		break;
		
	case $lang["delete_event"]; 
		deleteEvent($id);
		break;
	
		
	case "Add Profile";
		addProfile();
		break;
		
	case "Update Modules";
		updateModules();
		break;
	
	case "approve";
		approve($id);
		break;
	
	case "install_module";
		installModule($dir,$file);
		break;
	
	
	default; 
		header("Location: index.php");
		break;
	}
	mysql_close($link);
}
?>