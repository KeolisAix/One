<?php
	$g_hostname = 'localhost';
	$g_db_type = 'pgsql';
	$g_database_name = 'mantis';
	$g_db_username = 'postgres';
	$g_db_password = 'postgres';

   
# --- Anonymous Access / Signup ---
$g_allow_signup      = ON;
$g_allow_anonymous_login   = OFF;
$g_anonymous_account   = '';

# --- Email Configuration ---
$g_enable_email_notification   = ON;
$g_send_reset_password = ON;
$g_log_level = LOG_EMAIL | LOG_EMAIL_RECIPIENT;
$g_log_destination = 'file:_maDestination_';
$g_phpMailer_method = PHPMAILER_METHOD_SMTP;

$g_smtp_host = 'smtp.gmail.com';
$g_smtp_username = 'cellulescompteusessap@gmail.com';
$g_smtp_passoword = 'KeolisSAP';
$g_from_name = 'Mantis Bug Tracker';
$g_smtp_connection_mode = 'TLS';
$g_smtp_port = 587 ;
$g_administrator_email = 'patrice.maldi@keolis.com';
$g_webmaster_email = 'patrice.maldi@keolis.com';
$g_from_email = 'mantis-KPA@keolis.com';
$g_return_path_email = 'mantis-KPA@keolis.com';

$g_allow_blank_email = ON;
$g_validate_email = ON;
$g_email_send_using_cronjob = ON;
?>