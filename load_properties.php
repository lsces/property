<?php
/*
 * Created on 5 Jan 2008
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

// Initialization
require_once( '../kernel/setup_inc.php' );
require_once(PROPERTY_PKG_PATH.'Property.php' );

// Is package installed and enabled
$gBitSystem->verifyPackage( 'property' );
$gBitSystem->verifyPackage( 'contact' );

// Now check permissions to access this page
$gBitSystem->verifyPermission('p_property_admin' );

$property = new Property();

$property->DataExpunge();

$row = 0;

$handle = fopen("../contact/data/clientdatabase.csv", "r");
if ( $handle == FALSE) {
	$row = -999;
} else {
	while (($data = fgetcsv($handle, 800, ",")) !== FALSE) {
    	if ( $row ) $property->PropertyRecordLoad( $data );
    	$row++;
	}
	fclose($handle);
}

$gBitSmarty->assign( 'count', $row );

$gBitSystem->display( 'bitpackage:property/load_properties.tpl', tra( 'Load results: ' ) );
?>
