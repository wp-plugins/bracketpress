<?php

// to debug or not to debug?
define('BRACKETPRESS_DEBUG', false);

// Constants used by BracketPress
// most of these are now available in the bracketpress() class,

define( 'BRACKETPRESS_PATH', plugin_dir_path( __FILE__ ) );
define( 'BRACKETPRESS_URL',  plugin_dir_url( __FILE__ ) );

define( 'BRACKETPRESS_CSS_URL', BRACKETPRESS_URL.'media/css/' );
define( 'BRACKETPRESS_PATH_TEMPLATES', BRACKETPRESS_PATH.'media/css/' );

define( 'BRACKETPRESS_MEDIA', BRACKETPRESS_URL.'media/' );
define( 'BRACKETPRESS_IMAGES', BRACKETPRESS_MEDIA.'images/' );
define( 'BRACKETPRESS_JS', BRACKETPRESS_MEDIA.'js/' );

define('NUMBER_OF_TEAMS', 64);
define('NUMBER_OF_LOCATIONS', 14);

// Regions
define('BRACKETPRESS_REGION_SOUTH',   1);
define('BRACKETPRESS_REGION_WEST',    2);
define('BRACKETPRESS_REGION_EAST',    3);
define('BRACKETPRESS_REGION_MIDWEST', 4);


//Region Positions
define('BRACKETPRESS_REGION_1', get_option( 'bracketpress_region_1', '1'));
define('BRACKETPRESS_REGION_2', get_option( 'bracketpress_region_2', '2'));
define('BRACKETPRESS_REGION_3', get_option( 'bracketpress_region_3', '3'));
define('BRACKETPRESS_REGION_4', get_option( 'bracketpress_region_4', '4'));

//Region Position Names

define('BRACKETPRESS_REGION_NAME_1', get_option( 'bracketpress_regionname_1', 'SOUTH'));
define('BRACKETPRESS_REGION_NAME_2', get_option( 'bracketpress_regionname_2', 'WEST'));
define('BRACKETPRESS_REGION_NAME_3', get_option( 'bracketpress_regionname_3', 'EAST'));
define('BRACKETPRESS_REGION_NAME_4', get_option( 'bracketpress_regionname_4', 'MIDWEST'));