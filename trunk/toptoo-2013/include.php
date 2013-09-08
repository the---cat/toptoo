<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); ?>
<?
// Удаленный инклюд
//
// Если не задан URL для загрузки, то выходим
if ( !strlen($URL) )
	return;

// Время хранения кеша. По умолчанию 1 час.
if( isset($CACHE_TIME) && intval($CACHE_TIME) >= 0 ) {
	$CACHE_TIME = intval($CACHE_TIME);
} else {
	$CACHE_TIME = 3600;
};

$obCache = new CPHPCache;
$cache_id = md5($URL);

// если кеш есть и он ещё не истек, то

$includeLoaded = true;

if( $obCache->InitCache( $CACHE_TIME, $cache_id, "/") ):
    // получаем закешированные переменные
    $vars = $obCache->GetVars();
    $INCLUDE = $vars["INCLUDE"];
else:
    // иначе загружаем файл с сайта
    $opts = array('http' =>
      array(
        'method'  => 'GET',
        'timeout' => 10
      )
    );
    $context  = stream_context_create($opts);
    $INCLUDE = file_get_contents($URL, false, $context);
    if ( !strlen( $INCLUDE ) ) { // Если не удается загрузить файл за 10 с
      $includeLoaded = false;
      if ( $obCache->InitCache( 30000000, $cache_id, "/") ) { // то берем его из просроченного кеша
        $vars = $obCache->GetVars();
        $INCLUDE = $vars["INCLUDE"];
      }
    };
endif;

if( $obCache->StartDataCache() ):
    // выводим содержимое файла
    print $INCLUDE;
    if ( $includeLoaded ) {
      // записываем предварительно буферизированный вывод в файл кеша
      // вместе с дополнительной переменной
      $obCache->EndDataCache(array(
          "INCLUDE"	=> $INCLUDE
          ));
    }
endif;

unset($cache_id, $INCLUDE, $URL, $vars, $CACHE_TIME, $obCache, $opts, $context, $includeLoaded);
?>