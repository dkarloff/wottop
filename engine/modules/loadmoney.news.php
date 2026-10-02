<?php

include_once('loadmoney.php');

$row['short_story'] = $_loadmoney->replaceLinks($row['short_story'], $member_id['user_group']);
$row['full_story'] = $_loadmoney->replaceLinks($row['full_story'], $member_id['user_group']);