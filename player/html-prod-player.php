<?php
$dPath = 'player-memeCopy.php';
include '../db.php';
include 'storeRequest-html-prod.php';
include 'server/getWaterFall.php';
?>

    <?php echo "var id =".$id.";\n";?>
    <?php echo "var audit=".$audit.";\n";?>
    <?php echo "var iframe='".$iframe."';\n";?>
    <?php echo "var lrpartner='".$lrPartner."';\n";?>
    <?php echo "var sCampaignID='".$sCampaignID."';\n";?>
    <?php echo "var isAutostart='".$autostart."';\n";?>
    <?php echo "var tags='".$tags."';\n";?>
    <?php echo "var creativeType='".$creativeType."';\n";?>
    <?php echo "var creativeMedia=\"".$creativeMedia."\";\n";?>
    <?php echo "var isGoodBoy=".$isGoodBoy.";\n";?>
    <?php echo "var getGeo=".$getGeo.";\n";?>
    <?php echo "var ourBBBDomain='".$ourDomain."';\n";?>
    <?php echo "var sPlatform='".$sPlatform."';\n";?>
    <?php echo "var sSeller='".$sSeller."';\n";?>
    <?php echo "var sPlacement='".$sPlacement."';\n";?>
    <?php echo "var myVideo = document.getElementById('video1');\n"?>
    var adsPlayed = 0;
    var path = "http://" + ourBBBDomain + "/sas/memeplayer/2/";
    function callAjax(url) {
        // Send stuff to server
        var xmlhttp;
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp=new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.open("GET", url , true);
        xmlhttp.send();
    }

    if (getGeo) {
        function getTopDomain() {
            return window.location.host;
        }
        function getsDomain() {
            return window.location.href;
        }

        function getCountry() {
            return $.ajax({
                type: "GET",
                url: "http://freegeoip.net/json/",
                async: false
            }).responseText;
        }

        var a = getCountry();
        var sGeo = jQuery.parseJSON(a).country_code;
        var topDomain = getTopDomain();
        var sDomain = getsDomain();
        var url = path + "insertGeo.php?id=" + id + "&geo=" + sGeo + "&topDomain=" + topDomain + "&sDomain=" + sDomain;
        callAjax(url);

    }
    var adCurrentlyPlaying = false;
    var retry = 30;

    var startTime = Date.now();
    var adImpresssionTime = Date.now();
    var impression = Date.now();
    var hadAnyImpression = false;
    function reportError(id, message, linenumber) {
        console.log(message);
        var myTrackingImg = document.createElement("img");
        myTrackingImg.style.display = "none";
        //var tag2 = encodeURIComponent(e.tag);
        myTrackingImg.src = path + "reportError.php?id=" + id + "&error=" + message + "&lineNumber=" + linenumber;
    };

    function fallback() {
        if (!hadAnyImpression) {
            getMainPlayer().remove();
            if (iframe == 1) {
                document.location.href = '<?php echo $fallbackUrl?>';
            } else {
                document.write("<SCRIPT SRC='<?php echo $fallbackUrl?>'></SCRIPT>");
            }
        }
    };

    window.onerror = function (message, url, lineNumber) {
        //reportError(id, message, lineNumber);
        return true;
    };

    function debug(msg) {
        try {
            console.log(msg);
        } catch (e) {
        }
    }

    (function () {
        try {
            window.alert = function (msg) {
                debug(msg);
            };
        } catch (e) {
        }
    })();

    (function () {
        var queryString = {};


        // Parse query string
        (function () {
            var result = {};
            var query = window.location.search.substring(1);
            var vars = query.split('&');

            for (var i = 0; i < vars.length; i++) {
                var pair = vars[i].split('=');
                result[decodeURIComponent(pair[0])] = decodeURIComponent(pair[1]);
            }
            queryString = result;
        })();

        // Detect flash


    })();
    // Returns the version of Internet Explorer or null
    // (indicating the use of another browser).
    function getInternetExplorerVersion() {
        var rv = null; // Return value assumes failure.

        if (navigator.appName == 'Microsoft Internet Explorer') {
            var ua = navigator.userAgent;
            var re = new RegExp("MSIE ([0-9]{1,}[\.0-9]{0,})");

            if (re.exec(ua) != null) {
                rv = parseFloat(RegExp.$1);
            }
        }

        return rv;
    }

    function isAndroid() {
        var ua = navigator.userAgent.toLowerCase();

        return (ua.indexOf("android") > -1);
    }

    function hasHLSSupport() {
        // return true;
        var ua = navigator.userAgent.toLowerCase();

        if (ua.indexOf("android") > -1) {
            return false;
        }

        var ieVersion = getInternetExplorerVersion();

        if (ieVersion != null) {
            if (ieVersion < 10) {
                return false;
            }
        }

        return true;
    }
    function getQueryVariables() {
        var result = {};

        var query = window.location.search.substring(1);
        var vars = query.split('&');

        for (var i = 0; i < vars.length; i++) {
            var pair = vars[i].split('=');

            result[decodeURIComponent(pair[0])] = decodeURIComponent(pair[1]);
        }

        return result;
    }

    function getBaseUrl() {
        var result = location.href.split("?")[0];

        var lastSlashIndex = result.lastIndexOf('/');

        return result.substr(0, lastSlashIndex);
    }


    function init_player() {
        var qs = getQueryVariables();


        var imageUrl = ""; // default
        var media_id = 0;
        var skin = null;
        var url = null;
        var autostart = false;
        var delayAutoStart = 0;
        var ads = true;
        var nextStep = null;
        var volume = null;
        var loop = "1";
        var tag = "default";
        var midrollinterval = 20;
        var prerollcount = 1;
        var postrollcount = 0;
        var auction_retries = 4;
        var playlistUrl = null;
        var played_prerolls = 0;
        var onmouseover = null;
        var dReferer = null;
        var mute = true;
        var click_url = '<?php echo $click_url ?>';
        var appnexusPixelID = null;
        var appnexusPixelContext = null;
        var sPlatform = null;
        var sCampaignID = null;
        var dHost = null;
        //var pHeight = $(window).innerHeight() + 5;
        //var pWidth = $(window).innerWidth() + 5;
        var pHeight = 252;
        var pWidth = 302;
        var video = "<?php echo $video?>";
        var sDomain = null;
        var dIframe = null;
        var protocoltype = "http";
        var pixelfired = 0;

        if ("click_url" in qs) {
            click_url = qs["click_url"];
        }

        if ("mute" in qs) {
            if (parseInt(qs["mute"]) == 1) {
                mute = true;
            }
        }

        if ("tag" in qs) {
            tag = qs["tag"];
        }

        if (tag === "daniel") {
            pHeight = pHeight + 5;
            pWidth = pWidth + 5;
        }

        if ("volume" in qs) {
            volume = parseInt(qs["volume"]);

            if (isNaN(volume))
                volume = null;
            else if (volume < 0)
                volume = 0;
            else if (volume > 100)
                volume = 100;
        } else {
            volume = 1;
        }

        if ("next" in qs) {
            nextStep = qs["next"];
        }

        if ("image" in qs) {
            imageUrl = qs["image"];
        }
        /*
         if ("dTitle" in qs) {
         title = qs["dTitle"];
         }
         */
        if ("sDomain" in qs) {
            sDomain = qs["sDomain"];
        }

        if ("autostart" in qs) {
            if (qs["autostart"] == "1") {
                autostart = true;
            }
        }

        if ("secure" in qs) {
            if (qs["autostart"] == "1") {
                protocoltype = "https";
            }
        }

        if ("loop" in qs) {
            if (qs["loop"] == "1") {
                loop = true;
            }
        }

        if ("appnexusPixelID" in qs) {
            appnexusPixelID = qs["appnexusPixelID"];
        }

        if ("appnexusPixelContext" in qs) {
            appnexusPixelContext = qs["appnexusPixelContext"];
        }

        if ("sPlatform" in qs) {
            sPlatform = qs["sPlatform"];
        }

        if ("sCampaignID" in qs) {
            sCampaignID = qs["sCampaignID"];
        }

        if ("dHost" in qs) {
            dHost = qs["dHost"];
        }
        if ("skin" in qs) {
            skin = qs["skin"];
        }
        if ("dReferer" in qs) {
            dReferer = qs["dReferer"];
        }
        if ("dIframe" in qs) {
            dIframe = qs["dIframe"];
        }

        function showBanner() {
            document.getElementById("video_container").style.display = "none";
            document.write("<a href=\"http:\/\/www.netflix.com\/Turbo\/?ref=meme\" target=\"blank\"><img src=\"http:\/\/search-creatives.s3.amazonaws.com\/eb\/dc\/f1\/ebdcf197354a17225d341236b26f8dda.jpg\" style=\"margin:0;padding:0\"><\/a>");
        }

        if (tag === "hadar") {
            video = "http://wpc.a8b5.edgecastcdn.net/80A8B5/treehouse/videos/AndroidFunFacts-Trailer-App-534.mp4?514809d2869d73b56fd93c115910035521cb69db6e24cc0a225b92a25dcc4c65eb2cb698ad405f9d0a64b283599d93dba24a7bebb52ea002ee6b05a91f16ad629a2a34f5c72ad8a03fb48083a8b2cb9b3d9239f3681d0a9fb5834fd9dad7c23048f899f8a28b0b7c14ebdb86";
        }

        if (audit > 10) {
            if (creativeType == 'video') {
                video = creativeMedia;
                jwplayer.repeat = false;
            } else {
                $('#mvp').replaceWith(creativeMedia);
            }
        }
        var contentEntry = {
            image: imageUrl,
            mediaid: media_id,
            sources: [{file: video, label: "1"}]
        };


        //set LR_ALLOW_RETRY, which is the number an auction is being retried, between 2 and 7
        if (auction_retries != null) {

            if (auction_retries <= 2) {
                auction_retries = 2;
            }
            else if (auction_retries >= 7) {
                auction_retries = 7;
            }
        }

        if (autostart && delayAutoStart > 0) {
            autostart = false;

            setTimeout(
                function () {
                    try {
                        jwplayer().play();
                    }
                    catch (e) {
                    }
                },
                delayAutoStart
            );
        }

        var primary = "flash";
        var flashVersion = null;

        (function () {
            var FlashDetect = new function () {
                var self = this;
                self.installed = false;
                self.raw = "";
                self.major = -1;
                self.minor = -1;
                self.revision = -1;
                self.revisionStr = "";
                var activeXDetectRules = [{
                    "name": "ShockwaveFlash.ShockwaveFlash.7", "version": function (obj) {
                        return getActiveXVersion(obj);
                    }
                }, {
                    "name": "ShockwaveFlash.ShockwaveFlash.6", "version": function (obj) {
                        var version = "6,0,21";
                        try {
                            obj.AllowScriptAccess = "always";
                            version = getActiveXVersion(obj);
                        } catch (err) {
                        }
                        return version;
                    }
                }, {
                    "name": "ShockwaveFlash.ShockwaveFlash", "version": function (obj) {
                        return getActiveXVersion(obj);
                    }
                }];
                var getActiveXVersion = function (activeXObj) {
                    var version = -1;
                    try {
                        version = activeXObj.GetVariable("$version");
                    } catch (err) {
                    }
                    return version;
                };
                var getActiveXObject = function (name) {
                    var obj = -1;
                    try {
                        obj = new ActiveXObject(name);
                    } catch (err) {
                        obj = {activeXError: true};
                    }
                    return obj;
                };
                var parseActiveXVersion = function (str) {
                    var versionArray = str.split(",");
                    return {
                        "raw": str,
                        "major": parseInt(versionArray[0].split(" ")[1], 10),
                        "minor": parseInt(versionArray[1], 10),
                        "revision": parseInt(versionArray[2], 10),
                        "revisionStr": versionArray[2]
                    };
                };
                var parseStandardVersion = function (str) {
                    var descParts = str.split(/ +/);
                    var majorMinor = descParts[2].split(/\./);
                    var revisionStr = descParts[3];
                    return {
                        "raw": str,
                        "major": parseInt(majorMinor[0], 10),
                        "minor": parseInt(majorMinor[1], 10),
                        "revisionStr": revisionStr,
                        "revision": parseRevisionStrToInt(revisionStr)
                    };
                };
                var parseRevisionStrToInt = function (str) {
                    return parseInt(str.replace(/[a-zA-Z]/g, ""), 10) || self.revision;
                };
                self.majorAtLeast = function (version) {
                    return self.major >= version;
                };
                self.minorAtLeast = function (version) {
                    return self.minor >= version;
                };
                self.revisionAtLeast = function (version) {
                    return self.revision >= version;
                };
                self.versionAtLeast = function (major) {
                    var properties = [self.major, self.minor, self.revision];
                    var len = Math.min(properties.length, arguments.length);
                    for (i = 0; i < len; i++) {
                        if (properties[i] >= arguments[i]) {
                            if (i + 1 < len && properties[i] == arguments[i]) {
                                continue;
                            } else {
                                return true;
                            }
                        } else {
                            return false;
                        }
                    }
                };
                self.FlashDetect = function () {
                    if (navigator.plugins && navigator.plugins.length > 0) {
                        var type = 'application/x-shockwave-flash';
                        var mimeTypes = navigator.mimeTypes;
                        if (mimeTypes && mimeTypes[type] && mimeTypes[type].enabledPlugin && mimeTypes[type].enabledPlugin.description) {
                            var version = mimeTypes[type].enabledPlugin.description;
                            var versionObj = parseStandardVersion(version);
                            self.raw = versionObj.raw;
                            self.major = versionObj.major;
                            self.minor = versionObj.minor;
                            self.revisionStr = versionObj.revisionStr;
                            self.revision = versionObj.revision;
                            self.installed = true;
                        }
                    } else if (navigator.appVersion.indexOf("Mac") == -1 && window.execScript) {
                        var version = -1;
                        for (var i = 0; i < activeXDetectRules.length && version == -1; i++) {
                            var obj = getActiveXObject(activeXDetectRules[i].name);
                            if (!obj.activeXError) {
                                self.installed = true;
                                version = activeXDetectRules[i].version(obj);
                                if (version != -1) {
                                    var versionObj = parseActiveXVersion(version);
                                    self.raw = versionObj.raw;
                                    self.major = versionObj.major;
                                    self.minor = versionObj.minor;
                                    self.revision = versionObj.revision;
                                    self.revisionStr = versionObj.revisionStr;
                                }
                            }
                        }
                    }
                }();
            };

            if (FlashDetect.installed) {
                flashVersion = FlashDetect.major + " " + FlashDetect.minor + " " + FlashDetect.revisionStr;
            }
            else {
                flashVersion = "na";
            }
        })();

        if (flashVersion === "na") {
            primary = "html5";
        }
        if (isAutostart == 1) {
            autostart = true;
        } else {
            autostart = false;
        }
        var playerConfig = {
            id: "mvp",
            autostart: autostart,
            smoothing: true,
            stagevideo: false,
            playlist: [contentEntry],
            width: pWidth,
            height: pHeight,
            primary: primary,
            wmode: "opaque",
            repeat: true,
            skin: skin,
            abouttext: "Media Group",
            aboutlink: "http://" + ourBBBDomain
        };

        window.setTimeout(function a() {
            if (primary == 'html5') {
                jwplayer("mvp").play(true);
            }
        }, 100);

        if (playlistUrl != null) {
            playerConfig.playlist = playlistUrl;
        }

        if (ads) {
            playerConfig.advertising = {client: "vast"}; //Need this part, for some reason
            if (sDomain == null) url = 'http://' + ourBBBDomain;

        }


        var title = sDomain;

        jwplayer('mvp').setup(playerConfig);

        (function () {
            function updatePlayerVolume() {
                if (mute) {
                    if (volume != null) {
                        if (jwplayer().getVolume() != volume) {
                            jwplayer().setVolume(volume);
                        }
                    }
                    jwplayer().setMute(true);
                }
                else if (volume != null) {
                    jwplayer().setVolume(volume);
                }
            }

            var set_volume_counter = 0;
            jwplayer().onAdImpression(function (e) {
                function setVolume() {
                    //if (!mute) {
                    updatePlayerVolume();
                    //}
                    //else {
                    if (set_volume_counter < 100) {
                        ++set_volume_counter;

                        setTimeout(setVolume, 100);
                    }
                    //}
                }
                //adtags.unshift(e.tag);
                setVolume();
            });

            updatePlayerVolume();
        })();

        function getRandom(min, max) {
            return Math.round(Math.random() * (max - min) + min);
        }

        var VID = getRandom(986532, 986632);
        var CID = getRandom(1000000, 99999999);
        var VIDu = getRandom(334, 449);

        function getLRURL(partner) {
            return "http://ad4.liverail.com/?LR_SCHEMA=vast2-vpaid&LR_PUBLISHER_ID=74257&LR_VIDEO_ID=" + VID + "&LR_TITLE=" + title + "&LR_AUTOPLAY=1&LR_CONTENT=1&LR_PARTNERS=" + partner;
        }

        function getSXURL(channel) {
            return "http://search.spotxchange.com/vast/2.00/" + channel + "?VPAID=1&content_page_url=" + sDomain + "&cb=" + CID + "&player_width=" + pWidth + "&player_height=" + pHeight + "&vid_duration=" + VIDu;
        }
        if (sDomain == null) {
            var sDomain = document.location.href;
        }
        var VIDurl = sDomain + "/media/sample.mp4"; // default
        var adapt = "http://ads.adaptv.advertising.com/a/h/VM7S8T2Q1W_IkrU1es7FYSysBNb2hTAb?cb=" + CID + "&pet=preroll&pageUrl=" + sDomain + "&eov=eov";
        var lr_78593_daniel = "http://ad4.liverail.com/?LR_SCHEMA=vast2-vpaid&LR_PUBLISHER_ID=74257&LR_VIDEO_ID=" + VID + "&LR_TITLE=" + title + "&LR_AUTOPLAY=1&LR_CONTENT=1&LR_PARTNERS=762624&LR_VIDEO_URL=" + VIDurl + "&LR_DESCRIPTION=" + id;
        var sx_100490_daniel = "http://search.spotxchange.com/vast/2.00/101919?VPAID=1&content_page_url=" + sDomain + "&cb=" + CID + "&player_width=" + pWidth + "&player_height=" + pHeight + "&vid_duration=" + VIDu;

        var bt_78593_daniel = "http://ad4.liverail.com/?LR_SCHEMA=vast2-vpaid&LR_PUBLISHER_ID=70634&LR_VIDEO_ID=" + VID + "&LR_TITLE=" + title + "&LR_AUTOPLAY=1&LR_CONTENT=1&LR_PARTNERS=762474&LR_VIDEO_URL=" + VIDurl;
        var lr_ron = "http://ad4.liverail.com/?LR_SCHEMA=vast2-vpaid&LR_PUBLISHER_ID=74257&LR_VIDEO_ID=" + VID + "&LR_TITLE=" + title + "&LR_AUTOPLAY=1&LR_CONTENT=1&LR_PARTNERS=762626&LR_VIDEO_URL=" + VIDurl;
        var lr_tal = "http://ad4.liverail.com/?LR_SCHEMA=vast2-vpaid&LR_PUBLISHER_ID=74257&LR_VIDEO_ID=" + VID + "&LR_TITLE=" + title + "&LR_AUTOPLAY=1&LR_CONTENT=1&LR_PARTNERS=771918&LR_VIDEO_URL=" + VIDurl;
        var sx_ron = "http://search.spotxchange.com/vast/2.00/101921?VPAID=1&content_page_url=" + sDomain + "&cb=" + CID + "&player_width=" + pWidth + "&player_height=" + pHeight + "&vid_duration=" + VIDu;
        var sx_ron_clean = "http://search.spotxchange.com/vast/2.00/98419?VPAID=1&content_page_url=" + sDomain + "&cb=" + CID + "&player_width=" + pWidth + "&player_height=" + pHeight + "&vid_duration=" + VIDu;
        var sx_daniel = "http://search.spotxchange.com/vast/2.00/98036?VPAID=1&content_page_url=" + sDomain + "&cb=" + CID + "&player_width=" + pWidth + "&player_height=" + pHeight + "&vid_duration=" + VIDu;

        var bt_ron = "http://ad4.liverail.com/?LR_SCHEMA=vast2-vpaid&LR_PUBLISHER_ID=70634&LR_VIDEO_ID=" + VID + "&LR_TITLE=" + title + "&LR_AUTOPLAY=1&LR_CONTENT=1&LR_PARTNERS=762476&LR_VIDEO_URL=" + VIDurl;

        var vastest = "http://search.spotxchange.com/vast/2.00/98508?VPAID=1&content_page_url=" + sDomain + "&cb=" + CID + "&player_width=" + pWidth + "&player_height=" + pHeight + "&vid_duration=" + VIDu;
        var lr_eran = "http://ad4.liverail.com/?LR_SCHEMA=vast2-vpaid&LR_PUBLISHER_ID=74257&LR_VIDEO_ID=" + VID + "&LR_TITLE=" + title + "&LR_AUTOPLAY=1&LR_CONTENT=1&LR_PARTNERS=762625&LR_VIDEO_URL=" + VIDurl;
        var sx_eran = "http://search.spotxchange.com/vast/2.00/101920?VPAID=1&content_page_url=" + sDomain + "&cb=" + CID + "&player_width=" + pWidth + "&player_height=" + pHeight + "&vid_duration=" + VIDu;


        var bt_eran = "http://ad4.liverail.com/?LR_SCHEMA=vast2-vpaid&LR_PUBLISHER_ID=70634&LR_VIDEO_ID=" + VID + "&LR_TITLE=" + title + "&LR_AUTOPLAY=1&LR_CONTENT=1&LR_PARTNERS=762475&LR_VIDEO_URL=" + VIDurl;
        var coull = "http://radar.network.coull.com/radar?pid=13213&ad_type=in&categories=&tags=&video_id=" + VID + "&video_autoplay=1&video_duration=38&video_stream_url=" + VIDurl + "&video_muted=1&player_width=" + pWidth + "&player_height=" + pHeight + "&page_url=" + sDomain + "&video_title=TOS&video_description=TOS";
        var integiAbout = "http://ads.intergi.com/adrawdata/3.0/5205/3371066/0/1013/ADTECH;cors=yes;width=__WIDTH__;height=__HEIGHT__;referring_url=" + sDomain + ";content_url=__CONTENT_URL__;media_id=__MEDIA_ID__;title=__TITLE__;device=__DEVICE__;model=__MODEL__;os=__OS__;osversion=__OSVERSION__;ua=__UA__;ip=__IP__;uniqueid:__UNIQUEID__;tags=__TAGS__;number=" + CID + ";time=__TIME__";
        var integiHSW = "http://ads.intergi.com/adrawdata/3.0/5205/3371488/0/1013/ADTECH;cors=yes;width=__WIDTH__;height=__HEIGHT__;referring_url=" + sDomain + ";content_url=__CONTENT_URL__;media_id=__MEDIA_ID__;title=__TITLE__;device=__DEVICE__;model=__MODEL__;os=__OS__;osversion=__OSVERSION__;ua=__UA__;ip=__IP__;uniqueid:__UNIQUEID__;tags=__TAGS__;number=" + CID + ";time=__TIME__";
        var integiLATIMES = "http://ads.intergi.com/adrawdata/3.0/5205/3373393/0/1013/ADTECH;cors=yes;width=__WIDTH__;height=__HEIGHT__;referring_url=" + sDomain + ";content_url=__CONTENT_URL__;media_id=__MEDIA_ID__;title=__TITLE__;device=__DEVICE__;model=__MODEL__;os=__OS__;osversion=__OSVERSION__;ua=__UA__;ip=__IP__;uniqueid:__UNIQUEID__;tags=__TAGS__;number=" + CID + ";time=__TIME__";
        var altitude = "http://vpc.altitude-arena.com/vpc.xml?uid=nkla0ec262483745&page_url=" + sDomain + "&cb=" + CID + "&ref_page_url=" + sDomain + "&player_width=" + pWidth + "&player_height=" + pHeight + "&video_duration=" + VIDu + "&media_file_url=&media_description=GREATMOVIE&media_file_id=" + VID;
        var adkarmaUS = "http://u-ads.adap.tv/a/h/GbQESHwx03odG3a1wdcfCJOMXdm_pRPBNkXBMxzqp9M=?cb=" + CID + "&pageUrl=" + sDomain + "&description=VIDEO_DESCRIPTION&duration=" + VIDu + "&id=" + VID + "&keywords=VIDEO_KEYWORDS&title=VIDEO_TITLE&url=" + VIDurl + "&eov=eov&context=" + sDomain;
        var DMG = "http://ad4.liverail.com/?LR_PUBLISHER_ID=107962&LR_SCHEMA=vast2-vpaid&LR_TITLE=1&LR_VIDEO_ID=1";
        /*
         LR TEST TAG - "http://ad3.liverail.com/?LR_PUBLISHER_ID=1331&LR_CAMPAIGN_ID=229&LR_SCHEMA=vast2"
         */


        var adtags = [lr_ron, coull, adapt, integiAbout, integiHSW, integiLATIMES];
//    var adtags = [sx_100490_daniel, lr_78593_daniel,adapt];

        /*
         if (tag === "fredi2430") {
         adtags = ["http://search.spotxchange.com/vast/2.00/102757?VPAID=1&content_page_url=" + sDomain + "&cb=" + CID + "&player_width=" + pWidth + "&player_height=" + pHeight + "&vid_duration=" + VIDu];
         }
         */

        if (tag === "daniel") {
            adtags = [lr_ron, coull, altitude, integiAbout, integiHSW, integiLATIMES];
            if (sPlatform === "openx") {
                adtags = [sx_daniel, lr_ron, coull, adapt, altitude, integiAbout, integiHSW, integiLATIMES];
            }
        }
        if (tag === "chacha") {
            adtags = [lr_ron, sx_daniel, coull, adapt, altitude, integiAbout, integiHSW, integiLATIMES];
        }
        if (tag === "ron" || tag === "ISron") {
            adtags = [lr_ron, coull, altitude, adkarmaUS, DMG,integiAbout, integiHSW, integiLATIMES];

        }
        if (tag === "tal") {
            adtags = [getLRURL(lrpartner), coull, altitude, adkarmaUS,DMG,integiAbout, integiHSW, integiLATIMES];
        }
        if (tag === "ronclean") {
            adtags = [sx_ron_clean, lr_ron, coull, altitude, integiAbout, integiHSW, integiLATIMES];
        }

        if (tag === "eran") {
            adtags = [lr_eran];
        }

        /*
         if (tag === "fredi2436") {
         adtags = ["http://search.spotxchange.com/vast/2.00/102758?VPAID=1&content_page_url=" + sDomain + "&cb=" + CID + "&player_width=" + pWidth + "&player_height=" + pHeight + "&vid_duration=" + VIDu];
         }

         if (tag === "fredinj60") {
         adtags = ["http://search.spotxchange.com/vast/2.00/102764?VPAID=1&content_page_url=" + sDomain + "&cb=" + CID + "&player_width=" + pWidth + "&player_height=" + pHeight + "&vid_duration=" + VIDu];
         }
         */

        if (tag === "hadar") {
            adtags = ["http://ad3.liverail.com/?LR_PUBLISHER_ID=1331&LR_CAMPAIGN_ID=229&LR_SCHEMA=vast2"];
        }
        if (tag === "omd") {
            adtags = ["http://bs.serving-sys.com/BurstingPipe/adServer.bs?cn=is&c=23&pl=VAST&pli=12490037&PluID=0&pos=1068&ord=[timestamp]&cim=1"];
        }

        if (tag == "firstOffer") {
            adtags = [getLRURL(762626), getSXURL(101921)]
        }

        if (tags) {

            adtagsJson = JSON.parse(tags);
            adtags = [];
            for (var item in adtagsJson.data) {
                adtags.push(adtagsJson.data[item].tag);
            }

            for (var i = 0; i < adtags.length; i++) {
                adtags[i] = adtags[i].replace(/<VID>/g, VID);
                adtags[i] = adtags[i].replace(/<title>/g, title);
                adtags[i] = adtags[i].replace(/<sDomain>/g, sDomain);
                adtags[i] = adtags[i].replace(/<VIDurl>/g, VIDurl);
                adtags[i] = adtags[i].replace(/<CID>/g, CID);
                adtags[i] = adtags[i].replace(/<pHeight>/g, pHeight);
                adtags[i] = adtags[i].replace(/<pWidth>/g, pWidth);
                adtags[i] = adtags[i].replace(/<VIDu>/g, VIDu);
                adtags[i] = adtags[i].replace(/<ID>/g, id);
            }
        }

        if (audit > 10) {
            adtags = [];
//        adtags = ["http://u-ads.adap.tv/a/h/y2A0FW7vrbaCNAkO8UuILgT_gzraB+dE?cb=[CACHE_BREAKER]&pageUrl=test.com&description=VIDEO_DESCRIPTION&duration=VIDEO_DURATION&id=VIDEO_ID&keywords=VIDEO_KEYWORDS&title=VIDEO_TITLE&url=VIDEO_URL&eov=eov"];
//        adtags = ["http://search.spotxchange.com/vast/2.00/79391?VPAID=1&content_page_url=www.123.com&cb=57556703&player_width=305&player_height=255&vid_duration=369"];
        }


        function playAdNow() {
            console.log(adtags);
            if (adtags.length > 0) {
                jwplayer().playAd(adtags);
            }
        }

        jwplayer().onDisplayClick(function (e) {

            if (click_url != null) {
                try {
                    window.open(click_url, '_blank');
                    click_url = null; // click on the video is commenced only once

                }
                catch (e) {
                }
            }

        });


        //Preroll block

        jwplayer().onBeforePlay(function (e) {

            if (played_prerolls < prerollcount) {

                playAdNow();
                played_prerolls += 1;
            }

        });


        jwplayer().onPlaylistItem(function (e) {


            if (jwplayer().getPlaylistIndex() > 0) {
                console.log('%cPlaying middle pre-roll ', 'color: purple; font-size: large');
                if (isGoodBoy == 0 || adsPlayed<isGoodBoy) {
                    playAdNow();
                }
            }

        });


        //postroll block
        jwplayer().onBeforeComplete(function (e) {
            if (postrollcount > 0 && jwplayer().getPlaylistIndex() >= jwplayer().getPlaylist().length - 1) {

                //console.log('%cPlaying postroll ', 'color: purple; font-size: large');
                if (isGoodBoy == 0 || adsPlayed<isGoodBoy) {
                    playAdNow();
                }
            }
        });


        jwplayer().onAdComplete(function (e) {
            adsPlayed++;
            console.log('Ads Played: ' + adsPlayed);
            if (isGoodBoy == 0 || adsPlayed<isGoodBoy) {
                playAdNow();
            }
            hidePlayer();
        });


        //Midroll Block
        var adsLastAdPosition = 0;
        jwplayer().onTime(function (e) {
            //console.log("e.position: " + e.position + "; adsLastAdPosition + midrollinterval: " + (adsLastAdPosition + midrollinterval));

            if (midrollinterval != null && midrollinterval > 0) {
                if (e.position >= adsLastAdPosition + midrollinterval) {
                    adsLastAdPosition = Math.floor(e.position);


                    if (isGoodBoy == 0 || adsPlayed<isGoodBoy) {
                        playAdNow();
                    }

                }
            }
        });

        jwplayer().onSeek(function (e) {

            if (midrollinterval != null && midrollinterval > 0) {
                adsLastAdPosition = Math.floor(e.offset - (e.offset % midrollinterval));

                if (isGoodBoy == 0 || adsPlayed<isGoodBoy) {
                    playAdNow();
                }

            }
        });

        jwplayer().onReady(function () {
            //("player_setup_ready", [], [], null);
            readyTime = Date.now();

        });

        jwplayer().onSetupError(function (e) {
            //("player_setup_error", [], [], e.message);
            reportError(id, e.message);

        });


        jwplayer().onPlay(function (e) {

            //Enabling the controls on play

            jwplayer().setControls(true);

        });

        var isFirstImpression = true;
        var hadImppression = false;
        jwplayer().onAdImpression(function (e) {
            adImpresssionTime = Date.now();
            var duration = adImpresssionTime - startTime;
            jwplayer().setControls(false);

//            var myTrackingImg = document.createElement("img");
//            myTrackingImg.style.display = "none";
            var tag2 = encodeURIComponent(e.tag);

//            myTrackingImg.src = path + "reportview.php?id=" + id + "&tag=" + tag2 + "&duration=" + duration;

            //callAjax(path + "reportview.php?id=" + id + "&tag=" + tag2 + "&duration=" + duration);

            if (isFirstImpression) {
                //("player_ad_first_impression", [], [], e.tag);
                isFirstImpression = false;
            }

            hadImppression = true;
            hadAnyImpression = true;

        });

        jwplayer().onAdPlay( function(e) {
            var liveRail = "ad4.liverail";
            console.log(e.tag);
            if (e.tag.indexOf(liveRail) == -1) {
                //setTimeout(function(){
                    //adtags.unshift(e.tag);
//                    var myTrackingImg2 = document.createElement("img");
//                    myTrackingImg2.style.display = "none";
//                    myTrackingImg2.src = path + "reportviewNotLR.php?id=" + id + "&tag=" + e.tag;
                //}, 100);

                callAjax(path + "reportviewNotLR.php?id=" + id + "&tag=" + e.tag);
            }

            showPlayer();

            //console.log("on Ad Play: "  + e.tag);
        });


        jwplayer().onAdError(
            function err(result) {
                if(adtags[adtags.length-1].substring(0,20) == result.tag.substring(0,20)) {
                    if(retry>0) {
                        retry = retry -1;
                        setTimeout(playAdNow,3000);
                    }else {
                        fallback();
                    }
                }
                var liveRail = "ad4.liverail";
                if (adCurrentlyPlaying && result.tag.indexOf(liveRail) == -1) {
                    //setTimeout(function(){
//                        var myTrackingImg3 = document.createElement("img");
//                        myTrackingImg3.style.display = "none";
//                        myTrackingImg3.src = path + "reportviewNotLR.php?reduce=1&id=" + id + "&tag=" + result.tag;
                    //}, 200);

                    callAjax(path + "reportviewNotLR.php?reduce=1&id=" + id + "&tag=" + result.tag);
                }
                hidePlayer();
                //console.log("on Ad Error: "  + result.tag);
            }

        );

        // If no ad is served - fallback to banner


        jwplayer().onPlaylistComplete(function (e) {

            adsLastAdPosition = 0;

            if (loop) {
                var repeatLimit = null;

                if ("repeat" in qs) {
                    repeatLimit = parseInt(qs["repeat"]);

                    if (isNaN(repeatLimit))
                        repeatLimit = null;
                }

                var repeatCount = 0;

                if ("repeat.count" in qs) {
                    repeatCount = parseInt(qs["repeat.count"]);

                    if (isNaN(repeatCount))
                        repeatCount = 0;
                    else if (repeatCount < 0)
                        repeatCount = 0;
                }

                var doRepeat = false;

                ++repeatCount;
                qs["repeat.count"] = repeatCount;

                if (repeatLimit == null)
                    doRepeat = true;
                else
                    doRepeat = (repeatCount < repeatLimit);

                if (doRepeat) {


                    setTimeout(
                        function () {
                            var pageUrl = window.location.href.toString().split("?")[0];
                            var queryString = "";

                            for (var qsKey in qs) {
                                if (queryString.length > 0)
                                    queryString += "&";

                                queryString += escape(qsKey);
                                queryString += "=";
                                queryString += escape(qs[qsKey]);
                            }

                            pageUrl = pageUrl + "?" + queryString;

                            window.location.href = pageUrl;
                        },
                        100
                    );
                } else {
                    fallback();
                }
            } else {
                fallback();
            }
        });




        (function () {
            var muteTimeout = null;

            $("#video_container").mouseover(function (event) {

                if (onmouseover == "unmute" && mute) {
                    try {
                        muteTimeout = setTimeout(function () {

                                mute = false;
                                jwplayer().setMute(false);
                            },
                            2000);
                    }
                    catch (e) {
                        // console.log('%cException ', 'color: purple; font-size: large', e);
                    }
                }
            });

            $("#video_container").mouseout(function (event) {
                clearTimeout(muteTimeout);
            });
        })();
    }

    init_player();

    if (audit <10) {
        setTimeout(function(){
            hidePlayer();
        }, 1000);
    }

    function showPlayer() {
        $('#mvp_wrapper').css({'width':'302','height':'252'});
        $('#mvp_wrapper object').css({'width':'302','height':'252'});
        $('#placeholder').hide();
        if (myVideo!= null) {
            myVideo.pause();
        }
        adCurrentlyPlaying = true;
    };

    function hidePlayer() {
        $('#mvp_wrapper').css({'width':'0','height':'0'});
        $('#mvp_wrapper object').css({'width':'0','height':'0'});
        $('#placeholder').show();
        if (myVideo!= null) {
            myVideo.play();
        }
        adCurrentlyPlaying = false;
    };



  /*  window.onbeforeunload = function (e) {
        var leavePage = Date.now();
        var timeSpent = leavePage - startTime;
        var timeSinceReady = readyTime - startTime;
        var adTime = leavePage - adImpresssionTime;


        var myTrackingImg = document.createElement("img");
        myTrackingImg.style.display = "none";
        var tag2 = path + "reporttime.php?id=" + id + '&time=' + timeSpent + '&adTime=' + adTime + '&readyTime=' + timeSinceReady;
        myTrackingImg.src = tag2;

    };
        */
