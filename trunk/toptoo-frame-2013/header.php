<? 
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
if ( defined("SHOW_404") || SHOW_404 == "Y") {
    $APPLICATION->IncludeFile("404.php");
    return;
}

class __CCacheControl {
  
  function AddCacheControl( $site_path ) {
    $filesystem_path = $_SERVER[ "DOCUMENT_ROOT" ].$site_path;
    static $bOpera = 0;
    if ( $bOpera === 0 ) {
      $bOpera = ( strpos( $_SERVER[ "HTTP_USER_AGENT" ], "Opera" ) !== false );
    }
    return $site_path.( $bOpera ? "" : "?".filemtime( $filesystem_path ) );
  }

  function RenderCSSLink( $site_path, $bExternalSite = false ) {
  	$site_url = ( $bExternalSite ) ? 'http://'.$_SERVER['HTTP_HOST'] : '';
    return '<link rel="stylesheet" type="text/css" href="'.$site_url.CIBECacheControl::AddCacheControl( $site_path ).'" />'."\n";
  }

  function RenderJSLink( $site_path,  $bExternalSite = false ) {
  	$site_url = ( $bExternalSite ) ? 'http://'.$_SERVER['HTTP_HOST'] : '';
    return '<script type="text/javascript" src="'.$site_url.CIBECacheControl::AddCacheControl( $site_path ).'"></script>'."\n";
  }
  
};

function __GetHeadScripts()
{
	global $APPLICATION;
	$arScripts = array_unique($APPLICATION->arHeadScripts);
	$res = "";
	foreach($arScripts as $src)
	{
		if(file_exists($_SERVER["DOCUMENT_ROOT"].$src))
			$res .= __CCacheControl::RenderJSLink($src, true);
	}
	return $res;
}

function __ShowHeadScripts()
{
    global $APPLICATION;
    echo $APPLICATION->AddBufferContent(__GetHeadScripts);
}

function __GetCSS( $bExternal = true )
{
	global $APPLICATION;
	$res = "";
	$arCSS = $APPLICATION->sPath2css;
	if(defined("SITE_TEMPLATE_ID") && file_exists($_SERVER["DOCUMENT_ROOT"].BX_PERSONAL_ROOT."/templates/".SITE_TEMPLATE_ID."/styles.css"))
	{
		$arCSS[] = BX_PERSONAL_ROOT."/templates/".SITE_TEMPLATE_ID."/styles.css";
		$arCSS[] = BX_PERSONAL_ROOT."/templates/".SITE_TEMPLATE_ID."/template_styles.css";
	}
	else
	{
		$arCSS[] = BX_PERSONAL_ROOT."/templates/.default/styles.css";
		$arCSS[] = BX_PERSONAL_ROOT."/templates/.default/template_styles.css";
	}

	$arCSS = array_unique($arCSS);
	foreach($arCSS as $css_path)
	{
		$filename = $_SERVER["DOCUMENT_ROOT"].$css_path;
		//if( file_exists($filename) )
		//{
			if($bExternal && (strpos($css_path, "/bitrix/modules/")===false))
				$res .= __CCacheControl::RenderCSSLink($css_path, true);
			else
			{
				if($handle = fopen($filename, "r"))
				{
					$contents = fread($handle, filesize($filename));
					fclose($handle);
				}
				$res .= '<style type="text/css">\n'.$contents.'\n</style>\n';
			}
		//}
	}
	return $res;
}

function __ShowCSS()
{
	global $APPLICATION;
	echo $APPLICATION->AddBufferContent(__GetCSS);
}
?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?= LANG_CHARSET;?>" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/> <? // Запрет включения режима совместимости в IE ?>
<meta name="robots" content="all" />
<? $APPLICATION->ShowMeta("keywords") ?>
<? $APPLICATION->ShowMeta("description") ?>
<title><? $APPLICATION->ShowTitle()?></title>
</head>
<body>
<? __ShowHeadScripts() ?>
<? __ShowCSS() ?>
<script type="text/javascript">
// <![CDATA[
function tooltip(selector) {
  switch (typeof selector) {
    case 'object':
      var titles = selector.find('[title]');
      break;

    case 'string':
      selector = $(selector);
      var titles = selector.find('[title]');
      break;

    default: // undefined
      var titles = $('[title]');
      break;
  }

  if (titles.length) {
    titles.tooltip({
      bodyHandler: function() {
        return $('<div class="arr"></div><div class="inner">'.concat(this.tooltipText, '</div>'));
      },
      showURL: false,
      track: true,
      top: 20,
      left: -75,
      width: 160,
      fixPNG: true
    });
  }
}
// ]]>
</script>