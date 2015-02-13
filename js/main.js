var $dialog = {};
var oTable = {};
var aa = {};
var popupeditextra = {};
var popupedit = {}
var callback = {}
var bind = 0;
$(document).ready(function() {

    loadUi();
    UR_Start();
    $("#progressbar").offset({
        top: $(document).height() / 2 - $("#progressbar").height() / 2,
        left: $(document).width() / 2 - $("#progressbar").width() / 2
    })
    //$("#nav-one").dropmenu();

    //$(".selectrich").multiselect({multiple:false});

    $(".formfieldmulti").each(function() {

    })

    $(".formfieldmulti").live('click', function() {
        var arr = []
        var ref = $(this).attr('ref')

        $(".formfieldmulti:checked").each(function() {

            if ($(this).attr('ref') == ref) {
                arr.push($(this).val())
            }
        })
        $(".formfield[ref=" + ref + "]").val(arr);
    })
    $(".richtext").cleditor();
    $(".loginbutton").button();
})
function getId(id_elem) {
    var id = id_elem.split("_");
    return id[1];
}
function updateTips(t) {
    tips
            .text(t)
            .addClass("ui-state-highlight");
    setTimeout(function() {
        tips.removeClass("ui-state-highlight", 1500);
    }, 500);
}

function checkLength(o, n, min, max) {
    if (o.val().length > max || o.val().length < min) {
        o.addClass("ui-state-error");
        updateTips("Length of " + n + " must be between " +
                min + " and " + max + ".");
        return false;
    } else {
        return true;
    }
}

function checkRegexp(o, regexp, n) {
    if (!(regexp.test(o.val()))) {
        o.addClass("ui-state-error");
        updateTips(n);
        return false;
    } else {
        return true;
    }
}
function loadUi() {
    $("button").button();
    
    $(".datepicker").datepicker({
        //dateFormat: "dd/mm/yy",
        dateFormat: "yy-mm-dd",
        changeYear: true,
        changeMonth: true,
        yearRange: "1930:2020"
    });
    
    
    $('.timepicker').timepicker({
        ampm: false
    });
    $('.datetimepicker').datetimepicker({
        dateFormat: "yy-mm-dd",
        timeFormat: "hh:mm:ss"
    });
    

    //UR_Start();
    //$(".richtext").cleditor();
    $(".tabs").tabs({ heightStyle: "auto" });
    //$(".ui-dialog-content").dialog("option", "position", ['center', 'center']);
    //$(".selectrich").multiselect({multiple:false});

    /*
    $(".selectrich").multiselect({
        multiple: false,
        header: "Select an option",
        noneSelectedText: "Select an Option",
        selectedList: 1,
    }).multiselectfilter();
    */
   //$(".selectrich").chosen({max_selected_options: 25,width:"300px"});




}

function savemodel(id, attributes_id, ajaxformsave, callback) {
    var bValid = true;
    var fielddata = {}
    var data = {}
    data.id = id
    $("li").removeClass("taberror")
    $(".formfield_" + attributes_id + "").removeClass("ui-state-error");
    $(".arrtformfield_" + attributes_id + "").removeClass("ui-state-error");
    $(".itemerror").html("");
    var passref = false;
    var newpass = false;
    var verpass = false;
    $(".formfield_" + attributes_id + "").each(function() {
        //alert($(this).attr("name"))
        if ($(this).attr("ref") != "") {
            
            //bValid = bValid && checkLength($(this), $(this).attr("ref"), 1, 16 );

            bValid = true;

            if ($(this).attr("type") == 'password') {
                if ($(this).attr("as") == "new") {
                    passref = $(this).attr("ref");
                    newpass = ($(this).val());
                }
                if ($(this).attr("as") == "ver") {
                    verpass = ($(this).val());
                }
            } else {
                fielddata[$(this).attr("ref")] = $(this).val();
            }
        }
    })
    if (passref) {
        if (newpass == "" && verpass == "") {

        } else {
            if (newpass != verpass) {
                alert("NEW PASS AND VER PASS NOT MATCH")
                bValid = false;
                $(".arrtformfield_" + attributes_id + "[as=new]").addClass("ui-state-error");
                $(".formfield_" + attributes_id + "[as=new]").addClass("ui-state-error");                
                $(".arrtformfield_" + attributes_id + "[as=ver]").addClass("ui-state-error");
                $(".formfield_" + attributes_id + "[as=ver]").addClass("ui-state-error");
                
            } else {
                fielddata[passref] = newpass
            }
        }
    }
    fielddata["attrs"] = {}
    $(".arrtformfield_" + attributes_id + "").each(function() {
        //fielddata["attrs"][$(this).attr("ref")] = ($(this).val());
        if ($(this).attr("type") == 'password') {
            if ($(this).attr("as") == "new") {
                passref = $(this).attr("ref");
                newpass = ($(this).val());
            }
            if ($(this).attr("as") == "ver") {
                verpass = ($(this).val());
            }
        } else {
            fielddata["attrs"][$(this).attr("ref")] = ($(this).val());
        }

    })


    if (bValid) {
        fielddata["id"] = data.id
        ProgressBar.displayProgressBar();
        $.post(ajaxformsave, fielddata, function(result) {
            ProgressBar.hideProgressBar();
            if (result > 0 || result == '') {
                if ($.isFunction(callback)) {
                    callback(result)
                }
            }

            else {
                errorCheck(result, attributes_id)
            }

        })
    }
}
function errorCheck(result, attributes_id) {
    var res = result.split("|||");
    if (res[1] != undefined) {
        var obj = jQuery.parseJSON(res[0]);
        for (var i in obj) {
            $(".arrtformfield_" + attributes_id + "[ref=" + i + "]").addClass("ui-state-error");
            $(".formfield_" + attributes_id + "[ref=" + i + "]").addClass("ui-state-error");
            $("#itemerror_" + attributes_id + "_" + i).html(obj[i]);
        }
        var obj = jQuery.parseJSON(res[1]);
        for (var i in obj) {
            $("#l_tab" + attributes_id + "-" + i).addClass("taberror");
            //$("#l_tab"+attributes_id+"-"+i+" span").html("("+obj[i]+")")
        }
        return true;
    }
    return false;
}

function deletemodel(id, ajaxformdelete, callback) {
    var fielddata = {}
    var data = {}
    data.id = id
    if (confirm("Are you sure")) {
        ProgressBar.displayProgressBar();
        fielddata["id"] = data.id
        $.post(ajaxformdelete, fielddata, function(result) {
            ProgressBar.hideProgressBar();
            callback()
        })
    }
}

function serialize(mixed_value) {
    // Returns a string representation of variable (which can later be unserialized)  
    // 
    // version: 1109.2015
    // discuss at: http://phpjs.org/functions/serialize
    // +   original by: Arpad Ray (mailto:arpad@php.net)
    // +   improved by: Dino
    // +   bugfixed by: Andrej Pavlovic
    // +   bugfixed by: Garagoth
    // +      input by: DtTvB (http://dt.in.th/2008-09-16.string-length-in-bytes.html)
    // +   bugfixed by: Russell Walker (http://www.nbill.co.uk/)
    // +   bugfixed by: Jamie Beck (http://www.terabit.ca/)
    // +      input by: Martin (http://www.erlenwiese.de/)
    // +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net/)
    // +   improved by: Le Torbi (http://www.letorbi.de/)
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net/)
    // +   bugfixed by: Ben (http://benblume.co.uk/)
    // -    depends on: utf8_encode
    // %          note: We feel the main purpose of this function should be to ease the transport of data between php & js
    // %          note: Aiming for PHP-compatibility, we have to translate objects to arrays
    // *     example 1: serialize(['Kevin', 'van', 'Zonneveld']);
    // *     returns 1: 'a:3:{i:0;s:5:"Kevin";i:1;s:3:"van";i:2;s:9:"Zonneveld";}'
    // *     example 2: serialize({firstName: 'Kevin', midName: 'van', surName: 'Zonneveld'});
    // *     returns 2: 'a:3:{s:9:"firstName";s:5:"Kevin";s:7:"midName";s:3:"van";s:7:"surName";s:9:"Zonneveld";}'
    var _utf8Size = function(str) {
        var size = 0,
                i = 0,
                l = str.length,
                code = '';
        for (i = 0; i < l; i++) {
            code = str.charCodeAt(i);
            if (code < 0x0080) {
                size += 1;
            } else if (code < 0x0800) {
                size += 2;
            } else {
                size += 3;
            }
        }
        return size;
    };
    var _getType = function(inp) {
        var type = typeof inp,
                match;
        var key;

        if (type === 'object' && !inp) {
            return 'null';
        }
        if (type === "object") {
            if (!inp.constructor) {
                return 'object';
            }
            var cons = inp.constructor.toString();
            match = cons.match(/(\w+)\(/);
            if (match) {
                cons = match[1].toLowerCase();
            }
            var types = ["boolean", "number", "string", "array"];
            for (key in types) {
                if (cons == types[key]) {
                    type = types[key];
                    break;
                }
            }
        }
        return type;
    };
    var type = _getType(mixed_value);
    var val, ktype = '';

    switch (type) {
        case "function":
            val = "";
            break;
        case "boolean":
            val = "b:" + (mixed_value ? "1" : "0");
            break;
        case "number":
            val = (Math.round(mixed_value) == mixed_value ? "i" : "d") + ":" + mixed_value;
            break;
        case "string":
            val = "s:" + _utf8Size(mixed_value) + ":\"" + mixed_value + "\"";
            break;
        case "array":
        case "object":
            val = "a";
            /*
             if (type == "object") {
             var objname = mixed_value.constructor.toString().match(/(\w+)\(\)/);
             if (objname == undefined) {
             return;
             }
             objname[1] = this.serialize(objname[1]);
             val = "O" + objname[1].substring(1, objname[1].length - 1);
             }
             */
            var count = 0;
            var vals = "";
            var okey;
            var key;
            for (key in mixed_value) {
                if (mixed_value.hasOwnProperty(key)) {
                    ktype = _getType(mixed_value[key]);
                    if (ktype === "function") {
                        continue;
                    }

                    okey = (key.match(/^[0-9]+$/) ? parseInt(key, 10) : key);
                    vals += this.serialize(okey) + this.serialize(mixed_value[key]);
                    count++;
                }
            }
            val += ":" + count + ":{" + vals + "}";
            break;
        case "undefined":
            // Fall-through
        default:
            // if the JS object has a property which contains a null value, the string cannot be unserialized by PHP
            val = "N";
            break;
    }
    if (type !== "object" && type !== "array") {
        val += ";";
    }
    return val;
}

function utf8_encode(argString) {
    // Encodes an ISO-8859-1 string to UTF-8  
    // 
    // version: 1109.2015
    // discuss at: http://phpjs.org/functions/utf8_encode
    // +   original by: Webtoolkit.info (http://www.webtoolkit.info/)
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: sowberry
    // +    tweaked by: Jack
    // +   bugfixed by: Onno Marsman
    // +   improved by: Yves Sucaet
    // +   bugfixed by: Onno Marsman
    // +   bugfixed by: Ulrich
    // +   bugfixed by: Rafal Kukawski
    // *     example 1: utf8_encode('Kevin van Zonneveld');
    // *     returns 1: 'Kevin van Zonneveld'
    if (argString === null || typeof argString === "undefined") {
        return "";
    }

    var string = (argString + ''); // .replace(/\r\n/g, "\n").replace(/\r/g, "\n");
    var utftext = "",
            start, end, stringl = 0;

    start = end = 0;
    stringl = string.length;
    for (var n = 0; n < stringl; n++) {
        var c1 = string.charCodeAt(n);
        var enc = null;

        if (c1 < 128) {
            end++;
        } else if (c1 > 127 && c1 < 2048) {
            enc = String.fromCharCode((c1 >> 6) | 192) + String.fromCharCode((c1 & 63) | 128);
        } else {
            enc = String.fromCharCode((c1 >> 12) | 224) + String.fromCharCode(((c1 >> 6) & 63) | 128) + String.fromCharCode((c1 & 63) | 128);
        }
        if (enc !== null) {
            if (end > start) {
                utftext += string.slice(start, end);
            }
            utftext += enc;
            start = end = n + 1;
        }
    }

    if (end > start) {
        utftext += string.slice(start, stringl);
    }

    return utftext;
}
function UR_Start()
{
    //UR_Nu = new Date;
    //UR_Indhold = showFilled(UR_Nu.getHours()) + ":" + showFilled(UR_Nu.getMinutes());
    //document.getElementById("ur").innerHTML = UR_Indhold;
    //setTimeout("UR_Start()", 1000);
}
function showFilled(Value)
{
    return (Value > 9) ? "" + Value : "0" + Value;
}