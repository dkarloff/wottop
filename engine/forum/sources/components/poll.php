<?php

if( ! defined( 'DATALIFEENGINE' ) ) {
	die( "Hacking attempt!" );
}

$topic_id = $tid;

function get_votes($all) {
	
	$data = array ();
	
	if( $all != "" ) {
		$all = explode( "|", $all );
		
		foreach ( $all as $vote ) {
			list ( $answerid, $answervalue ) = explode( ":", $vote );
			$data[$answerid] = intval( $answervalue );
		}
	}
	
	return $data;
}

	$_IP = $db->safesql($_SERVER['REMOTE_ADDR']);

	if ($is_logged)
		$log_id = intval($member_id['user_id']);
	else
		$log_id = $_IP;

    $log  = $db->super_query("SELECT COUNT(*) as count FROM " . PREFIX . "_forum_poll_log WHERE topic_id = '$topic_id' AND member ='{$log_id}'");

	$row_topic['poll_title'] = stripslashes($row_topic['poll_title']);
	$row_topic['frage'] = stripslashes($row_topic['frage']);
	$body =  explode("<br />", stripslashes($row_topic['poll_body']));

	$tpl->load_template($tpl_dir.'poll.tpl');
	
	$tpl->set('{vote_title}', $row_topic['poll_title']);
	$tpl->set('{question}', $row_topic['frage']);
	$tpl->set('{vote_count}', $row_topic['poll_count']);

	$list = "<div id=\"dle-poll-list\">";

  if (!$log['count'] AND $check_write) {
	if (!$row_topic['multiple']){

		for ($v = 0; $v < sizeof($body); $v++) {
			if (!$v) $sel = "checked"; else $sel = "";

			$list .= <<<HTML
<div><input name="dle_poll_votes" id="dle_poll_votes" type="radio" $sel value="{$v}" /><label for="dle_poll_votes">{$body[$v]}</label></div>
HTML;

		}
	} else {

		for ($v = 0; $v < sizeof($body); $v++) {

			$list .= <<<HTML
<div><input name="dle_poll_votes[]" id="dle_poll_votes" type="checkbox" value="{$v}" /><label for="dle_poll_votes">{$body[$v]}</label></div>
HTML;

		}

	}

	 $allcount = 0;

  } else {

   $answer = get_votes ($row_topic['answer']);
   $allcount = $row_topic['poll_count'];
   $pn = 0;

	for ($v = 0; $v < sizeof($body); $v++) {

     $num = $answer[$v];
	 ++$pn; if ($pn > 5) $pn = 1;

     if (!$num) $num = 0;

	 if ($allcount != 0) $proc = (100 * $num) / $allcount;
	 else $proc = 0;

     $proc = round($proc, 0);

		$list .= <<<HTML
{$body[$v]} - {$num} ({$proc}%)<br />
<div class="pollprogress"><span class="poll{$pn}" style="width:{$proc}%;">{$proc}%</span></div>
HTML;

	}

 $allcount = 1;

  }

	$list .= "</div>";

	$tpl->set('{vote_body}',$list);

$ajax_script = <<<HTML
<script type="text/javascript">
<!--
var dle_poll_result = 0;
var dle_poll_voted  = {$allcount};
function doPoll( event ){

    var frm = document.dlepollform;
	var vote_check = '';
	var topic_id = frm.topic_id.value;

  if (dle_poll_voted == 1) { return; }

  if (event != 'results' && dle_poll_result != 1) {
    for (var i=0;i < frm.elements.length;i++) {
        var elmnt = frm.elements[i];
        if (elmnt.type=='radio') {
            if(elmnt.checked == true){ vote_check = elmnt.value; break;}
        }
        if (elmnt.type=='checkbox') {
            if(elmnt.checked == true){ vote_check = vote_check + elmnt.value + ' ';}
        }
    }

	if (event == 'vote' && vote_check == '') { alert ('{$lang['poll_failed']}'); return; }

	dle_poll_voted  = 1;

  } else { dle_poll_result = 1; }

	if (dle_poll_result == 1 && event == 'vote') { dle_poll_result = 0; event = 'list'; }
    
    ShowLoading();
    
    $.post(forum_ajax + 'poll.php', {topic_id: topic_id, vote_skin: '{$config['skin']}', action: event, answer: vote_check}, function(data) { $('#dle-poll-list').html(data); });
    
    HideLoading();
}
//-->
</script>
HTML;

$tpl->copy_template = $ajax_script . "<form  method=\"post\" name=\"dlepollform\" id=\"dlepollform\" action=\"\">" . $tpl->copy_template . "<input type=\"hidden\" name=\"topic_id\" id=\"topic_id\" value=\"" . $topic_id . "\" /></form>";

$tpl->compile( 'topic_poll' );
$tpl->clear();

?>