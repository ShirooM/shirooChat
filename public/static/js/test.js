var js_url = "";
var kw_uId = "";
var kw_ref = "";
var kw_url = "";
var kw_gurl = "";
var is_get = "";
var copy_content = "";
var copy_type = "";
try {
    js_url = document.getElementById("kw_tongji").src
} catch (e) {
}
try {
    kw_uId = getURLParam("sign", js_url)
} catch (e) {
}
try {
    kw_url = window.parent.location
} catch (e) {
}
try {
    kw_ref = window.parent.document.referrer
} catch (e) {
}
tj(1);
setInterval(function () {
    var str = encodeURIComponent(window.getSelection(0).toString());
    if (str != "" && copy_content != str) {
        copy_content = str;
        copy_type = 1;
        setTimeout(tj(2), 1)
    }
}, 1000);
document.addEventListener('copy', function (event) {
    try {
        var str = encodeURIComponent(window.getSelection(0).toString());
        if (str != "" && copy_content != str) {
            copy_content = str;
            copy_type = 2;
            setTimeout(tj(2), 1)
        }
    } catch (e) {
    }
});

function tj(type) {
    if (type == 1) {
        if (is_get != "ok") {
            kw_gurl = "http://tj.hzypro.com/addurl.jpg?kw_sign_id=" + kw_uId + "&kw_url=" + escape(kw_url) + "&kw_ref=" + escape(kw_ref) + "&v=" + unique();
            setTimeout('is_get="ok";kw_img = new Image;kw_img.src=kw_gurl;', 1)
        }
    } else {
        if (type == 2) {
            kw_gurl = "http://tj.hzypro.com/addcopy.jpg?kw_sign_id=" + kw_uId + "&type=" + copy_type + "&kw_url=" + escape(kw_url) + "&kw_ref=" + escape(kw_ref) + "&c=" + copy_content + "&v=" + unique();
            setTimeout("kw_img = new Image;kw_img.src=kw_gurl;", 1)
        }
    }
}

function getURLParam(strParamName, url) {
    var strReturn = "";
    var strHref = url.toLowerCase();
    if (strHref.indexOf("?") > -1) {
        var strQueryString = strHref.substr(strHref.indexOf("?") + 1).toLowerCase();
        var aQueryString = strQueryString.split("&");
        for (var iParam = 0; iParam < aQueryString.length; iParam++) {
            if (aQueryString[iParam].indexOf(strParamName.toLowerCase() + "=") > -1) {
                var aParam = aQueryString[iParam].split("=");
                strReturn = aParam[1];
                break
            }
        }
    }
    return strReturn
}

function unique() {
    var time = (new Date()).getTime() + "-", i = 0;
    return time + (i++)
};