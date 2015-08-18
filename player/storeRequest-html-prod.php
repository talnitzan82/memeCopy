<?php
date_default_timezone_set('UTC');
error_reporting(E_ALL);

//Get http params
$protocol = $_SERVER['HTTPS'] == 'on' ? 'https' : 'http';
$url = $protocol . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$parts = parse_url($url);
parse_str($parts['query'], $query);
$ourDomain = $_SERVER['SERVER_NAME'];
$sDeviceType = $query['sDeviceType'];
$sType = $query['sType'];
$sPlatform = $query['sPlatform'];
$sSeller = $query['sSeller'];
$sPlacement = $query['sPlacement'];
$sCampaignID = $query['sCampaignID'];
$sBid = $query['sBid'];
$sCost = $query['sCost'];
$sDomain = $query['sDomain'];
$sGeo = $query['sGeo'];
$sSize = $query['sSize'];
$sUserID = $query['userID'];
$click_url = $query['click_url'];

$uAgent = $_SERVER['HTTP_USER_AGENT'];
$uIP = $query['uIP'];
$uDeviceID = $query['uDeviceID'];
$uID = $query['uID'];
$uAllowCookies = $query['uAllowCookies'];
$uConnectionType = $query['uConnectionType'];
$uCarrier = $query['uCarrier'];
$uGeo = $query['uGeo'];

$dHost = $query['dHost'];
$dReferer = $query['dReferer'];
$dMaskedIndex = $query['dMaskedIndex'];
$cGeo = $query['cGeo'];

$pReady = $query['pReady'];
$pErrorIndex = $query['pErrorIndex'];
$pSize = $query['pSize'];
$pInetiation = $query['pInetiation'];
$pSound = $query['pSound'];
$pRandering = $query['pRandering'];
$pContentId = $query['pContentId'];
$pContent = $query['pContent'];
$adTagURL = $query['prerollTag'];
$adTagViewed = $query['adTagViewed'];
$tag = $query['tag'];
$sPlatformID = $query['sPlatformID'];
$sPublisher = $query['sPublisher'];
$video = $query['video'];
$fallback = $query['f'];
$tagid = $query['tagid'];
$creativeID = $query['creativeID'];
$lrPartner= $query['lrPartner'];
$waterfall= $query['waterfall'];
$cpgID = $query['lineItemID'];
$userState =$query['userState'];
$userCity =$query['userCity'];
$creativeType =$query['creativeType'];
$creativeBrand = $query['creativeBrand'];
$invSourceID = $query['invSourceID'];
$isGoodBoy = $query['isGoodBoy'];
$getGeo = $query['getGeo'];
$creativeMedia = '';

//include 'uAgentGetter.php';

if (($getGeo == '') || (!isset($getGeo))) {
    $getGeo = 0;
}

if (($isGoodBoy == '') || (!isset($isGoodBoy))) {
    $isGoodBoy = 0;
}

if (($creativeType == '') || ($creativeType == 'null')) {
    $creativeType = 'video';
}

if ($creativeType == 'banner') {
    if ($creativeBrand == 'rdio') {
        $creativeMedia = "<a href='http://www.rdio.com' target='_blank'><img src='http://search-creatives.s3.amazonaws.com/ad/f9/2c/adf92c13d2bef238f58d1e514b3c0e08.jpg'/></a>";
    } elseif ($creativeBrand == 'nokia') {
        $creativeMedia = "<a href='http://www.nokia.com' target='_blank'><img src='http://demo.theme-junkie.com/insider/files/2013/06/insider_300x250.png'/></a>";
    } else {
        $creativeMedia = "<a href='http://www.rdio.com' target='_blank'><img src='http://search-creatives.s3.amazonaws.com/ad/f9/2c/adf92c13d2bef238f58d1e514b3c0e08.jpg'/></a>";
    }
} else {
    $creativeType = 'video';
    $creativeMedia = 'http://cdn.liverail.com/adasset4/7917/75850/242266/lo.mp4';
}


if (($video == '') || ($video == 'null')) {
    if (strpos($ourDomain,'viewwonder') !== false) {
        $domainForVideo = 'viewwonder.com';
    } else {
        $domainForVideo = $ourDomain;
    }
    $video = 'http://cdnsl.memeglobal.com/v1/AUTH_9ad04ec0-e68c-4941-b188-2912aaed078a/media/silence2.mp4';

}

if(!isset($lrPartner)) {
    $lrPartner = 771918;
}


if ($dReferer == '') {
    $referrer = $_SERVER['HTTP_REFERER'];
    $parsedRef = parse_url($referrer);
    $dReferer = $parsedRef['host'];
}
$parsed = parse_url($sDomain);
$topDomain =$parsed['host'];
if(!isset($topDomain)) {
    $topDomain = $parsed['path'];
}

if (!isset($tagid)) {
    $tagid = 0;
}

//AUDIT
$taggifyAudit = (!isset($sCampaignID) || $sCampaignID == '{campaign_id}' || $sCampaignID == '0') && (strpos($dReferer, 'data.rtbfy') !== false);
$adnxAudit = (!isset($sCampaignID) || $sCampaignID == '${CP_ID}');
$openxAudit = (!isset($sCampaignID) || $sCampaignID == '{line.id}');

//AUDIT
if ((strpos($dReferer, 'mediat') !== false) || (strpos($dReferer, 'openx') !== false && $openxAudit == true) || ($taggifyAudit == true) || ($adnxAudit == true) || (strpos($dReferer, 'w3s') !== false) || strpos($dReferer, 'mathads') !== false || strpos($dReferer, 'themediatrust') !== false) {
    $audit = 100;
    $autostart = 0;
} else {
    $audit = 0;
    $autostart = 1;
}

if(!isset($audit) || $audit=='') {
    $audit =0;
}

if (!isset($sType)) {
    $sType = "newiframe";
}

$qs = "select deskWF,bid,tgtGeo,tgtSeller,platform,device from campaignsN where campaignID = $sCampaignID";

$defaultPlatform='AppNexus';
$player = 'tp';
$tgtGeo = '';
$tgtSeller = '';
$platform = '';
$defaultCost = '';
$deskWF = 'tal-default';
$device = 'desktop';
if ($mysqli_result = mysqli_query($con, $qs)) {
    if ($mysql_fetch_row = mysqli_fetch_row($mysqli_result)) {
        $deskWF = $mysql_fetch_row[0];
        $defaultCost = $mysql_fetch_row[1];
        $tgtGeo = $mysql_fetch_row[2];
        $tgtSeller = $mysql_fetch_row[3];
        $platform = $mysql_fetch_row[4];
        $device = $mysql_fetch_row[5];
    }
}

if (!isset($sCost)) {
    $sCost = $defaultCost;
}

if (!isset($waterfall)) {
    $waterfall = $deskWF;
}

if (!isset($sGeo)) {
    $sGeo = $tgtGeo;
}

if ($platform == "MediaMath") {
    $sSeller = $tgtSeller;
}

if ($platform == '' ) {
    $sPlatform = $defaultPlatform;
} else {
    $sPlatform = $platform;
}

if (!isset($sDeviceType)) {
    $sDeviceType = $device;
}

if (!isset($uConnectionType)) {
    $uConnectionType = $waterfall;
}

$tableName = 'Impression' . idate("d") . idate("m") . 'n';
if ((time() % 10) == 0) {
    mysqli_query($con, "CREATE TABLE IF NOT EXISTS $tableName LIKE Impression");
}

$str = "INSERT INTO $tableName (uBrowserVer,uBrowser,uDevice,uOSVer,uOS,sDeviceType, sType, sPlatform, sSeller, sPlacement, sBid, sCost, sDomain, sGeo, sSize, sUserID, uAgent, uIP, uDeviceID, uAllowCookies, uConnectionType, uCarrier, uGeo, dHost, dReferer, dMaskedIndex, cGeo, pReady, pErrorIndex, pSize, pInetiation, pSound, pRandering, pContentId, pContent, adTagURL, adTagViewed,sCampaignID,tag,sPlatformID,dPath,tagid,sPublisher,topDomain,lrPartner,creativeID,creativeCode, cpgID, sState, sCity,invSourceID)
values ('$uBrowserVer','$uBrowser','$uDevice','$uOSVer','$uOS','$sDeviceType', '$sType', '$sPlatform', '$sSeller', '$sPlacement', '$sBid', '$sCost', '$sDomain', '$sGeo', '$sSize', '$sUserID', '$uAgent', '$uIP', '$uDeviceID', '$uAllowCookies', '$uConnectionType', '$uCarrier', '$uGeo', '$dHost', '$dReferer', '$dMaskedIndex', '$cGeo', '$pReady', '$pErrorIndex', '$pSize', '$pInetiation', '$pSound', '$pRandering', '$pContentId', '$pContent', '$adTagURL', '$adTagViewed','$sCampaignID','$tag','$sPlatformID','$dPath','$tagid','$sPublisher','$topDomain','$lrParner','$creativeID','$audit', '$cpgID', '$userSate', '$userCity', '$invSourceID')";

mysqli_query($con, $str);
$id = mysqli_insert_id($con);



