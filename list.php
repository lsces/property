<?php
/**
 * @version $Header: /cvsroot/bitweaver/_bit_property/list.php,v 1.5 2010/02/08 21:27:22 wjames5 Exp $
 *
 * Copyright (c) 2006 bitweaver.org
 * All Rights Reserved. See below for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See http://www.gnu.org/copyleft/lesser.html for details
 *
 * @package property
 * @subpackage functions
 */

/**
 * required setup
 */
require_once( '../kernel/setup_inc.php' );

include_once( PROPERTY_PKG_PATH.'Property.php' );

$gBitSystem->verifyPackage( 'property' );

$gBitSystem->verifyPermission( 'p_property_view' );

$gContent = new Property( );

if( !empty( $_REQUEST["find_org"] ) ) {
	$_REQUEST["find_name"] = '';
	$_REQUEST["sort_mode"] = 'organisation_asc';
} else if( empty( $_REQUEST["sort_mode"] ) ) {
	$_REQUEST["sort_mode"] = 'surname_asc';
	$_REQUEST["find_name"] = 'a';
}

//$property_type = $gContent->getPropertysTypeList();
//$gBitSmarty->assign_by_ref('property_type', $property_type);
$listHash = $_REQUEST;
// Get a list of matching property entries
$listproperties = $gContent->getList( $listHash );

$gBitSmarty->assign_by_ref( 'listproperties', $listproperties );
$gBitSmarty->assign_by_ref( 'listInfo', $listHash['listInfo'] );
$gBitSystem->setBrowserTitle("View Properties List");
// Display the template
$gBitSystem->display( 'bitpackage:property/list.tpl', NULL, array( 'display_mode' => 'list' ));

?>
