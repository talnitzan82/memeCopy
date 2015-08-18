<?php
date_default_timezone_set('UTC');

require_once '/opt/composer/vendor/autoload.php';
use UAParser\Parser;

$parser = Parser::create();
$result = $parser->parse($_SERVER['HTTP_USER_AGENT']);

/*print_r($result);

// ==========================================================================
echo "\n"."<BR>".'======================'."\n"."<BR>";
// ==========================================================================

print "->ua->family:".$result->ua->family."\n"."<BR>";            // Safari
print "->ua->major:".$result->ua->major."\n"."<BR>";             // 6
print "->ua->minor:".$result->ua->minor."\n"."<BR>";             // 0
print "->ua->patch:".$result->ua->patch."\n"."<BR>";             // 2
print "->ua->toString:".$result->ua->toString()."\n"."<BR>";        // Safari 6.0.2
print "->ua->toVersion:".$result->ua->toVersion()."\n"."<BR>";       // 6.0.2

print "->os->family:".$result->os->family."\n"."<BR>";            // Mac OS X
print "->os->major:".$result->os->major."\n"."<BR>";             // 10
print "->os->minor:".$result->os->minor."\n"."<BR>";             // 7
print "->os->patch:".$result->os->patch."\n"."<BR>";             // 5
print "->os->patchMinor:".$result->os->patchMinor."\n"."<BR>";        // [null]
print "->os->toString:".$result->os->toString()."\n"."<BR>";        // Mac OS X 10.7.5
print "->os->toVersion:".$result->os->toVersion()."\n"."<BR>";       // 10.7.5

print "->dev->brand:".$result->device->brand."\n"."<BR>";        // Other
print "->dev->model:".$result->device->model."\n"."<BR>";        // Other
print "->dev->family:".$result->device->family."\n"."<BR>";        // Other
print "->dev->toString:".$result->device->toString()."\n"."<BR>";        // Other

print "->res->toString:".$result->toString()."\n"."<BR>";            // Safari 6.0.2/Mac OS X 10.7.5
print "->res->originalUserAgent:".$result->originalUserAgent."\n"."<BR>";     // Mozilla/5.0 (Macintosh; Intel Ma...*/

/*
 * uAgent,
 * uOS,         $result->os->family
 * uOSVer,      $result->os->toString()
 * uDevice,     $result->device->toString()
 * uBrowser,    $result->ua->family
 * uBrowserVer  $result->ua->toVersion()
 *
 */

$uOS = $result->os->family;
$uOSVer = $result->os->toVersion();
$uDevice = $result->device->toString();
$uBrowser = $result->ua->family;
$uBrowserVer = $result->ua->toVersion();
//
