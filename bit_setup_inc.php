<?php
global $gBitSystem, $gBitSmarty;
$registerHash = array(
	'package_name' => 'property',
	'package_path' => dirname( __FILE__ ).'/',
	'homeable' => TRUE,
);
$gBitSystem->registerPackage( $registerHash );

if( $gBitSystem->isPackageActive( 'property' ) ) {
	$menuHash = array(
		'package_name'  => PROPERTY_PKG_NAME,
		'index_url'     => PROPERTY_PKG_URL.'index.php',
		'menu_template' => 'bitpackage:property/menu_property.tpl',
	);
	$gBitSystem->registerAppMenu( $menuHash );
}

?>
