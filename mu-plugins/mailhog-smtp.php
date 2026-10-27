<?php
/**
 * Routes all outgoing WordPress mail through the local Mailhog container
 * for development/testing. No real SMTP credentials involved — Mailhog
 * accepts any connection and just catches mail for viewing at
 * http://localhost:8025.
 */

add_action( 'phpmailer_init', function ( $phpmailer ) {
	$phpmailer->isSMTP();
	$phpmailer->Host       = 'mailhog';
	$phpmailer->Port       = 1025;
	$phpmailer->SMTPAuth   = false;
	$phpmailer->SMTPSecure = '';
	$phpmailer->SMTPAutoTLS = false;
} );
