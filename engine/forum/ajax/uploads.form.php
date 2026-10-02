<?php
/*
=====================================================
 DLE Forum - by DLE Files Group
-----------------------------------------------------
 http://dlekey.cn/
-----------------------------------------------------
 File: uploads.form.php
=====================================================
 Copyright (c) 2007,2011 DLE Files Group
=====================================================
*/

include 'init.php';

$open_url = convert_unicode($_REQUEST['open_url'], $config['charset']);

$content = <<<HTML
<iframe id="_AddUpload" src="{$open_url}" frameborder="0" width="100%" height="220px"></iframe>
<div class="highslide-footer">
<div><span class="highslide-resize">
<span></span></span>
</div>
</div>
HTML;

@header("Content-type: text/css; charset=".$config['charset']);
@header( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
@header( "Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . " GMT" );
@header( "Cache-Control: no-store, no-cache, must-revalidate" );
@header( "Cache-Control: post-check=0, pre-check=0", false );
@header( "Pragma: no-cache" );

echo $content;

?>