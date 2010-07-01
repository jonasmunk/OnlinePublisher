function browserSniffer() {
    // convert all characters to lowercase to simplify testing
    var agt=navigator.userAgent.toLowerCase();
    var appVer = navigator.appVersion.toLowerCase();




// ======================================= BROWSER VERSION ========================================

    this.minorVersion = parseFloat(appVer);
    this.majorVersion = parseInt(this.minorVersion);



// ============================================ OPERA =============================================

    this.opera = (agt.indexOf("opera") != -1);
    this.opera2 = (agt.indexOf("opera 2") != -1 || agt.indexOf("opera/2") != -1);
    this.opera3 = (agt.indexOf("opera 3") != -1 || agt.indexOf("opera/3") != -1);
    this.opera4 = (agt.indexOf("opera 4") != -1 || agt.indexOf("opera/4") != -1);
    this.opera5 = (agt.indexOf("opera 5") != -1 || agt.indexOf("opera/5") != -1);
    this.opera6 = (agt.indexOf("opera 6") != -1 || agt.indexOf("opera/6") != -1);
    this.opera7 = (agt.indexOf("opera 7") != -1 || agt.indexOf("opera/7") != -1);
    this.opera5up = (this.opera && !this.opera2 && !this.opera3 && !this.opera4);
    this.opera6up = (this.opera && !this.opera2 && !this.opera3 && !this.opera4 && !this.opera5);
    this.opera7up = (this.opera && !this.opera2 && !this.opera3 && !this.opera4 && !this.opera5 && !this.opera6);






// ========================================== Konqueror ===========================================
                                      
    this.konqueror = false;
    var kqPos   = agt.indexOf('konqueror');
    if (kqPos !=-1) {                 
       this.konqueror  = true;
       this.minorVersion = parseFloat(agt.substring(kqPos+10,agt.indexOf(';',kqPos)));
       this.majorVersion = parseInt(this.minorVersion);
    }






// ======================================== Mac Browsers =========================================


    this.safari = ((agt.indexOf('safari')!=-1)&&(agt.indexOf('mac')!=-1))?true:false;
    this.khtml  = (this.safari || this.konqueror);
    this.omniweb = (agt.indexOf('omniweb')!=-1);
    this.applewebkit = (agt.indexOf('applewebkit')!=-1);
    this.applewebkitVersion = -1;
    if(this.applewebkit) {
        var actual_index = agt.indexOf("applewebkit");
        this.applewebkitVersion = parseFloat(agt.substring(actual_index + 12, actual_index + 15));
    }
    this.camino = (agt.indexOf('camino')!=-1);
    this.chimera = (agt.indexOf('chimera')!=-1);
    this.icab = (agt.indexOf('icab')!=-1);







// ============================================ Gecko ============================================


    this.gecko = ((!this.khtml)&&(navigator.product)&&(navigator.product.toLowerCase()=="gecko"))?true:false;
    this.geckoVersion  = 0;
    if (this.gecko) this.geckoVersion=navigator.productSub;


// ============================================ Mozilla ============================================


    this.mozilla   = ((agt.indexOf('mozilla/5')!=-1) && (agt.indexOf('spoofer')==-1) &&
                    (agt.indexOf('compatible')==-1) && (agt.indexOf('opera')==-1) &&
                    (agt.indexOf('webtv')==-1) && (agt.indexOf('hotjava')==-1) &&
                    (this.gecko) &&
                    ((navigator.vendor=="")||(navigator.vendor=="Mozilla")));
    if (this.mozilla) {
       this.mozillaVersion = (navigator.vendorSub)?navigator.vendorSub:0;
       if(!(this.mozillaVersion)) {
           this.mozillaVersion = agt.indexOf('rv:');
           this.mozillaVersion = agt.substring(this.mozillaVersion+3);
           is_paren   = this.mozillaVersion.indexOf(')');
           this.mozillaVersion = this.mozillaVersion.substring(0,is_paren);
       }
       this.minorVersion = this.mozillaVersion;
       this.majorVersion = parseInt(this.mozillaVersion);
    }

// ============================================ Netscape ============================================


    this.netscape  = ((agt.indexOf('mozilla')!=-1) &&
                     (agt.indexOf('spoofer')==-1) &&
                     (agt.indexOf('compatible') == -1) &&
                     (agt.indexOf('opera')==-1) &&
                     (agt.indexOf('webtv')==-1) &&
                     (agt.indexOf('hotjava')==-1) &&
                     (!this.khtml) &&
                     (!(this.mozilla)));


    // Netscape6 is mozilla/5 + Netscape6/6.0!!!
    // Mozilla/5.0 (Windows; U; Win98; en-US; m18) Gecko/20001108 Netscape6/6.0
    if ((navigator.vendor)&&
        ((navigator.vendor=="Netscape6")||(navigator.vendor=="Netscape"))&&
        (this.netscape)) {
       this.majorVersion = parseInt(navigator.vendorSub);
       // here we need this.minorVersion as a valid float for testing. We'll
       // revert to the actual content before printing the result. 
       this.minorVersion = parseFloat(navigator.vendorSub);
    }

    this.netscape2 = (this.netscape && (this.majorVersion == 2));
    this.netscape3 = (this.netscape && (this.majorVersion == 3));
    this.netscape4 = (this.netscape && (this.majorVersion == 4));
    this.netscape4up = (this.netscape && this.minorVersion >= 4);
    this.netscapeNavigatorOnly      = (this.netscape && ((agt.indexOf(";nav") != -1) || (agt.indexOf("; nav") != -1)) );

    this.netscape6   = (this.netscape && this.majorVersion==6);    
    this.netscape6up = (this.netscape && this.minorVersion >= 6);

    this.netscape5   = (this.netscape && this.majorVersion == 5 && !this.netscape6);
    this.netscape5up = (this.netscape && this.minorVersion >= 5);

    this.netscape7   = (this.netscape && this.majorVersion == 7);
    this.netscape7up = (this.netscape && this.minorVersion >= 7);

    if (this.netscape6up) {
       this.minorVersion = navigator.vendorSub;
    }




// ======================================== Internet Explorer =========================================


    // Note: On IE, start of appVersion return 3 or 4 
    // which supposedly is the version of Netscape it is compatible with.
    // So we look for the real version further on in the string
    var iePos  = appVer.indexOf('msie');
    if (iePos !=-1) {
       this.minorVersion = parseFloat(appVer.substring(iePos+5,appVer.indexOf(';',iePos)))
       this.majorVersion = parseInt(this.minorVersion);
    }

    this.ie   = ((iePos!=-1) && (!this.opera) && (!this.khtml));
    this.ie3  = (this.ie && (this.majorVersion < 4));

    this.ie4   = (this.ie && this.majorVersion == 4);
    this.ie4up = (this.ie && this.minorVersion >= 4);
    this.ie5   = (this.ie && this.majorVersion == 5);
    this.ie5up = (this.ie && this.minorVersion >= 5);
    
    this.ie5_5  = (this.ie && (agt.indexOf("msie 5.5") !=-1));
    this.ie5_5up =(this.ie && this.minorVersion >= 5.5);
    
    this.ie6   = (this.ie && this.majorVersion == 6);
    this.ie6up = (this.ie && this.minorVersion >= 6);





// =============================================== AOL ================================================

    // KNOWN BUG: On AOL4, returns false if IE3 is embedded browser
    // or if this is the first browser window opened.  Thus the
    // variables this.aol, this.aol3, and this.aol4 aren't 100% reliable.

    this.aol   = (agt.indexOf("aol") != -1);
    this.aol3  = (this.aol && this.ie3);
    this.aol4  = (this.aol && this.ie4);
    this.aol5  = (agt.indexOf("aol 5") != -1);
    this.aol6  = (agt.indexOf("aol 6") != -1);
    this.aol7  = ((agt.indexOf("aol 7")!=-1) || (agt.indexOf("aol7")!=-1));
    this.aol8  = ((agt.indexOf("aol 8")!=-1) || (agt.indexOf("aol8")!=-1));




// ============================================= OTHERS ===============================================

    this.webtv = (agt.indexOf("webtv") != -1);
        
    this.tvNavigator = ((agt.indexOf("navio") != -1) || (agt.indexOf("navio_aoltv") != -1)); 
    this.aolTv = this.tvNavigator;

    this.hotjava = (agt.indexOf("hotjava") != -1);
    this.hotjava3 = (this.hotjava && (this.majorVersion == 3));
    this.hotjava3up = (this.hotjava && (this.majorVersion >= 3));
    



// =================================== JAVASCRIPT VERSION CHECK ======================================

    // Useful to workaround Nav3 bug in which Nav3 loads <SCRIPT LANGUAGE="JavaScript1.2">.

    this.javaScriptVersion = 0.0;
    this.ecmaScriptVersion = -1;
    if (this.netscape2 || this.ie3) this.javaScriptVersion = 1.0;
    else if (this.netscape3) this.javaScriptVersion = 1.1;
    else if (this.opera5 || this.opera6) this.javaScriptVersion = 1.3;
    else if (this.opera7up) this.javaScriptVersion = 1.5;
    else if (this.khtml) this.javaScriptVersion = 1.5;
    else if (this.opera) this.javaScriptVersion = 1.1;
    else if ((this.netscape4 && (this.minorVersion <= 4.05)) || this.ie4) this.javaScriptVersion = 1.2;
    else if (this.netscape4 && (this.minorVersion > 4.05)) this.javaScriptVersion = 1.3;
    else if (this.netscape5 && !(this.netscape6)) this.javaScriptVersion = 1.4;
    else if (this.hotjava3up) this.javaScriptVersion = 1.4;
    else if (this.netscape && (this.majorVersion > 5)) this.javaScriptVersion = 1.4;
    else if (this.netscape6up) {
        this.javaScriptVersion = 1.5;
        this.ecmaScriptVersion = 3;
        }
    else if (this.ie4) {
        this.javaScriptVersion = 1.2;
        this.ecmaScriptVersion = 1;
        }
    else if (this.ie5) {
        this.javaScriptVersion = 1.3;
        this.ecmaScriptVersion = 2;
        }
    else if (this.ie5_5 || this.ie6up) {
        this.javaScriptVersion = 1.5;
        this.ecmaScriptVersion = 3;
        }
    else if (this.mozilla) {
        this.javaScriptVersion = 1.5;
        this.ecmaScriptVersion = 3;
        }
    
    // ??? what about ie6 and ie6up for js version? abk ???
    
    // HACK FOR IE5 MAC = js vers = 1.4 (if put inside if/else jumps out at 1.3)
    if ((agt.indexOf("mac")!=-1) && this.ie5up) this.javaScriptVersion = 1.4;


// ============================================ PLATFORM ===============================================



// --------- Windows --------

    this.win   = ( (agt.indexOf("win")!=-1) || (agt.indexOf("16bit")!=-1) );
    // On Opera 3.0, the userAgent string includes "Windows 95/NT4" on all Win32,
    // so you can't distinguish between Win95 and WinNT.
    this.win95 = ((agt.indexOf("win95")!=-1) || (agt.indexOf("windows 95")!=-1));

    this.win16bit = ((agt.indexOf("win16")!=-1) ||
                     (agt.indexOf("16bit")!=-1) ||
                     (agt.indexOf("windows 3.1")!=-1) ||
                     (agt.indexOf("windows 16-bit")!=-1));

    this.win3_1 = ((agt.indexOf("windows 3.1")!=-1) ||
                  (agt.indexOf("win16")!=-1) ||
                  (agt.indexOf("windows 16-bit")!=-1));
    
    this.winMe = ((agt.indexOf("win 9x 4.90")!=-1));
    this.win2k = ((agt.indexOf("windows nt 5.0")!=-1) || (agt.indexOf("windows 2000")!=-1));
    this.winXp = ((agt.indexOf("windows nt 5.1")!=-1) || (agt.indexOf("windows xp")!=-1));

    // Reliable detection of Win98 may not be possible. It appears that:
    //       - On Nav 4.x and before you'll get plain "Windows" in userAgent.
    //       - On Mercury client, the 32-bit version will return "Win98", but
    //         the 16-bit version running on Win98 will still return "Win95".

    this.win98 = ((agt.indexOf("win98")!=-1) || (agt.indexOf("windows 98")!=-1));
    this.winNt = ((agt.indexOf("winnt")!=-1) || (agt.indexOf("windows nt")!=-1));

    this.win32bit = (this.win95 || this.winNt || this.win98 ||
                    ((this.majorVersion >= 4) && (navigator.platform == "Win32")) ||
                    (agt.indexOf("win32")!=-1) || (agt.indexOf("32bit")!=-1));

// --------- OS2 --------

    this.os2   = ((agt.indexOf("os/2")!=-1) ||
                  (appVer.indexOf("OS/2")!=-1) ||
                  (agt.indexOf("ibm-webexplorer")!=-1));

// --------- Mac --------

    this.mac = (agt.indexOf("mac")!=-1);

    if (this.mac) { this.win = !this.mac; } // if mac then not win

    if(this.ie && this.mac && (this.majorVersion==5)) { // special minor for ie on mac
        var actual_index = agt.indexOf("msie 5");
        this.minorVersion = parseFloat(agt.substring(actual_index + 5, actual_index + 8));
    }

    this.mac68k = (this.mac && ((agt.indexOf("68k")!=-1) || (agt.indexOf("68000")!=-1)));
    this.macppc = (this.mac && ((agt.indexOf("ppc")!=-1) || (agt.indexOf("powerpc")!=-1)));
    this.macosx = (agt.indexOf("mac os x")!=-1 || this.omniweb);

// --------- UNIX --------

    this.sun     = (agt.indexOf("sunos")!=-1);
    this.sun4    = (agt.indexOf("sunos 4")!=-1);
    this.sun5    = (agt.indexOf("sunos 5")!=-1);
    this.suni86  = (this.sun && (agt.indexOf("i86")!=-1));
    this.irix    = (agt.indexOf("irix") !=-1);    // SGI
    this.irix5   = (agt.indexOf("irix 5") !=-1);
    this.irix6   = ((agt.indexOf("irix 6") !=-1) || (agt.indexOf("irix6") !=-1));
    this.hpux    = (agt.indexOf("hp-ux")!=-1);
    this.hpux9   = (this.hpux && (agt.indexOf("09.")!=-1));
    this.hpux10  = (this.hpux && (agt.indexOf("10.")!=-1));
    this.aix     = (agt.indexOf("aix") !=-1);      // IBM
    this.aix1    = (agt.indexOf("aix 1") !=-1);
    this.aix2    = (agt.indexOf("aix 2") !=-1);
    this.aix3    = (agt.indexOf("aix 3") !=-1);
    this.aix4    = (agt.indexOf("aix 4") !=-1);
    this.linux   = (agt.indexOf("inux")!=-1);
    this.sco     = (agt.indexOf("sco")!=-1) || (agt.indexOf("unix_sv")!=-1);
    this.unixware= (agt.indexOf("unix_system_v")!=-1);
    this.mpras   = (agt.indexOf("ncr")!=-1);
    this.reliant = (agt.indexOf("reliantunix")!=-1);
    this.dec     = ((agt.indexOf("dec")!=-1) ||
                    (agt.indexOf("osf1")!=-1) ||
                    (agt.indexOf("dec_alpha")!=-1) ||
                    (agt.indexOf("alphaserver")!=-1) ||
                    (agt.indexOf("ultrix")!=-1) ||
                    (agt.indexOf("alphastation")!=-1));
    this.sinix   = (agt.indexOf("sinix")!=-1);
    this.freebsd = (agt.indexOf("freebsd")!=-1);
    this.bsd     = (agt.indexOf("bsd")!=-1);
    this.unix    = ((agt.indexOf("x11")!=-1) || this.sun || this.irix || this.hpux ||
                    this.sco || this.sco || this.sco || this.reliant ||
                    this.dec || this.sinix || this.aix || this.linux || this.bsd || this.freebsd);

    this.vms     = ((agt.indexOf("vax")!=-1) || (agt.indexOf("openvms")!=-1));





// ================================= Object detection ========================================


    this.object_getElementById   = (document.getElementById) ? "true" : "false";
    this.object_getElementsByTagName = (document.getElementsByTagName) ? "true" : "false";
    this.object_documentElement = (document.documentElement) ? "true" : "false";

    this.object_anchors = (document.anchors) ? "true":"false";
    this.object_all = (document.all) ? "true":"false";

    document.cookie = "cookies=true";
    this.object_cookie = (document.cookie) ? "true" : "false";
    this.object_images = (document.images) ? "true":"false";
    this.object_layers = (document.layers) ? "true":"false"; // gecko m7 bug?

    this.object_forms = (document.forms) ? "true" : "false";
    this.object_links = (document.links) ? "true" : "false";
    this.object_frames = (window.frames) ? "true" : "false";
    this.object_screen = (window.screen) ? "true" : "false";
    


// ================================= Method detection ========================================

    this.method_regexp = (window.RegExp) ? "true":"false";
    this.method_option = (window.Option) ? "true":"false";


// ================================== Java detection =========================================

    this.java = (navigator.javaEnabled());

    return this;
}




//==================================== PlugIn's =============================================




function getAdobeAcrobatVersion() {
    var agent = navigator.userAgent.toLowerCase(); 
    acrobatVersion = -1;
   
    if (navigator.plugins != null && navigator.plugins.length > 0) {
        for (i=0; i < navigator.plugins.length; i++ ) {
            var plugin =navigator.plugins[i];
            if (plugin.name.indexOf("Acrobat") > -1) {
                acrobatVersion = parseFloat(plugin.description.substring(plugin.description.indexOf(".")-1));
            }
        }

    }

    else if (agent.indexOf("msie") != -1 && parseInt(navigator.appVersion) >= 4 && agent.indexOf("win")!=-1 && agent.indexOf("16bit")==-1) {
      document.write(
         '<scr' + 'ipt language=VBScript>' + '\n' +
         'Dim hasPlayer, playerversion' + '\n' +
         'hasPlayer = false' + '\n' +
         'version = 10' + '\n' +
         'Do While version > 0' + '\n' +
            'On Error Resume Next' + '\n' +
            'hasPlayer = (IsObject(CreateObject("PDF.PdfCtrl." & version)))' + '\n' +
            'If hasPlayer = true Then Exit Do' + '\n' +
            'version = version - 1' + '\n' +
         'Loop' + '\n' +
         'acrobatVersion = version' + '\n' +
         '<\/sc' + 'ript>');
    }

    return acrobatVersion;
}

function getAdobeSvgVersion() {
    var agent = navigator.userAgent.toLowerCase(); 
    acrobatVersion = -1;
   
    if (navigator.plugins != null && navigator.plugins.length > 0) {
        for (i=0; i < navigator.plugins.length; i++ ) {
            var plugin =navigator.plugins[i];
            if (plugin.description.indexOf("Adobe SVG") > -1) {
                acrobatVersion = parseFloat(plugin.description.substring(plugin.description.indexOf(".")-1));
            }
        }

    }

    else if (agent.indexOf("msie") != -1 && parseInt(navigator.appVersion) >= 4 && agent.indexOf("win")!=-1 && agent.indexOf("16bit")==-1) {
//      document.write(
//         '<scr' + 'ipt language=VBScript>' + '\n' +
//         'Dim hasPlayer, playerversion' + '\n' +
//         'hasPlayer = false' + '\n' +
//         'version = 10' + '\n' +
//         'Do While version > 0' + '\n' +
//            'On Error Resume Next' + '\n' +
//            'hasPlayer = (IsObject(CreateObject("PDF.PdfCtrl." & version)))' + '\n' +
//            'If hasPlayer = true Then Exit Do' + '\n' +
//            'version = version - 1' + '\n' +
//         'Loop' + '\n' +
//         'acrobatVersion = version' + '\n' +
//         '<\/sc' + 'ript>');
    }

    return acrobatVersion;
}

function getFlashVersion() {
    var agent = navigator.userAgent.toLowerCase(); 
    flashVersion = -1;
   
    if (navigator.plugins != null && navigator.plugins.length > 0) {
        var plugin = (navigator.mimeTypes && 
                    navigator.mimeTypes["application/x-shockwave-flash"] &&
                    navigator.mimeTypes["application/x-shockwave-flash"].enabledPlugin) ?
                    navigator.mimeTypes["application/x-shockwave-flash"].enabledPlugin : 0;
        if (plugin) { 
            flashVersion = parseFloat(plugin.description.substring(plugin.description.indexOf(".")-1));
        }
    }

    else if (agent.indexOf("msie") != -1 && parseInt(navigator.appVersion) >= 4 && agent.indexOf("win")!=-1 && agent.indexOf("16bit")==-1) {
      document.write(
         '<scr' + 'ipt language=VBScript>' + '\n' +
         'Dim hasPlayer, playerversion' + '\n' +
         'hasPlayer = false' + '\n' +
         'version = 10' + '\n' +
         'Do While version > 0' + '\n' +
            'On Error Resume Next' + '\n' +
            'hasPlayer = (IsObject(CreateObject("ShockwaveFlash.ShockwaveFlash." & version)))' + '\n' +
            'If hasPlayer = true Then Exit Do' + '\n' +
            'version = version - 1' + '\n' +
         'Loop' + '\n' +
         'flashVersion = version' + '\n' +
         '<\/sc' + 'ript>');
    }
        
    // WebTV 2.5 supports flash 3
    else if (agent.indexOf("webtv/2.5") != -1) flashVersion = 3;

    // older WebTV supports flash 2
    else if (agent.indexOf("webtv") != -1) flashVersion = 2;

    return flashVersion;
}



function getQuickTimeVersion() {
    var bs = new browserSniffer();
    quicktimeVersion=-1
    if (navigator.plugins != null && navigator.plugins.length > 0) {
        for (i=0; i < navigator.plugins.length; i++ ) {
            var plugin =navigator.plugins[i];
            if (plugin.name.indexOf("QuickTime") > -1) {
                quicktimeVersion = parseFloat(plugin.name.substring(18));
                quicktimeVersion = parseFloat(plugin.name.substring(plugin.name.indexOf(".")-1));
            }
        }
    }
   
    else if (bs.ie4up && bs.win && !bs.win16bit) {
        document.write('<scr' + 'ipt language="VBScript"\> \n');
        document.write('on error resume next \n');
        document.write('dim obQuicktime \n');
        document.write('set obQuicktime = CreateObject("QuickTimeCheckObject.QuickTimeCheck.1") \n');
        document.write('if IsObject(obQuicktime) then \n');
        document.write('   if obQuicktime.IsQuickTimeAvailable(0) then \n');
        document.write('      quicktimeVersion = CDbl(Hex(obQuicktime.QuickTimeVersion) / 1000000) \n');
        document.write('   end if \n');
        document.write('end if \n');
        document.write('</scr' + 'ipt\> \n');
  }
    return quicktimeVersion;
}

javascriptVersion1_1 = true;
var detectableWithVB = false;
var pluginFound = false;

function canDetectPlugins() {
    if( detectableWithVB || (navigator.plugins && navigator.plugins.length > 0) ) {
        return true;
    } else {
        return false;
    }
}

function detectFlash() {
    pluginFound = detectPlugin('Shockwave','Flash'); 
    // if not found, try to detect with VisualBasic
    if(!pluginFound && detectableWithVB) {
        pluginFound = detectActiveXControl('ShockwaveFlash.ShockwaveFlash.1');
    }
    // check for redirection
    return pluginFound;
}

function detectDirector() { 
    pluginFound = detectPlugin('Shockwave','Director'); 
    // if not found, try to detect with VisualBasic
    if(!pluginFound && detectableWithVB) {
        pluginFound = detectActiveXControl('SWCtl.SWCtl.1');
    }
    // check for redirection
    return pluginFound;
}

function detectQuickTime() {
    pluginFound = detectPlugin('QuickTime');
    // if not found, try to detect with VisualBasic
    if(!pluginFound && detectableWithVB) {
        pluginFound = detectQuickTimeActiveXControl();
    }
    return pluginFound;
}

function detectAdobeSvg() {
    pluginFound = detectPlugin('Adobe SVG Viewer');
    // if not found, try to detect with VisualBasic
    if(!pluginFound && detectableWithVB) {
        pluginFound = detectActiveXControl('Adobe.SVGCtl');
    }
    return pluginFound;
}

function detectAdobeAcrobat() {
    pluginFound = detectPlugin('Adobe Acrobat');
    // if not found, try to detect with VisualBasic
    if(!pluginFound && detectableWithVB) {
        pluginFound = detectActiveXControl('PDF.PdfCtrl.5');
    }
    return pluginFound;
}

function detectReal() {
    pluginFound = detectPlugin('RealPlayer');
    // if not found, try to detect with VisualBasic
    if(!pluginFound && detectableWithVB) {
        pluginFound = (detectActiveXControl('rmocx.RealPlayer G2 Control') ||
                       detectActiveXControl('RealPlayer.RealPlayer(tm) ActiveX Control (32-bit)') ||
                       detectActiveXControl('RealVideo.RealVideo(tm) ActiveX Control (32-bit)'));
    }   
    return pluginFound;
}

function detectWindowsMedia() {
    pluginFound = detectPlugin('Windows Media Player');
    // if not found, try to detect with VisualBasic
    if(!pluginFound && detectableWithVB) {
        pluginFound = detectActiveXControl('MediaPlayer.MediaPlayer.1');
    }
    return pluginFound;
}

function detectPlugin() {
    // allow for multiple checks in a single pass
    var daPlugins = detectPlugin.arguments;
    // consider pluginFound to be false until proven true
    var pluginFound = false;
    // if plugins array is there and not fake
    if (navigator.plugins && navigator.plugins.length > 0) {
        var pluginsArrayLength = navigator.plugins.length;
        // for each plugin...
        for (pluginsArrayCounter=0; pluginsArrayCounter < pluginsArrayLength; pluginsArrayCounter++ ) {
            // loop through all desired names and check each against the current plugin name
            var numFound = 0;
            for(namesCounter=0; namesCounter < daPlugins.length; namesCounter++) {
                // if desired plugin name is found in either plugin name or description
                if( (navigator.plugins[pluginsArrayCounter].name.indexOf(daPlugins[namesCounter]) >= 0) || 
                    (navigator.plugins[pluginsArrayCounter].description.indexOf(daPlugins[namesCounter]) >= 0) ) {
                    // this name was found
                    numFound++;
                }   
            }
            // now that we have checked all the required names against this one plugin,
            // if the number we found matches the total number provided then we were successful
            if(numFound == daPlugins.length) {
                pluginFound = true;
                // if we've found the plugin, we can stop looking through at the rest of the plugins
                break;
            }
        }
    }
    return pluginFound;
} // detectPlugin


// Here we write out the VBScript block for MSIE Windows
if ((navigator.userAgent.indexOf('MSIE') != -1) && (navigator.userAgent.indexOf('Win') != -1)) {
    document.writeln('<script language="VBscript">');

    document.writeln('\'do a one-time test for a version of VBScript that can handle this code');
    document.writeln('detectableWithVB = False');
    document.writeln('If ScriptEngineMajorVersion >= 2 then');
    document.writeln('  detectableWithVB = True');
    document.writeln('End If');

    document.writeln('\'this next function will detect most plugins');
    document.writeln('Function detectActiveXControl(activeXControlName)');
    document.writeln('  on error resume next');
    document.writeln('  detectActiveXControl = False');
    document.writeln('  If detectableWithVB Then');
    document.writeln('     detectActiveXControl = IsObject(CreateObject(activeXControlName))');
    document.writeln('  End If');
    document.writeln('End Function');

    document.writeln('\'and the following function handles QuickTime');
    document.writeln('Function detectQuickTimeActiveXControl()');
    document.writeln('  on error resume next');
    document.writeln('  detectQuickTimeActiveXControl = False');
    document.writeln('  If detectableWithVB Then');
    document.writeln('    detectQuickTimeActiveXControl = False');
    document.writeln('    hasQuickTimeChecker = false');
    document.writeln('    Set hasQuickTimeChecker = CreateObject("QuickTimeCheckObject.QuickTimeCheck.1")');
    document.writeln('    If IsObject(hasQuickTimeChecker) Then');
    document.writeln('      If hasQuickTimeChecker.IsQuickTimeAvailable(0) Then ');
    document.writeln('        detectQuickTimeActiveXControl = True');
    document.writeln('      End If');
    document.writeln('    End If');
    document.writeln('  End If');
    document.writeln('End Function');

    document.writeln('</scr' + 'ipt>');
}