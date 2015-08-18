<?php
$default = "tal-default";
$db = 'talnitza_meme';
if (!isset($waterfall) ) {
    $waterfall = $default;
}

// Get waterfall tags

$str = "SELECT tag FROM $db.waterfalls WHERE waterfall='" .$waterfall . "' order by position asc";
$qry_res = mysqli_query($con,$str);

$rowNumber = 0;
$data = new stdClass;
while ($row = mysqli_fetch_array($qry_res,MYSQLI_ASSOC)) {
    $data->data[$rowNumber] = new stdClass();
    $data->data[$rowNumber]->tag = $row['tag'];
    $rowNumber++;
}

// Get default waterfall if waterfall not found in DB

$total = count((array)$data);
if ($total == 0) {
    $waterfall = $default;
    $str = "SELECT tag FROM $db.waterfalls WHERE waterfall='" .$waterfall . "' order by position asc";
    $qry_res = mysqli_query($con,$str);
    $rowNumber = 0;
    $data = new stdClass;
    while ($row = mysqli_fetch_array($qry_res,MYSQLI_ASSOC)) {
        $data->data[$rowNumber] = new stdClass();
        $data->data[$rowNumber]->tag = $row['tag'];
        $rowNumber++;
    }
}
mysqli_close($con);

$jsn = json_encode($data);
$tags = $jsn;