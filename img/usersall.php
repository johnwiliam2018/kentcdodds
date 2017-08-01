<?php
	$page_title = "Users";
	$page_slug = "users_";
	$page_sub_slug = "usersall";
	require ('library/admin_application_top.php');

	$type = tep_get_value_get("type");

	$action = (isset($_GET['action']) ? $_GET['action'] : '');
	$userid = (isset($_GET['id']) ? $_GET['id'] : '');

	if ($action == 'delete') {
		$wpdb -> delete(TABLE_USERS, array("id" => $userid));

		$wpdb -> delete(TABLE_USER_VISIT_LOGS, array("user_id" => $userid));

		die('OK');
	} elseif ($action == 'lock') {
		$wpdb -> update(TABLE_USERS, array("status" => 0), array("id" => $userid));

		die('OK');
	} elseif ($action == 'unlock') {
		$wpdb -> update(TABLE_USERS, array("status" => 1), array("id" => $userid));

		die('OK');
	}

	$sql = "SELECT * FROM " . TABLE_USERS . " WHERE 1=1";
	if ($type != '') {
		$sql .= " AND `type` = " . $type;
	}

	$users = $wpdb -> get_results($sql);


	require ('views/header.php');
?>

<!-- page content -->
<div class="right_col" role="main">
	<div class="">
		
		<div class="page-title">
	      <div class="title_left">
	        <h3><?php echo $page_title?></h3>
	      </div>
	    </div>
		<div class="clearfix"></div>
		
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="x_panel">
                                <div class="x_title">						
                                    <h2>&nbsp;Users</h2>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content">
			            <table id="table-users" class="table table-striped table-bordered">
			              <thead>
			                <tr>
			                  <th>ID</th>			                  
			                  <th>First Name</th>
							  <th>Last Name</th>
							  <th>Email</th>			                  
			                  <th>Last Activated</th>
			                  <th>Status</th>
			                  <th>Actions</th>
			                </tr>
			              </thead>

                                        <tbody>
                                            <?php if (count($users) == 0) : ?>
                                                <tr>
                                                    <td colspan="15">Empty users.</td>
                                                </tr>
                                            <?php else: ?>
			              	<?php foreach ($users as $user) : ?>
			                <tr id="user-<?php echo $user->id ?>">
			                  <td><?php echo $user->id ?></td>
						      <td><?php echo $user->firstname ?></td>							  
			                  <td><?php echo $user->lastname ?></td>
							  <td><?php echo $user->email ?></td>			            
		               		  <td><?php echo $user->last_actived ?></td>
		               		  <td class="status-text"><?php echo($user -> status == 1 ? 'Activated' : 'Blocked'); ?>
			                  <td>
			                  	<a href='usersedit.php?id=<?php echo $user->id ?>' title="Edit"><i class="fa fa-edit"></i></a>
			                  	&nbsp;|&nbsp;
			                  	<a href='javascript:delete_user(<?php echo $user->id ?>)' title="Delete"><i class="fa fa-remove"></i></a>
                                                &nbsp;|&nbsp;				 	
                                                <?php if ($user->status == 1) : ?>
                                                    <a href='javascript:lock_user(<?php echo $user->id ?>)' title="Click for lock" class="user-status"><i class="fa fa-unlock"></i></a>
                                                <?php else: ?>
                                                    <a href='javascript:unlock_user(<?php echo $user->id ?>)' title="Click for unlock" class="user-status"><i class="fa fa-lock"></i></a>
                                                <?php endif; ?>
                                          </td>
                                        </tr>
                                        <?php endforeach; ?>
                                        <?php endif; ?>
                                        </tbody>
			            </table>
			        </div>
			    </div>
			</div>
		</div>
	</div>
</div>

<?php include_once 'views/table.js.php' ?>
<!-- Datatables -->
<script>
	var $tableUsers;
	$(document).ready(function() {
		try {
			$tableUsers = $('#table-users').DataTable({
				"order" : [[4, "desc"]]
			});
		} catch(e) {
		}
	});

	function delete_user(userid) {
		if (confirm("Delete selected user?")) {
			$.get("usersall.php?action=delete&id=" + userid, function() {
				$tableUsers.row($("#user-" + userid)).remove().draw();
			})
		}
	}

	function lock_user(userid) {
		if (confirm("Suspend selected user?")) {
			$.get("usersall.php?action=lock&id=" + userid, function() {
				$statusObj = $("#user-" + userid).find(".status-text");
				$statusObj.text("Blocked");
				
				$linkObj = $("#user-" + userid).find("a.user-status");
				$linkObj.attr('href', 'javascript:unlock_user(' + userid + ')');
				$linkObj.attr('title', 'Click for unlock');
				$linkObj.find('i.fa').removeClass('fa-unlock').addClass('fa-lock');

				$tableUsers.draw();
			})
		}
	}

	function unlock_user(userid) {
		if (confirm("Are you sure unlock selected user?")) {
			$.get("usersall.php?action=unlock&id=" + userid, function() {
				$statusObj = $("#user-" + userid).find(".status-text");
				$statusObj.text("Activated");
				
				$linkObj = $("#user-" + userid).find("a.user-status");
				$linkObj.attr('href', 'javascript:lock_user(' + userid + ')');
				$linkObj.attr('title', 'Click for lock');
				$linkObj.find('i.fa').removeClass('fa-lock').addClass('fa-unlock');

				$tableUsers.draw();
			})
		}
	}
</script>
<!-- /Datatables -->
<?php
require ('views/footer.php');
