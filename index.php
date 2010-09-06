<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_contact/index.php,v 1.7 2010/02/08 21:27:22 wjames5 Exp $
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

$gBitSystem->isPackageActive('property', TRUE);

if( !empty( $_REQUEST['content_id'] ) ) {
	$gContent = new Property( null, $_REQUEST['content_id'] );
	$gContent->load();
	$gContent->loadXrefList();
} else {
	$gContent = new Property();
}

// Comments engine!
if( $gBitSystem->isFeatureActive( 'feature_contact_comments' ) ) {
	$comments_vars = Array('page');
	$comments_prefix_var='contact note:';
	$comments_object_var='page';
	$commentsParentId = $gContent->mContentId;
	$comments_return_url = PROPERTY_PKG_URL.'index.php?content_id='.$gContent->mContentId;
	include_once( LIBERTY_PKG_PATH.'comments_inc.php' );
}

if ( $gContent->isValid() ) {
	$gBitSmarty->assign_by_ref( 'propertyInfo', $gContent->mInfo );
	$gBitSystem->setBrowserTitle("Property List Item");
	$gBitSystem->display( 'bitpackage:property/show_property.tpl', NULL, array( 'display_mode' => 'display' ));
} else {
	header ("location: ".PROPERTY_PKG_URL."list.php");
	die;
}
?>
