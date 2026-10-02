<?php

include_once('loadmoney.php');

$related['short_story'] = $_loadmoney->replaceLinks($related['short_story'], $member_id['user_group']);
$related['full_story'] = $_loadmoney->replaceLinks($related['full_story'], $member_id['user_group']);