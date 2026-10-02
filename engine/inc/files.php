<?PHP
/*
=====================================================
 DataLife Engine - by SoftNews Media Group 
-----------------------------------------------------
 http://dle-news.ru/
-----------------------------------------------------
 Copyright (c) 2004-2020 SoftNews Media Group
=====================================================
 This code is protected by copyright
=====================================================
 Files: files.php
-----------------------------------------------------
 Use: manage uploaded pictures
=====================================================
*/
if( !defined( 'DATALIFEENGINE' ) OR !defined( 'LOGGED_IN' ) ) {
	header( "HTTP/1.1 403 Forbidden" );
	header ( 'Location: ../../' );
	die( "Hacking attempt!" );
}

if( $member_id['user_group'] != 1 ) {
	msg( "error", $lang['index_denied'], $lang['index_denied'] );
}

$full_path_array = array(ROOT_DIR . "/uploads" );
$userdir = "";

if( $_GET['userdir'] ) {
	
	$full_path_array[] = $userdir = cleanpath( $_GET['userdir'] );
	
}

$max_file_size = (int)($config['max_up_size'] * 1024);
$allowed_extensions = array ("gif", "jpg", "png", "webp" );
$simple_ext = implode( "', '", $allowed_extensions );


if ( $userdir == "files" ) msg( "error", $lang['addnews_denied'], $lang['index_denied'] );

$config_path_image_upload = implode("/", $full_path_array );

if( ! @is_dir( $config_path_image_upload ) ) msg( "error", $lang['addnews_denied'], "Directory {$userdir} not found" );

if( $action == "doimagedelete" ) {
	
	if( $_REQUEST['user_hash'] == "" or $_REQUEST['user_hash'] != $dle_login_hash ) {
		
		die( "Hacking attempt! User not found" );
	
	}
	
	if( ! isset( $_POST['images'] ) ) {
		msg( "error", $lang['images_delerr'], $lang['images_delerr_1'], "?mod=files" );
	}

	foreach ( $_POST['images'] as $image ) {

		$image = totranslit($image);

		if( $image ) {
			
			if( stripos ( $image, ".htaccess" ) !== false ) die("Hacking attempt!");
	
			$img_name_arr = explode( ".", $image );
			$type = totranslit( end( $img_name_arr ) );
	
			if( !in_array( $type, $allowed_extensions ) ) die("Hacking attempt!");
			
			@unlink( $config_path_image_upload . "/". $image );
			@unlink( $config_path_image_upload . "/thumbs/" . $image );
			@unlink( $config_path_image_upload . "/medium/" . $image );
	
			$db->query( "INSERT INTO " . USERPREFIX . "_admin_logs (name, date, ip, action, extras) values ('".$db->safesql($member_id['name'])."', '{$_TIME}', '{$_IP}', '37', '{$image}')" );
	
		}
	}
}

	$js_array[] = "engine/classes/uploads/html5/fileuploader.js";
	$js_array[] = "engine/classes/highslide/highslide.js";

	echoheader( "<i class=\"fa fa-file-image-o position-left\"></i><span class=\"text-semibold\">{$lang['header_f_1']}</span>", $lang['header_f_2'] );
	
	$current_dir = opendir( $config_path_image_upload );
	
	$folder_list = array();

	if( $userdir ) {
		$prev_link = explode("/", $userdir);
		array_pop($prev_link);
		$prev_link = implode("/", $prev_link);
		$folder_list[] = "<div class=\"uploadedfile\"><div class=\"uploadimage\"><a class=\"uploadfile\" href=\"?mod=files&userdir={$prev_link}\"><i class=\"fa fa-arrow-circle-o-left text-slate-600\"></i></a></div><div class=\"info\">{$lang['files_prev']}</div></div>";
	}
	
	while ( $entryname = readdir( $current_dir ) ) {


		if( is_dir( "{$config_path_image_upload}/{$entryname}" ) AND $entryname != "." AND $entryname != ".." AND $entryname != "files" ) {
			
			if( $userdir ) $link = $userdir."/".$entryname; else $link = $entryname;
			
			$folder_list[] = "<div class=\"uploadedfile\"><div class=\"uploadimage\"><a class=\"uploadfile\" href=\"?mod=files&userdir={$link}\"><i class=\"fa fa-folder\"></i></a></div><div class=\"info\">{$entryname}</div></div>";

		}
	}

	
	$img_dir = opendir( $config_path_image_upload );
	$i = 0;
	$total_size = 0;
	
	while ( $file = readdir( $img_dir ) ) {
		$images_in_dir[] = $file;
	}
	
	natcasesort( $images_in_dir );
	reset( $images_in_dir );

	foreach ( $images_in_dir as $file ) {
		
		$img_type = explode( ".", $file );
		$img_type = totranslit( end( $img_type ) );
		
		if( in_array( $img_type, $allowed_extensions ) AND is_file( $config_path_image_upload ."/". $file ) ) {
			
			$i ++;
			$this_size = @filesize( $config_path_image_upload ."/". $file );
			$img_info = @getimagesize( $config_path_image_upload ."/". $file );
			$total_size += $this_size;
			
			if($userdir) {
				$img_url = $config['http_home_url'] . "uploads/" . $userdir . "/" . $file;	
			} else {
				$img_url = $config['http_home_url'] . "uploads/" . $file;
			}

			$folder_list[] = "<div class=\"uploadedfile\"><div class=\"info\">{$file}</div><div class=\"uploadimage\"><a class=\"highslide\" href=\"{$img_url}\" target=\"_blank\"><img style=\"width:auto;height:auto;max-width:100px;max-height:90px;\" src=\"{$img_url}\" alt=\"{$file}\"></a></div><div class=\"info\"><div class=\"checkbox\"><label><input type=\"checkbox\" class=\"icheck\" name=\"images[{$file}]\" value=\"{$file}\">{$img_info[0]}x{$img_info[1]}</label></div></div></div>";
	
		}
	}
	
	$folder_list = implode('', $folder_list);
	
	if( $total_size ) {
		$total_size = formatsize( $total_size );
		$total_size = "<div class=\"mt-20\"><span class=\"position-left\">{$lang['images_size']}</span>{$total_size}</div>";
	} else $total_size = "";

	echo <<<HTML
<form action="" method="post" name="delimages" id="delimages">
<div class="panel panel-default">
  <div class="panel-heading">
    {$lang['uploaded_file_list']}
  </div>
  <div class="panel-body row-seamless">
	<div class="file-list">
	{$folder_list}
	</div>
	{$total_size}
  </div>
HTML;


	echo "<div class=\"panel-footer\">
		<div id=\"file-uploader\" style=\"width:210px;float:left;\"></div>
		<div style=\"float:right;\"><button class=\"btn bg-danger btn-sm btn-raised\" onclick=\"delete_file(); return false;\">{$lang['images_del']}</button><input type=\"hidden\" name=\"action\" value=\"doimagedelete\"><input type=\"hidden\" name=\"user_hash\" value=\"$dle_login_hash\" /></div>
	</div>";

	$max_file_size = number_format($max_file_size, 0, '', '');

	echo <<<HTML
   </div>
</form>
<script>
jQuery(function($){

	var totaladded = 0;
	var totaluploaded = 0;

	var uploader = new qq.FileUploader({
		element: document.getElementById('file-uploader'),
		action: 'engine/ajax/controller.php?mod=upload',
		maxConnections: 1,
		encoding: 'multipart',
        sizeLimit: {$max_file_size},
		allowedExtensions: ['{$simple_ext}'],
	    params: {"subaction" : "upload", "news_id" : "0", "area" : "adminupload", "userdir" : "{$userdir}", "user_hash" : "{$dle_login_hash}"},
        template: '<div class="qq-uploader">' + 
                '<div class="qq-upload-drop-area"><span>{$lang['media_upload_st5']}</span></div>' +
                '<div class="qq-upload-button btn bg-teal btn-sm btn-raised position-left" style="width: auto;">{$lang['media_upload_st14']}</div>' +
                '<ul class="qq-upload-list" style="display:none;"></ul>' + 
             '</div>',
		onSubmit: function(id, fileName) {

					totaladded ++;

					$('<div id="uploadfile-'+id+'" class="file-box mt-20"><span class="qq-upload-file">{$lang['media_upload_st6']}&nbsp;'+fileName+'</span><span class="qq-status"><span class="qq-upload-spinner"></span><span class="qq-upload-size"></span></span></div>').appendTo('.panel-body.row-seamless');

        },
		onProgress: function(id, fileName, loaded, total){
					$('#uploadfile-'+id+' .qq-upload-size').text(uploader._formatSize(loaded)+' {$lang['media_upload_st8']} '+uploader._formatSize(total));
		},
		onComplete: function(id, fileName, response){
						totaluploaded ++;

						if ( response.success ) {

							$('#uploadfile-'+id+' .qq-status').html('{$lang['media_upload_st9']}');

							if (totaluploaded == totaladded ) setTimeout("location.replace( window.location )",2E3);


						} else {
							$('#uploadfile-'+id+' .qq-status').html('{$lang['media_upload_st10']}');

							if( response.error ) $('#uploadfile-'+id+' .qq-status').append( '<br /><span class="text-danger">' + response.error + '</span>' );

							setTimeout(function() {
								$('#uploadfile-'+id).fadeOut('slow');
							}, 4000);
						}
		},
        messages: {
            typeError: "{$lang['media_upload_st11']}",
            sizeError: "{$lang['media_upload_st12']}",
            emptyError: "{$lang['media_upload_st13']}"
        },
		debug: false
    });

    hs.graphicsDir = '{$config['http_home_url']}engine/classes/highslide/graphics/';
	hs.numberOfImagesToPreload = 0;
	hs.captionEval = 'this.thumb.alt';
	hs.showCredits = false;
	hs.transitions = ['expand', 'crossfade'];
	hs.wrapperClassName = 'less';
	hs.outlineType = null;

});

function delete_file() {
	DLEconfirm( '{$lang['delete_selected']}', '{$lang['p_info']}', function () {
		document.delimages.submit();
	} );
};	
</script>
HTML;

	echofooter();

?>