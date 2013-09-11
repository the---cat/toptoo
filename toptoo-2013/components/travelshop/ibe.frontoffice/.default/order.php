<?php

/* Для режима USE_MERGED_STEPS экран preview подключается с экрана personal_data */
if ( $arParams['USE_MERGED_STEPS'] !== 'Y' ) {
	require( dirname( __FILE__ ).'/personal_data_order.php' );
}

?>