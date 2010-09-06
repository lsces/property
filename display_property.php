<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_contact/display_contact.php,v 1.7 2010/02/08 21:27:22 wjames5 Exp $
 *
 * Copyright (c) 2006 bitweaver.org
 * All Rights Reserved. See below for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See http://www.gnu.org/copyleft/lesser.html for details.
 *
 * @package contact
 * @subpackage functions
 */

/**
 * required setup
 */
require_once( '../kernel/setup_inc.php' );

include_once( PROPERTY_PKG_PATH.'Property.php' );

$gBitSystem->verifyPackage( 'property' );

$gBitSystem->verifyPermission( 'p_property_view' );

if( !empty( $_REQUEST['property_id'] ) ) {
	$gContent = new Property( $_REQUEST['property_id'], null );
	$gContent->load();
	$gContent->loadXrefList();
} else if( !empty( $_REQUEST['content_id'] ) ) {
	$gContent = new Property( null, $_REQUEST['content_id'] );
	$gContent->load();
	$gContent->loadXrefList();
} else {
	$gContent = new Contact();
}
$gBitSmarty->assign_by_ref( 'propertyInfo', $gContent->mInfo );
if ( $gContent->isValid() ) {
	$gBitSystem->setBrowserTitle("Property Information");
	$gBitSystem->display( 'bitpackage:property/show_property.tpl');
} else {
	header ("location: ".PROPERTY_PKG_URL."list.php");
	die;
}
?>
