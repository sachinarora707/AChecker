<?php
/************************************************************************/
/* AChecker                                                             */
/************************************************************************/
/* Copyright (c) 2008 by Greg Gay, Cindy Li                             */
/* Adaptive Technology Resource Centre / University of Toronto			    */
/*                                                                      */
/* This program is free software. You can redistribute it and/or        */
/* modify it under the terms of the GNU General Public License          */
/* as published by the Free Software Foundation.                        */
/************************************************************************/

$_user_location = 'public';

define('AC_INCLUDE_PATH', 'include/');
require(AC_INCLUDE_PATH.'vitals.inc.php');
require(AC_INCLUDE_PATH.'classes/DAO/UsersDAO.class.php');

if (isset($_POST['cancel'])) {
	$msg->addFeedback('CANCELLED');
	header('Location: '.$_base_href.'login.php');
	exit;
}

if (isset($_GET['id'], $_GET['m'])) 
{
	$id = intval($_GET['id']);
	$m  = $_GET['m'];

	$usersDAO = new UsersDAO();
	$row = $usersDAO->getUserByID($id);
	
	if ($row['status'] == AC_STATUS_UNCONFIRMED) 
	{
		$code = substr(md5($row['email'] . $row['creation_date'] . $id), 0, 10);

		if ($code == $m) 
		{
			$usersDAO->setStatus($id, AC_STATUS_ENABLED);

			$msg->addFeedback('CONFIRM_GOOD');
			$_REQUEST["user_id"] = $id;
		} 
		else 
		{
			$msg->addError('CONFIRM_BAD');
		}
	} 
	else 
	{
		$msg->addError('CONFIRM_BAD');
	}
} 
else if (isset($_POST['submit'])) {
	$_POST['email'] = $addslashes($_POST['email']);

	$row = $usersDAO->getUserByEmail($_POST['email']);

	if ($row) 
	{
		if ($row['status'] == AC_STATUS_UNCONFIRMED) 
		{
			$code = substr(md5($row['email'] . $row['creation_date']. $row['user_id']), 0, 10);
			
			$confirmation_link = $_base_href . 'confirm.php?id='.$row['user_id'].SEP.'m='.$code;

			/* send the email confirmation message: */
			require(AC_INCLUDE_PATH . 'classes/phpmailer/acheckermailer.class.php');
			$mail = new ACheckerMailer();
		
			$mail->From     = $_config['contact_email'];
			$mail->AddAddress($row['email']);
			$mail->Subject = SITE_NAME . ': ' . _AC('email_confirmation_subject');
			$mail->Body    = _AC('email_confirmation_message', $_base_href, $confirmation_link)."\n\n";
			$mail->Send();

			$msg->addFeedback('CONFIRMATION_SENT');
		} 
		else 
		{
			$msg->addFeedback('ACCOUNT_CONFIRMED');
		}

		header('Location: '.$_base_href.'login.php');
		exit;
	} 
	else 
	{
		$msg->addError('EMAIL_NOT_FOUND');
	}
}

require(AC_INCLUDE_PATH.'header.inc.php'); ?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="form">

<div class="input-form" style="max-width: 400px">
	<div class="row">
		<p><?php echo _AC('send_confirmation'); ?></p>
	</div>

	<div class="row">
		<div class="required" title="<?php echo _AC('required_field'); ?>">*</div><label for="email"><?php echo _AC('email_address'); ?></label><br />
		<input type="text" name="email" id="email" size="50" />
	</div>

	<div class="row buttons">
		<input type="submit" name="submit" value="<?php echo _AC('send'); ?>" /> 
		<input type="submit" name="cancel" value="<?php echo _AC('cancel'); ?>" />
	</div>
</div>
</form>

<?php require(AC_INCLUDE_PATH.'footer.inc.php'); ?>