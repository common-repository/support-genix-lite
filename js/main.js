(function(){function r(e,n,t){function o(i,f){if(!n[i]){if(!e[i]){var c="function"==typeof require&&require;if(!f&&c)return c(i,!0);if(u)return u(i,!0);var a=new Error("Cannot find module '"+i+"'");throw a.code="MODULE_NOT_FOUND",a}var p=n[i]={exports:{}};e[i][0].call(p.exports,function(r){var n=e[i][1][r];return o(n||r)},p,p.exports,r,e,n,t)}return n[i].exports}for(var u="function"==typeof require&&require,i=0;i<t.length;i++)o(t[i]);return o}return r})()({1:[function(require,module,exports){
(function (global){(function (){
'use strict';

global.jQuery = global.$ = jQuery;
require("./modules/magnific");
require("./modules/pro_req");
window.gcl=function(obj,$isShow){
    console.log(obj);
}
window.APPSBDAPPJS={
    core : require("./modules/core"),
    sidemenu : require("./modules/menu"),
    lightbox : require("./modules/lightbox"),
    confirmAjax : require("./modules/confirm_ajax"),
    notification : require("./modules/notification"),
    InputPicker : require("./modules/inputpicker"),
    FilePicker : require("./modules/file-picker"),
    datetimepicker : require("./modules/datatimepicker"),
    dependable : require("./modules/dependable_input"),
    WPEditor : require("./modules/wp_edittor_setup"),
    WPMedia : require("./modules/wp_media_choose"),
    Dragdrop : require("./modules/dragdrop"),
    SliderInput:require("./modules/slider_input"),
    Version:{
            version:'1.0.3',
            release_date:'March 3, 2021'
        },
    Initialize:function () {
        APPSBDAPPJS.lightbox.SetLightbox();
        APPSBDAPPJS.lightbox.SetLightBoxAjax();
        APPSBDAPPJS.confirmAjax.SetConfirm();
        APPSBDAPPJS.confirmAjax.SetAjaxForm();
        APPSBDAPPJS.InputPicker.SetColorPicker();
        APPSBDAPPJS.InputPicker.SetIconPicker();
        APPSBDAPPJS.InputPicker.SetSelectPicker();
        APPSBDAPPJS.InputPicker.SetEditableSelect();
        APPSBDAPPJS.InputPicker.SetInputTag();

        APPSBDAPPJS.FilePicker.SetImageInput();
        APPSBDAPPJS.FilePicker.SetFilePicker();
        APPSBDAPPJS.datetimepicker.SetDateTimePicker();
        APPSBDAPPJS.dependable.SetDependable();
        APPSBDAPPJS.core.SetPopover();
        APPSBDAPPJS.core.SetTooltip();
        APPSBDAPPJS.core.SetDropGridDropdownMenu();
        APPSBDAPPJS.WPEditor.Init();

        APPSBDAPPJS.core.SetTabView();
        APPSBDAPPJS.core.SetLazyLoader();
        APPSBDAPPJS.core.SetBVForm();
        APPSBDAPPJS.core.SetDownload();
        APPSBDAPPJS.Dragdrop.init();

    }
}

var s='url("data:image/svg+xml,%3csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 4 5\'%3e%3cpath fill=\'%23343a40\' d=\'M2 0L0 2h4zm0 5L0 3h4z\'/%3e%3c/svg%3e") no-repeat right 0.75rem center/8px 10px, url("data:image/svg+xml,%3csvg xmlns=\'http://www.w3.org/2000/svg\' fill=\'%23dc3545\' viewBox=\'-2 -2 7 7\'%3e%3cpath stroke=\'%23dc3545\' d=\'M0 0l3 3m0-3L0 3\'/%3e%3ccircle r=\'.5\'/%3e%3ccircle cx=\'3\' r=\'.5\'/%3e%3ccircle cy=\'3\' r=\'.5\'/%3e%3ccircle cx=\'3\' cy=\'3\' r=\'.5\'/%3e%3c/svg%3E") #fff no-repeat center right 1.75rem/calc(0.75em + 0.375rem) calc(0.75em + 0.375rem)';
window.AddOnCloseMethod=APPSBDAPPJS.core.AddOnLoadLightbox;
//ready function
jQuery(document).ready(function ($) {
    //initilize
    jQuery(document).on('focusin', function(e) {
        if ($(e.target).closest(".mce-reset").length) {
            e.stopPropagation();
            e.stopImmediatePropagation();
        }
    });
    APPSBDAPPJS.core.CallOnInitApp();
    $(window).on("resize",function(){
        APPSBDAPPJS.core.CallOnWindowResize();
    });
    APPSBDAPPJS.dependable.SetLiveElementDependable();
    try{
        var hashlink=window.location.hash.replace("#","").split(",");
        for(var hi in hashlink){
            $("#"+hashlink[hi]).click();
        }


    }catch (e) {
        
    }
    APPSBDAPPJS.sidemenu.SetMenuSidebar();
    APPSBDAPPJS.Initialize();
    APPSBDAPPJS.WPMedia.Init();
    APPSBDAPPJS.WPEditor.SetInsertButton();
    APPSBDAPPJS.core.SetFullScreen();
    APPSBDAPPJS.SliderInput.SetSliderInput();
    //add on lighboxload
    APPSBDAPPJS.core.AddOnLoadLightbox(APPSBDAPPJS.lightbox.SetLightbox);
    APPSBDAPPJS.core.AddOnLoadLightbox(APPSBDAPPJS.lightbox.SetLightBoxAjax);
    APPSBDAPPJS.core.AddOnLoadLightbox(APPSBDAPPJS.confirmAjax.SetConfirm);
    APPSBDAPPJS.core.AddOnLoadLightbox(APPSBDAPPJS.InputPicker.SetColorPicker);
    APPSBDAPPJS.core.AddOnLoadLightbox(APPSBDAPPJS.InputPicker.SetIconPicker);
    APPSBDAPPJS.core.AddOnLoadLightbox(APPSBDAPPJS.InputPicker.SetSelectPicker);
    APPSBDAPPJS.core.AddOnLoadLightbox(APPSBDAPPJS.datetimepicker.SetDateTimePicker);
    APPSBDAPPJS.core.AddOnLoadLightbox(APPSBDAPPJS.dependable.SetDependable);
    APPSBDAPPJS.core.AddOnLoadLightbox(APPSBDAPPJS.core.SetPopover);
    APPSBDAPPJS.core.AddOnLoadLightbox(APPSBDAPPJS.core.SetTooltip);
    APPSBDAPPJS.core.AddOnLoadLightbox(APPSBDAPPJS.WPEditor.Init);
    APPSBDAPPJS.core.AddOnLoadLightbox(APPSBDAPPJS.confirmAjax.SetAjaxForm);
    APPSBDAPPJS.core.AddOnLoadLightbox(APPSBDAPPJS.Dragdrop.init);
    APPSBDAPPJS.core.AddOnLoadLightbox(APPSBDAPPJS.core.SetDownload);
    APPSBDAPPJS.core.AddOnLoadLightbox(APPSBDAPPJS.FilePicker.SetImageInput);
    APPSBDAPPJS.core.AddOnLoadLightbox(APPSBDAPPJS.FilePicker.SetFilePicker);
    APPSBDAPPJS.core.AddOnLoadLightbox(APPSBDAPPJS.InputPicker.SetEditableSelect);
    APPSBDAPPJS.core.AddOnLoadLightbox(APPSBDAPPJS.InputPicker.SetInputTag);

    APPSBDAPPJS.core.AddOnGridDataLoad(APPSBDAPPJS.core.SetDownload);
    APPSBDAPPJS.core.AddOnGridDataLoad(APPSBDAPPJS.core.SetPopover);
    APPSBDAPPJS.core.AddOnGridDataLoad(APPSBDAPPJS.core.SetTooltip);
    APPSBDAPPJS.core.AddOnGridDataLoad(APPSBDAPPJS.core.SetDropGridDropdownMenu);
    APPSBDAPPJS.core.AddOnGridDataLoad(APPSBDAPPJS.InputPicker.SetEditableSelect);
    APPSBDAPPJS.core.AddOnGridDataLoad(APPSBDAPPJS.InputPicker.SetInputTag);

    APPSBDAPPJS.core.AddOnLoadLightbox(APPSBDAPPJS.SliderInput.SetSliderInput);
    APPSBDAPPJS.core.AddOnGridDataLoad(APPSBDAPPJS.SliderInput.SetSliderInput);


});


}).call(this)}).call(this,typeof global !== "undefined" ? global : typeof self !== "undefined" ? self : typeof window !== "undefined" ? window : {})
},{"./modules/confirm_ajax":2,"./modules/core":3,"./modules/datatimepicker":4,"./modules/dependable_input":5,"./modules/dragdrop":6,"./modules/file-picker":7,"./modules/inputpicker":8,"./modules/lightbox":9,"./modules/magnific":10,"./modules/menu":11,"./modules/notification":12,"./modules/pro_req":13,"./modules/slider_input":14,"./modules/wp_edittor_setup":15,"./modules/wp_media_choose":16}],2:[function(require,module,exports){
var core=require('./core');
var notification=require('./notification');
var confirmAjax= {
    SetConfirm: function () {
        $("body").on("click", ".Confirm,.confirm", function (e) {
            if($(this).closest(".sa-confirm-button-container").length>0){
                return;
            }
            var msg = $(this).attr('msg');
            if (confirm(msg) == false) {
                e.stopPropagation();
                e.preventDefault();
            }
        });

        $("body").on("click", ".ConfirmAjaxWR,.confirmAjaxWR,.confirmajaxwr", function (e) {
                       e.stopPropagation();
            e.preventDefault();
            var msg = $(this).data('msg');
            var $thisObj = $(this);

            var callAfterProcess = $(this).attr('oncompleted');
            if (!callAfterProcess || callAfterProcess == "") {
                callAfterProcess = $(this).data('on-complete');
            }
            var thisobj = $(this);
            var url = thisobj.attr("href");
            if (typeof (url) == "undefined" || url == "") {
                alert("Target url is empty");
                return;
            }
            if (msg && msg.length>0) {
                notification.ConfirmAlert(msg, function (isConfirm) {
                    if(isConfirm) {
                        confirmAjax.process_confirm_ajax(thisobj, url, callAfterProcess);
                    }
                },true);
            }else{
                confirmAjax.process_confirm_ajax(thisobj, url, callAfterProcess);
            }
        });

    },
    process_confirm_ajax: function (thisobj, url, callAfterProcess) {
        var lastHtml = "";
        $.ajax({
            url: url,
            type: "GET",
            scriptCharset: "utf-8",
            dataType: "json",
            beforeSend: function () {
                lastHtml = thisobj.html();
                thisobj.html('<i class="conf-loader fa fa-spinner fa-spin"></i> ');
            },
            success: function (rdata) {
                try {
                    if (callAfterProcess) {
                        if(typeof callAfterProcess =="function"){
                            setTimeout(function () {
                                callAfterProcess(rdata, thisobj);

                            }, 50);
                            return;
                        }else {
                            var com = eval(callAfterProcess);
                            if (typeof com == 'function') {
                                setTimeout(function () {
                                    com(rdata, thisobj);
                                }, 50);
                                return;
                            }
                        }

                    }
                } catch (e) {
                    gcl(e);
                }
                try {
                    notification.ShowSwal(rdata);
                } catch (e) {}
                if (rdata.status) {
                    APPSBDAPPJS.core.SetAjaxChangeStatus(true);
                    APPSBDAPPJS.core.ReloadAll();
                }


            },
            complete: function (jqXHR, textStatus) {
                thisobj.html(lastHtml);
            }
        });
    },

    SetAjaxForm: function () {
        $("form.apbd-module-form,form.apbd-module-form").each(function () {
            var Ajaxbostrapvalidator = $(this).bootstrapValidator({
                excluded: ':disabled',
                message: 'This value is not valid',
                feedbackIcons: {
                    valid: 'fa fa-check',
                    invalid: 'fa fa-times',
                    validating: 'fa fa-refresh'
                },
                fields: {
                    cc_exp_date: {
                        validators: {
                            callback: {
                                message: 'Invalid MMYY',
                                callback: function (value, validator) {
                                    var m = new moment(value, 'MMYY', true);
                                    if (!m.isValid()) {
                                        return false;
                                    }
                                    var m2 = moment();
                                    // US independence day is July 4
                                    return m > m2;
                                }
                            }
                        }
                    }
                },
                submitHandler: function (validator, form, submitButton) {
                    var rtype = form.attr("request-type");
                    var htmlBeforeLoading = "";
                    if (!rtype) {
                        rtype = "json";
                    }
                    var isMultiPart = false;
                    if (form.data("multipart")) {
                        try {
                            form.find("input[type=file]").each(function () {
                                if ($(this).val() != "") {
                                    isMultiPart = true;
                                }
                            });
                        } catch (e) {
                            isMultiPart = true;
                        }
                    }
                    if (isMultiPart) {
                        var formData = new FormData(form[0]);
                        formData = APPSBDAPPJS.core.SetCsrfParam(formData);
                        var contentType = false;
                        var processData = false;
                        var async = true;

                    } else {
                        var formData = APPSBDAPPJS.core.SetCsrfParam(form.serialize());
                        var contentType = 'application/x-www-form-urlencoded; charset=UTF-8';
                        var processData = true;
                        var async = true;
                    }
                    var method = form.attr("method");
                    var form_id=form.closest('.apbd-module-container').attr("id");

                    $.ajax({
                        type: method,
                        url: form.attr('action'),
                        data: formData,
                        processData: processData,
                        dataType: rtype,
                        contentType: contentType,
                        cache: false,
                        async: async,
                        beforeSend: function () {
                            htmlBeforeLoading = form.find("[type=submit]").html();
                            form.find("[type=submit]").html('<i class="fa fa-spinner fa-spin"></i>');
                            form.find("[type=submit]").addClass("Loading");
                            form.find("[type=submit]").attr("disabled", "disabled");
                            form.addClass("form-loader");
                            try {
                                var beforesend = form.data("beforesend");
                                if (beforesend) {
                                    eval(beforesend + "(form);");
                                    return;
                                }
                                beforesend = form.attr("beforesend")
                                if (beforesend) {
                                    eval(beforesend + "(form);");
                                    return;
                                }
                                beforesend = form.data("on-beforesend")
                                if (beforesend) {
                                    eval(beforesend + "(form);");
                                    return;
                                }
                            } catch (e) {

                            }
                        },
                        success: function (rdata) {
                            try {
                                var oncomplete = form.data("oncomplete");
                                if (oncomplete) {
                                    eval(oncomplete + "(rdata,form);");
                                    return;
                                }
                                oncomplete = form.attr("oncomplete")
                                if (oncomplete) {
                                    eval(oncomplete + "(rdata,form);");
                                    return;
                                }
                                oncomplete = form.data("on-complete")
                                if (oncomplete) {
                                    eval(oncomplete + "(rdata,form);");
                                    return;
                                }
                            } catch (e) {

                            }
                            APPSBDAPPJS.core.CallOnModuleAjaxSuccess(form_id,rdata,form);
                            if (rtype != "json") return;
                            if (rdata.status) {
                                APPSBDAPPJS.core.ReloadAll();
                            }
                            notification.ShowGritterMsg(rdata.msg,rdata.status,rdata.isSticky,rdata.title,rdata.icon);
                        },
                        complete: function (jqXHR, textStatus) {
                            form.removeClass("form-loader");
                            form.find("[type=submit]").removeClass("Loading");
                            form.find("[type=submit]").removeAttr("disabled");
                            form.find("[type=submit]").html(htmlBeforeLoading);
                            if (jqXHR.status == "500" || jqXHR.status == "403" || textStatus == "error") {
                                form.find(".state-loading").removeClass("state-loading");
                                try {
                                    notification.ShowGritterMsg(jqXHR.responseJSON.msg, jqXHR.responseJSON.status, jqXHR.responseJSON.is_sticky, jqXHR.responseJSON.title, jqXHR.responseJSON.icon);
                                } catch (e) {
                                    notification.ShowGritterMsg("Unwanted Error", false, false, "Error !!", "times-circle-o");
                                }
                            }


                        }
                    });
                }
            });
            // Init iCheck elements
            try {
                Ajaxbostrapvalidator.find('.cbox-control').iCheck({
                    checkboxClass: 'icheckbox_square-green',
                    radioClass: 'iradio_square-green'
                })
                // Called when the radios/checkboxes are changed
                    .on('ifChanged', function (e) {
                        // Get the field name
                        try {
                            var field = $(this).attr('name');
                            var fromobj = $(this).closest("form");
                            fromobj
                            // Mark the field as not validated
                                .bootstrapValidator('updateStatus', field, 'NOT_VALIDATED')
                                // Validate field
                                .bootstrapValidator('validateField', field);
                        } catch (e) {
                        }
                    });
            } catch (e) {
            }
        }) ;


    },
    ConfirmWRChange: function (rdata, element) {
        notification.ShowResponseSwal(rdata);
        if (rdata.status) {
            element.html(rdata.data);
        }
    },
    ConfirmWReload: function (rdata, element) {
        notification.ShowResponseSwal(rdata);
        if (rdata.status) {
            core.CallOnCloseLightbox();
        }
    }
}
module.exports=confirmAjax;



},{"./core":3,"./notification":12}],3:[function(require,module,exports){
(function (global){(function (){
'use strict';
global.onLoadLightbox=[];
global.onAppInit=[];
global.onCloseLightbox=[];
global.onModuleAjaxSuccess=[];
global.onCloseLightboxWithEvent=[];
global.onEventCaller=[];
global.onGridDataLoad=[];
global.onWindowResize=[];
global.onTabActive=[];
global.IsAPBDAjaxChange=false;
global.CurrentAPBDAjaxEvent="";

var coreObj= {
    AddOnAppInit: function (func) {
        onAppInit.push(func);
    },
    AddOnLoadLightbox: function (func) {
        onLoadLightbox.push(func);
    },

    AddOnModuleAjaxSuccess: function (module_id, func) {
        if (typeof onModuleAjaxSuccess[module_id] == "undefined") {
            onModuleAjaxSuccess[module_id] = [];
        }
        onModuleAjaxSuccess[module_id].push(func);
    },
    AddOnCloseLightbox: function (func) {
        onCloseLightbox.push(func);
    },
    AddOnCloseLightboxWithEvent: function (event, func) {
        if (typeof onCloseLightboxWithEvent[event] == "undefined") {
            onCloseLightboxWithEvent[event] = [];
        }
        onCloseLightboxWithEvent[event].push(func);
    },
    AddOnEvent: function (event, func) {
        if (typeof onEventCaller[event] == "undefined") {
            onEventCaller[event] = [];
        }
        onEventCaller[event].push(func);
    },
    AddOnReload: function (func) {
        onCloseLightbox.push(func);
    },
    AddOnGridDataLoad: function (func) {
        onGridDataLoad.push(func);
    },
    AddOnWindowResize: function (func) {
        onWindowResize.push(func);
    },
    AddOnOnTabActive: function (module_id, func) {
        module_id = module_id.trim();
        if (typeof onTabActive[module_id] == "undefined") {
            onTabActive[module_id] = [];
        }
        onTabActive[module_id].push(func);
    },
    CallOnModuleAjaxSuccess: function (event, rdata, form) {
        try {
            if ((typeof onModuleAjaxSuccess[event] != "undefined") && onModuleAjaxSuccess[event].length > 0) {
                for (var i in onModuleAjaxSuccess[event]) {
                    try {
                        onModuleAjaxSuccess[event][i](rdata, form);
                    } catch (e) {
                    }
                }
            }
        } catch (e) {
        }
    },
    CallOnTabActive: function (module_id) {
        try {
            for (var i in onTabActive[module_id]) {
                try {
                    onTabActive[module_id][i]();
                } catch (e) {
                }
            }
        } catch (e) {
            console.log(e);
        }
    },
    CallOnInitApp: function (module_id) {
        try {
            for (var i in onAppInit[module_id]) {
                try {
                    onAppInit[module_id][i]();
                } catch (e) {
                }
            }
        } catch (e) {
            console.log(e);
        }
    },
    CallOnWindowResize: function () {
        try {
            for (var i in onWindowResize) {
                try {
                    onWindowResize[i]();
                } catch (e) {
                }
            }
        } catch (e) {
            console.log(e);
        }
    },
    CallOnLoadGridData: function () {
        try {
            for (var i in onGridDataLoad) {
                try {
                    onGridDataLoad[i]();
                } catch (e) {
                }
            }
        } catch (e) {
            console.log(e);
        }
    },
    CallOnLoadLightbox: function () {
        try {
            for (var i in onLoadLightbox) {
                try {
                    onLoadLightbox[i]();
                } catch (e) {
                }
            }
        } catch (e) {

        }
    },
    CallOnReloadWithEvent: function (event) {
        try {
            if ((typeof onCloseLightboxWithEvent[event] != "undefined") && onCloseLightboxWithEvent[event].length > 0) {
                for (var i in onCloseLightboxWithEvent[event]) {
                    try {
                        onCloseLightboxWithEvent[event][i]();
                    } catch (e) {
                    }
                }
            }
        } catch (e) {

        }
    },
    CallOnEvent: function (event) {
        var args = [];
        try {
            for (var i = 1; i < arguments.length; i++) {
                args.push(arguments[i]);
            }
        } catch (e) {
        }

        try {
            if ((typeof onEventCaller[event] != "undefined") && onEventCaller[event].length > 0) {
                for (var i in onEventCaller[event]) {
                    try {
                        onEventCaller[event][i].apply(this, args);
                    } catch (e) {
                    }
                }
            }
        } catch (e) {

        }
    },
    CallOnCloseLightbox: function () {
        var thisObj = null;
        try {
            if (typeof (this._lastFocusedEl) != "undefined") {
                thisObj = $(this._lastFocusedEl);
            } else if (typeof ($(this)[0].ev) != "undefined") {
                thisObj = $(this)[0].ev;
            } else {
                thisObj = $(this);
            }
        } catch (e) {
            thisObj = $(this);
        }

        var onclosemainevent = thisObj.attr('onclose');
        if (onclosemainevent) {
            eval(onclosemainevent + "()");
            return;
        }

        var onclosemainevent2 = thisObj.data('onclose');
        if (onclosemainevent2) {
            eval(onclosemainevent2 + "()");
            return;
        }
        if (coreObj.IsAjaxChange()) {
            try {
                for (var i in onCloseLightbox) {
                    try {
                        onCloseLightbox[i]();
                    } catch (e) {
                    }
                }
            } catch (e) {
                console.log(e);
            }
            try {
                if (global.CurrentAPBDAjaxEvent != "" && (typeof onCloseLightboxWithEvent[global.CurrentAPBDAjaxEvent] != "undefined") && onCloseLightboxWithEvent[global.CurrentAPBDAjaxEvent].length > 0) {
                    for (var i in onCloseLightboxWithEvent[global.CurrentAPBDAjaxEvent]) {
                        try {
                            onCloseLightboxWithEvent[global.CurrentAPBDAjaxEvent][i]();
                        } catch (e) {
                        }
                    }
                }
            } catch (e) {
                console.log(e);
            }
        } else {
        }


    },

    SetCsrfParam: function (param) {
        try {
            var postValue = coreObj.GetCookie(csrf_ajax_cookie_name);
            if (postValue && postValue != "") {
                if (typeof param == "string") {
                    if (param != "") {
                        param += "&";
                    }
                    param += csrf_ajax_input_name + "=" + postValue;
                } else if (typeof param == "object") {
                    try {
                        if (typeof param.append === 'function') {
                            param.append(csrf_ajax_input_name, postValue);
                        } else {
                            if (param.length == 0) {
                                param[csrf_ajax_input_name] = postValue;
                            } else {
                                param[csrf_ajax_input_name] = postValue;
                            }
                        }
                    } catch (e) {
                    }

                }
            }
            return param;
        } catch (e) {
            return param;
        }

    },
    ReloadAll: function () {
        coreObj.CallOnCloseLightbox();
    },
    ReloadSiteUrl: function () {
        window.location = window.location.href;
    },
    RedirectUrl: function (url) {
        window.location = url;
    },
    CallMyAjax: function (url, data, beforeSend, Success, JSONData, Complete) {
        if (!beforeSend) {
            beforeSend = function () {
            }
        }
        if (!Success) {
            Success = function () {
            }
        }
        if (typeof (JSONData) == "undefined") {
            JSONData = true;
        }
        $.ajax({
            url: url,
            data: APPSBDAPPJS.core.SetCsrfParam(data),
            type: "POST",
            scriptCharset: "utf-8",
            dataType: JSONData ? "json" : "html",
            beforeSend: function () {
                beforeSend();
            },
            success: function (rdata) {
                Success(rdata);
            },
            complete: function (jqXHR, textStatus) {
                if (typeof (Complete) != "undefined") {
                    Complete(jqXHR, textStatus);
                }
                if (textStatus == "error") {
                    if (jqXHR.status == "404") {
                        console.log("Error: Page does not found");
                    } else if (jqXHR.status == "408") {
                        console.log("Error: Sarver does not active.");
                    } else {
                        console.log("Error: May be connection lost.");
                    }
                }
            }
        });
    },
    SetFullScreen: function () {
        try {
            $(".full-screen").prepend('<a type="button" href="#" class="full-screen-btn btn-xs btn"><i class="fa"></i></a>');
            $("body").on("click", ".full-screen-btn", function (e) {
                e.preventDefault();
                $("body").toggleClass("full-screen-body");

            });
        } catch (e) {
            gcl(e);
        }
        try {

            $("body").on("click", ".app-full-screen", function (e) {
                e.preventDefault();
                $("body").toggleClass("full-screen-body");
                var target = $(this).data('target');
                $(target).toggleClass("full-screen-target");
            });
        } catch (e) {
            gcl(e);
        }
    },
    GetTimeSpendDate: function (date1, date2) {
        var diff = Math.floor(date1.getTime() - date2.getTime());
        var secs = Math.floor(diff / 1000);
        var mins = Math.floor(secs / 60);
        var hours = Math.floor(mins / 60);
        var days = Math.floor(hours / 24);
        var months = Math.floor(days / 31);
        var years = Math.floor(months / 12);
        months = Math.floor(months % 12);
        days = Math.floor(days % 31);
        hours = Math.floor(hours % 24);
        mins = Math.floor(mins % 60);
        secs = Math.floor(secs % 60);
        var message = "";
        if (days <= 0) {
            message += secs + " sec ";
            message += mins + " min ";
            message += hours + " hours ";
        } else {
            message += days + " days ";
            if (months > 0 || years > 0) {
                message += months + " months ";
            }
            if (years > 0) {
                message += years + " years ago";
            }
        }
        return message
    },
    GetCookie: function (w) {
        var cName = "";
        var pCOOKIES = [];
        pCOOKIES = document.cookie.split('; ');
        for (var bb = 0; bb < pCOOKIES.length; bb++) {
            var NmeVal = [];
            NmeVal = pCOOKIES[bb].split('=');
            if (NmeVal[0] == w) {
                cName = unescape(NmeVal[1]);
            }
        }
        return cName;
    },
    PrintCookies: function printCookies(w) {
        var cStr = "";
        var pCOOKIES = [];
        var pCOOKIES = document.cookie.split('; ');
        for (var bb = 0; bb < pCOOKIES.length; bb++) {
            var NmeVal = [];
            NmeVal = pCOOKIES[bb].split('=');
            if (NmeVal[0]) {
                cStr += NmeVal[0] + '=' + unescape(NmeVal[1]) + '; ';
            }
        }
        return cStr;
    },
    DeleteCookie: function (name) {
        try {
            document.cookie = name + '=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT';
        } catch (e) {
        }
    },
    SetCookie: function (name, value, expires, path, domain, secure) {
        var cookieStr = name + "=" + escape(value) + "; ";

        if (expires) {
            expires = coreObj.SetExpiration(expires);
            cookieStr += "expires=" + expires + "; ";
        }
        if (path) {
            cookieStr += "path=" + path + "; ";
        }
        if (domain) {
            cookieStr += "domain=" + domain + "; ";
        }
        if (secure) {
            cookieStr += "secure; ";
        }

        document.cookie = cookieStr;
    },
    SetExpiration: function (cookieLife) {
        var today = new Date();
        var expr = new Date(today.getTime() + cookieLife * 24 * 60 * 60 * 1000);
        return expr.toGMTString();
    },
    SetPopover: function () {
        try {
            $('[data-toggle="popover"]:not(".poad"),.app-popover:not(".poad")').each(function () {
                $(this).addClass('poad');
                var hasElem = $(this).data('element');

                var parent = $(this).closest("#LightBoxBody");
                var containter = "#APPSBDWP";
                if (parent.length > 0) {
                    containter = "#LightBoxBody";
                }
                if (hasElem) {
                    $(this).popover({
                        container: containter,
                        content: $(hasElem),
                        html: true
                    });
                } else {
                    $(this).popover({
                        container: containter
                    });
                }
            });
        } catch (e) {
        }
    },
    SetNoticeLink: function () {
        try {
            $("body").on("click", '.apd-notice-tab-link', function (e) {
                e.preventDefault();
                var target = $(this).attr("href");
                $(target).click();
            });
        } catch (e) {
        }
    },
    SetTooltip: function () {
        try {
            $('[data-toggle="tooltip"]:not(".ttad"),.app-tooltip:not(".ttad")').each(function () {
                $(this).addClass('ttad');
                var parent = $(this).closest("#LightBoxBody");
                var containter = "#APPSBDWP";
                if (parent.length > 0) {
                    containter = "#LightBoxBody";
                }
                $(this).tooltip({container: containter, boundary: 'window', trigger: "hover"})
            });
        } catch (e) {
        }
    },
    SetAjaxChangeStatus: function (status) {
        global.IsAPBDAjaxChange = status;
    },
    SetAjaxChangeEvent: function (evt) {
        global.CurrentAPBDAjaxEvent = evt;
    },
    IsAjaxChange: function () {
        return global.IsAPBDAjaxChange;
    },
    SetTabView: function () {
        try {
            $('.app-tab-viewer:not(.atv-added)').each(function () {
                $(this).addClass("atv-added");
                $(this).on("click", function (e) {
                    e.preventDefault();
                    var target = $(this).attr("href");

                    if (target.startsWith("#")) {
                        var module_id = target.replace('#', '');
                        var menu = $('a.nav-link[data-module-id=' + module_id + ']');
                        if (menu.length > 0) {
                            menu.click();
                        } else {
                            try {
                                $(target).closest(".tab-content").find(".tab-pane").removeClass("active").removeClass("show");
                                $(target).addClass("active show");
                                APPSBDAPPJS.core.CallOnTabActive(target.replace('#', ''));
                            } catch (e) {
                            }
                        }

                    }
                });

            });
        } catch (e) {
        }
    },
    SetLazyLoader: function () {
        try {
            $('.apbd-lazy-loader:not(.alzl-added)').each(function () {
                $(this).addClass("alzl-added");
                $(this).on("click", function (e) {
                    var isCalled = $(this).data("app-loaded");
                    if (isCalled != "Y") {
                        var odload = $(this).data("onclick");
                        if (odload) {
                            $(this).data("app-loaded", "Y");
                            eval(odload + "()");
                        }
                    }
                });

            });
        } catch (e) {
        }
    },
    SetBVForm: function () {
        try {
            $('.apbd-bv-form:not(.app-lb-ajax-form)').each(function () {
                var submitHandler = $(this).data("submit-handler");
                if (submitHandler) {
                    submitHandler = eval(submitHandler);
                }
                $(this).bootstrapValidator({
                    submitHandler: submitHandler,
                    excluded: ':disabled,:hidden:not(.force-bv)',
                    message: 'This value is not valid',
                    feedbackIcons: {
                        valid: 'fa fa-check',
                        invalid: 'fa fa-times',
                        validating: 'fa fa-refresh'
                    }
                });


            });
        } catch (e) {
        }
    },
    SetDownload: function () {
        $(".apbd-download:not(.added-dwn)").on("click", function (e) {
            e.preventDefault();
            var targetHref = $(this).attr("href");
            $(this).addClass("added-dwn");
            coreObj.DownloadURL(targetHref);
        });
    },
    SetDropGridDropdownMenu: function () {
        try {
            $(".app-grid-submenu-box").remove();
        } catch (e) {
        }
        try {
            $(".app-grid-dropdown:not(.adgdds)").appdropdown({submenuClass: "app-grid-submenu-box"}).addClass('adgdds');
        } catch (e) {

        }
    },
    DownloadURL: function (targetHref) {
        var ifr = null;
        if ($("#APPSBDWP .apbd-fdwn").length == 0) {
            ifr = $("<iframe class='apbd-fdwn d-none'></iframe>");
            $("#APPSBDWP").append(ifr);
        } else {
            ifr = $("#APPSBDWP .apbd-fdwn");
        }
        ifr.attr("src", targetHref);
    },
    SetSelectAllOnFocus: function () {
        jQuery(".apd-select-all-onfocus:not(.p-allf)").each(function () {
            $(this).addClass("p-allf").on("focus", function (e) {
                $(this).select();
            });
        });
    },
    humanFileSize: function (size) {
        var i = Math.floor(Math.log(size) / Math.log(1024));
        return (size / Math.pow(1024, i)).toFixed(2) * 1 + ' ' + ['B', 'kB', 'MB', 'GB', 'TB'][i];
    },
    CopyTextToClipboard: function (text,elem) {

            if (window.clipboardData && window.clipboardData.setData) {
                // Internet Explorer-specific code path to prevent textarea being shown while dialog is visible.
                return window.clipboardData.setData("Text", text);

            }
            else if (document.queryCommandSupported && document.queryCommandSupported("copy")) {
                if(typeof (elem) == "undefined"){
                    elem=$('body');
                }
                var $temp = $("<input style='position:absolute; left:-5000px'>");
                elem.append($temp);
                $temp.val(text).select();
                document.execCommand("copy");
                $temp.remove();
            }

    }
};
module.exports=coreObj;
}).call(this)}).call(this,typeof global !== "undefined" ? global : typeof self !== "undefined" ? self : typeof window !== "undefined" ? window : {})
},{}],4:[function(require,module,exports){
var datetimepickerobject= {
    SetDateTimePicker: function () {
        try {
            $(".app-date-picker:not(.added-picker)").datetimepicker({
                pickTime: false,
                timepicker: false,
                useStrict: true,
                format: "Y-m-d",
                scrollInput: false,
                onSelectDate: function (ct, $i) {
                    $i.trigger("input");
                    $i.trigger("keyup");
                },
                onShow: function (ct, elem) {
                    var thisval = elem.val();
                    var dv = new Date(thisval.replace(/-/g, "/"));
                    var md = dv.getMonth() + 1;
                    if (md < 10) {
                        md = "0" + md;
                    }
                    thisval = dv.getFullYear() + "/" + md + "/" + dv.getDate();

                    var data_min_date = elem.data('min-date');
                    var data_min_elem = elem.data('min-elem');
                    var data_min_elem_data = $(data_min_elem).val();
                    if (data_min_elem_data) {
                        var dt = new Date(data_min_elem_data.replace(/-/g, "/"));
                        var m = dt.getMonth() + 1;
                        if (m < 10) {
                            m = "0" + m;
                        }
                        data_min_elem_data = dt.getFullYear() + "/" + m + "/" + dt.getDate();
                    }

                    var data_max_date = elem.data('max-date');
                    var data_max_elem = elem.data('max-elem');
                    var data_max_elem_val = $(data_max_elem).val();
                    if (data_max_elem_val) {
                        var d = new Date(data_max_elem_val.replace(/-/g, "/"));
                        var m = d.getMonth() + 1;
                        if (m < 10) {
                            m = "0" + m;
                        }
                        data_max_elem_val = d.getFullYear() + "/" + m + "/" + d.getDate();
                    }
                    var opt_min_date = false;
                    if (data_min_elem && data_min_elem_data != "") {
                        opt_min_date = data_min_elem_data;
                        if (thisval == opt_min_date && opt_min_date != data_min_date) {
                            opt_min_date = data_min_date;
                        }
                    } else if (data_min_date && data_min_date != "") {
                        opt_min_date = data_min_date;
                    }

                    var opt_max_date = false;
                    if (data_max_elem && data_max_elem_val != "") {
                        opt_max_date = data_max_elem_val;
                        if (thisval == opt_max_date && opt_max_date != data_max_date) {
                            opt_max_date = data_max_date;
                        }
                    } else if (data_max_date && data_max_date != "") {
                        opt_max_date = data_max_date;
                    }

                    if (opt_min_date) {
                        opt_min_date = opt_min_date.replace(/-/g, "/");
                    }
                    if (opt_max_date) {
                        opt_max_date = opt_max_date.replace(/-/g, "/");
                    }
                    this.setOptions({
                        minDate: opt_min_date,
                        maxDate: opt_max_date

                    });
                }
            });
            $(".app-date-picker:not(.added-picker)").addClass("added-picker");
        } catch (e) {
            console.log(e.message);
        }
        try {
            $(".app-datetime-picker:not(.added-picker)").datetimepicker({
                pickTime: false,
                useStrict: true,
                step: 15,
                format: "Y-m-d H:i",
                onSelectDate: function (ct, $i) {
                    $i.trigger("input");
                    $i.trigger("keyup");
                },
                onSelectTime: function (ct, $i) {
                    $i.trigger("input");
                    $i.trigger("keyup");
                }
            });
            $(".app-datetime-picker:not(.added-picker)").addClass("added-picker");
        } catch (e) {
        }
        try {
            $(".app-time-picker:not(.added-picker)").datetimepicker({
                datepicker: false,
                format: 'H:i',
                step: 15,
                //mask:'23:59',disabled for some problem
                useStrict: true,
                onSelectDate: function (ct, $i) {
                    $i.trigger("input");
                    $i.trigger("keyup");
                },
                onSelectTime: function (ct, $i) {
                    $i.trigger("input");
                    $i.trigger("keyup");
                }
            });
            $(".app-time-picker:not(.added-picker)").addClass("added-picker");
        } catch (e) {
        }
    },
    SetDateGridPicker: function () {
        try {
            $(".gs-date-picker-grid-options").each(function (e) {
                if (!$(this).hasClass("addedDate")) {
                    $(this).addClass("addedDate");
                    var pickerObj = $(this).find(">input");
                    var type = $(this).attr("data-type");
                    var config = {
                        pickTime: true,
                        timepicker: false,
                        useStrict: true,
                        format: "Y-m-d",
                        onChangeDateTime: function (ct, $i) {
                            pickerObj.val(ct.dateFormat('Y-m-d'));
                        }
                    };
                    if (type == "date" || type == "daterange") {
                        config.pickTime = false;
                        config.timepicker = false;
                        config.format = "Y-m-d";

                    } else if (type == "time" || type == "timerange") {
                        config.pickTime = true;
                        config.timepicker = true;
                        config.datepicker = false;
                        config.format = "H:i";
                        config.onChangeDateTime = function (ct, $i) {
                            pickerObj.val(ct.dateFormat('H:i'));
                        }
                    } else if (type == "datetimerange") {
                        config.pickTime = true;
                        config.timepicker = true;
                        config.onChangeDateTime = function (ct, $i) {
                            pickerObj.val(ct.dateFormat('Y-m-d H:i'));
                        }
                    }
                    $(this).datetimepicker(config);
                }
            });

        } catch (e) {
            gsl(e);
        }
    },
    UnsetDateGridPicker: function () {
        try {
            $(".gs-date-picker-grid-options.addedDate").each(function (e) {
                $(this).removeClass("addedDate").datetimepicker('destroy');
            });

        } catch (e) {
            gsl(e);
        }
    }
}

module.exports=datetimepickerobject;

},{}],5:[function(require,module,exports){
'use strict';
var dependable= {
    SetDependable: function () {
        var in_added_event_list = [];
        jQuery(".has_depend_fld:not(.added-dpnds)").each(function () {
            var thisObj = jQuery(this);
            var inputtype = thisObj.attr("type");
            var class_prefix = thisObj.data("class-prefix");
            var name = thisObj.attr("name");
            if (!class_prefix) {
                class_prefix = "fld-" + name.replace(/[\[\]\_]/g, "-").replace(/\-$/g, "");
            }
            thisObj.addClass("added-dpnds");
            if (in_added_event_list.indexOf("[name=" + name + "]") == -1) {
                in_added_event_list.push("[name=" + name + "]");
                jQuery("[name='" + name + "']").on("change", function (e) {
                    dependable.setDependableSettings(thisObj, class_prefix);

                });
                dependable.setDependableSettings(thisObj, class_prefix);
            }
        });
        jQuery(".has_depend_fld2:not(.added-dpnds)").each(function () {
            var thisObj = jQuery(this);
            var inputtype = thisObj.attr("type");
            var class_prefix = thisObj.data("class-prefix");
            var name = thisObj.attr("name");
            if (!class_prefix) {
                class_prefix = "fld-" + name.replace(/[\[\]\_]/g, "-").replace(/\-$/g, "");
            }
            thisObj.addClass("added-dpnds");
            if (in_added_event_list.indexOf("[name=" + name + "]") == -1) {
                in_added_event_list.push("[name=" + name + "]");
                jQuery("[name='" + name + "']").on("change", function (e) {
                    dependable.setDependableSettings2(thisObj, class_prefix);
                });
                dependable.setDependableSettings2(thisObj, class_prefix, true);
            }
        });
    },
    hideNestedDependable:function(hiddenFlields,clen){
        try {
            if(typeof clen =="undefined"){
                clen=1;
            }
            hiddenFlields.find(".has_depend_fld:not(input[type=radio],input[type=checkbox])").val('').find('option').prop("selected", false);
            var chiledDepends = hiddenFlields.find("input[type=radio].has_depend_fld:not(.not-zero-value),input[type=checkbox].has_depend_fld:not(.not-zero-value)");
            if (chiledDepends.length > 0) {
                chiledDepends.each(function () {
                    var thisObj2 = jQuery(this);
                    var name = thisObj2.attr("name");
                    thisObj2.prop("checked", false).trigger('change');
                    var class_prefix = thisObj2.data("class-prefix");
                    var name = thisObj2.attr("name");
                    if (!class_prefix) {
                        class_prefix = "fld-" + name.replace(/[\[\]\_]/g, "-").replace(/\-$/g, "");
                    }
                    var NhiddenFlields = jQuery("." + class_prefix);
                    NhiddenFlields.fadeOut('fast', function () {
                        NhiddenFlields.addClass('d-none');
                        NhiddenFlields.find("input,select,textarea").prop("disabled", true).trigger('change');
                        NhiddenFlields.find(".has_depend_fld:not(input[type=radio],input[type=checkbox])").val('').find('option').prop("selected", false);
                    });
                });
            }
            hiddenFlields.each(function(){
                dependable.setDependableSettings(jquery(thisObj), class_prefix);
            })

        }catch(e){

        }
    },

    setDependableSettings: function (elem, class_prefix) {
        try {
            var name = elem.attr("name");
            var type = elem.attr("type");
            if (type == "checkbox" || type == "radio") {
                var selectedAction = jQuery("[name='" + name + "']:checked").val();
                if (selectedAction == undefined) {
                    selectedAction = jQuery("[name='" + name + "'][type=hidden]").val();
                }
            } else {
                var selectedAction = jQuery("[name='" + name + "']").val();
            }

            if (selectedAction) {
                selectedAction = selectedAction.toLowerCase();
                var hiddenFlields =jQuery("." + class_prefix + ":not(." + class_prefix + "-" + selectedAction + ")");
                if (hiddenFlields.length > 0) {
                    dependable.hideNestedDependable(hiddenFlields);
                    hiddenFlields.fadeOut('fast', function () {
                        hiddenFlields.addClass('d-none');
                        hiddenFlields.find("input,select,textarea").prop("disabled", true).trigger('change');
                        dependable.showDependableSettings(class_prefix, selectedAction);
                    });

                } else {
                    dependable.showDependableSettings(class_prefix, selectedAction);
                }
                elem.closest("form").find("[type=submit]").prop("disabled", false);
            }
        } catch (e) { }
    },
    setDependableSettings2: function (elem, class_prefix, is_first_load) {
        try {
            var name = elem.attr("name");
            var type = elem.attr("type");
            if (type == "checkbox" || type == "radio") {
                var selectedAction = jQuery("[name='" + name + "']:checked").val();
                if (selectedAction == undefined) {
                    selectedAction = jQuery("[name='" + name + "'][type=hidden]").val();
                }
            } else {
                var selectedAction = jQuery("[name='" + name + "']").val();
            }
            if (selectedAction) {
                selectedAction = selectedAction.toLowerCase();
                var hiddenFlields = jQuery("." + class_prefix + ":not(." + class_prefix + "-" + selectedAction + ")");
                try{ elem.closest("form").find("[type=submit]").prop("disable",false);  }catch (e) { }
                if (hiddenFlields.length > 0) {
                    hiddenFlields.prop("disabled", true);
                    var elems=hiddenFlields.find("input,select,textarea");
                    try {
                        var bvform=  elem.closest('form').data('bootstrapValidator');
                        if(elems.length>0) {elems.each(function () {  bvform.updateStatus($(this).attr("name"), 'NOT_VALIDATED');  });}
                        try{ bvform.updateStatus(hiddenFlields.attr("name"), 'NOT_VALIDATED');}catch (e) {}
                    }catch (e) {}
                    elems.prop("disabled", true);
                    dependable.showDependableSettings2(class_prefix, selectedAction, is_first_load);
                } else {
                    dependable.showDependableSettings2(class_prefix, selectedAction, is_first_load);
                }
                elem.closest("form").find("[type=submit]").prop("disabled", false);
            }
        } catch (e) {
             gcl(e.message);
        }
    },
    showDependableSettings: function (class_prefix, selectedAction) {
        var activeFlields = jQuery("." + class_prefix + "-" + selectedAction).removeClass("hidden").removeClass("d-none");

        activeFlields.show();
        activeFlields.find("input,select,textarea").prop("disabled", false);
        try{
            if(jQuery("." + class_prefix + "-" + selectedAction).hasClass('fld-focus')){
                jQuery("." + class_prefix + "-" + selectedAction).focus();
            }else{
                jQuery("." + class_prefix + "-" + selectedAction).find('.fld-focus').focus();
            }
            activeFlields.each(function () {
                try {
                    var ev = $(this).data('on-active');

                    if (ev) {
                        eval(ev + "($(this))");
                    }
                }catch (e) {}
            });
        }catch (e) {

        }
    },
    showDependableSettings2: function (class_prefix, selectedAction, is_first_load) {
        var activeFlields = jQuery("." + class_prefix + "-" + selectedAction);

        var acFlds = activeFlields.prop("disabled", false).find("input,select,textarea").prop("disabled", false);
        if (!is_first_load) {
            if(activeFlields.is("input") || activeFlields.is("select")){
                activeFlields.focus();
            }else {
                acFlds.first().focus();
            }
        }
    },

}
var main_object= {
    SetDependable: function () {
        var in_added_event_list = [];
        jQuery(".has_depend_fld:not(.added-dpnds)").each(function () {
            var thisObj = jQuery(this);
            var inputtype = thisObj.attr("type");
            var class_prefix = thisObj.data("class-prefix");
            var name = thisObj.attr("name");
            if (!class_prefix) {
                class_prefix = "fld-" + name.replace(/[\[\]\_]/g, "-").replace(/\-$/g, "");
            }
            thisObj.addClass("added-dpnds");
            if (in_added_event_list.indexOf("[name=" + name + "]") == -1) {
                in_added_event_list.push("[name=" + name + "]");
                jQuery("[name='" + name + "']").on("change", function (e) {
                    dependable.setDependableSettings(thisObj, class_prefix);
                });
                dependable.setDependableSettings(thisObj, class_prefix);
            }
        });
        jQuery(".has_depend_fld2:not(.added-dpnds)").each(function () {
            var thisObj = jQuery(this);
            var inputtype = thisObj.attr("type");
            var class_prefix = thisObj.data("class-prefix");
            var name = thisObj.attr("name");
            if (!class_prefix) {
                class_prefix = "fld-" + name.replace(/[\[\]\_]/g, "-").replace(/\-$/g, "");
            }
            thisObj.addClass("added-dpnds");
            if (in_added_event_list.indexOf("[name=" + name + "]") == -1) {
                in_added_event_list.push("[name=" + name + "]");
                jQuery("[name='" + name + "']").on("change", function (e) {
                    dependable.setDependableSettings2(thisObj, class_prefix);
                });
                dependable.setDependableSettings2(thisObj, class_prefix, true);
            }
        });
    },
    SetLiveElementDependable:function(){
        jQuery('body').on("click",'.btn-depends-toggle',function(e){
            jQuery(this).closest('.apb-depends-elem ').toggleClass("apb-show");
        });
    }
}
module.exports=main_object;
},{}],6:[function(require,module,exports){
var dragdrop= {
    init:function(){
        jQuery(".app-drag-drop:not(.added-dd)").each(function () {
            var thisobj=jQuery(this);
            var thisonly=this;
            thisobj.addClass("added-dd");
            var wrapper=jQuery('<div class="dd-upload-wrap"><span class="dd-msg">Drag and drop a file or click to select </span></div>');
            thisobj.after(wrapper);
            thisobj.appendTo(wrapper);
            wrapper.bind('dragover', function () {
                wrapper.addClass('dd-file-dropping');
            });
            wrapper.bind('dragleave', function () {
                wrapper.removeClass('dd-file-dropping');
            });
            thisobj.bind('dragover', function () {
                wrapper.addClass('dd-file-dropping');
            });
            thisobj.bind('dragleave', function () {
                wrapper.removeClass('dd-file-dropping');
            });
            thisobj.on("change",function (e) {
                wrapper.removeClass('dd-file-dropping');
                if (this.files && this.files[0]) {
                    wrapper.find(".dd-msg").html(this.files[0].name);
                }
            });
        });
    }
}
module.exports=dragdrop;



},{}],7:[function(require,module,exports){
var appcore =require("./core");
var FilePicker={
    SetFilePicker:function() {
        $(".app-file-upload:not(.added-fin)").each(function () {
            var mainObj = $(this);
            var inputname = mainObj.data("name");
            var accept = mainObj.data("accept");
            console.log(accept);
            var acceptstr = typeof(accept) != "undefined" ? 'accept="' + accept : '';
            var inputObj = $('<input type="file" name="' + inputname + '" style="display:none;" ' + acceptstr + '">');
            mainObj.on("click", function () {
                inputObj.trigger('click');
            });
            mainObj.after(inputObj);


            var fileContainer = $('<div class="app-fi-row d-none"></div>');
            var fileUploaderText = $('<div class="text-center"></div>');
            fileUploaderText.html(mainObj.html());
            mainObj.html('');

            var fileErrorContainer = $('<div class="row"></div>');

            var icon = $('<i class="far fa-file"></i>');
            var icon_container = $('<div class="app-fi-icon-container"></div>');
            icon_container.append(icon);
            var fileName = $('<div class="app-fi-name-container"></div>');
            var fileMag = $('<div class="col-sm text-danger app-fi-msg"></div>');
            var maxsize = mainObj.data('maxsize');
            var maxfilezone = parseInt(mainObj.data('maxsize'));
            fileContainer.removeClass('d-none').hide();
            fileContainer.append( icon_container);
            fileContainer.append(fileName);
            fileErrorContainer.append(fileMag);
            var icon_fa_list = {"application_pdf": "far fa-file-pdf","image_png":"far fa-file-image","image_jpeg":"far fa-file-image","image_jpg":"far fa-file-image","image_svg":"far fa-file-image"};
            mainObj.append(fileUploaderText);
            mainObj.append(fileContainer);
            mainObj.append(fileErrorContainer);
            inputObj.on("change", function (e) {
                console.log(this.files[0]);
                var fa_icon = "far fa-file";
                var fileExtension = this.files[0].name.substr(-4);
                var fileAccepts = mainObj.data("accept");
                var iconName = this.files[0].type.replace('/', '_');
                if (typeof (icon_fa_list[iconName]) != "undefined") {
                    fa_icon = icon_fa_list[iconName];
                }
                icon.attr("class",fa_icon);
                var isExtensionOk = typeof (fileAccepts) == 'undefined' || fileAccepts.indexOf(fileExtension) != -1;

                if (isExtensionOk) {
                    fileMag.html('');
                    fileContainer.hide();
                    if (typeof maxsize != 'undefined' && (maxfilezone < this.files[0].size)) {
                        fileMag.html('<div class="animated fadeIn  text-center">Your uploaded file is larger then allowed size</div>');
                    } else {
                        fileMag.html('');
                        fileUploaderText.hide();
                        fileContainer.show();
                        if(this.files[0].name.length>20){
                            fileName.html('...'+(this.files[0].name.substr(-20)));
                        }else {
                            fileName.html(this.files[0].name);
                        }

                        fileName.append('<small>'+appcore.humanFileSize(this.files[0].size)+'</small>');
                    }
                } else {
                    fileMag.html('<div class="animated fadeIn  text-center">This file does not allowed to upload</div>');
                }

            });
            mainObj.addClass("added-fin");
        });

    },
    SetImageInput:function(){
        var fileCounter=1;
        $(".app-image-input:not(.added-apim)").each(function() {
            try {
                fileCounter++;
                var mainObj = $(this);
                mainObj.addClass("added-apim");
                var on_change=mainObj.data("change");
                if(on_change){
                    on_change=eval(on_change);
                }
                var has_delete=mainObj.data("delete");

                var imgObj = null
                var imgObjstr = mainObj.data("img-id");
                if (imgObjstr) {
                    imgObj = $(imgObjstr);
                } else {
                    imgObj = mainObj;
                }
                var inputname = mainObj.data("name");
                if (!inputname || inputname == "") {
                    inputname = "file_" + fileCounter;
                }
                var inputObj = $('<input type="file" name="' + inputname + '" style="display:none;" accept="image/*">');
                var delete_btn=null;
                if(has_delete){
                    delete_btn=$('<button style="display:none;position: absolute;right: 16px;top: 6px;font-size: 9px;" class="btn btn-danger btn-xs"><fa class="fa-trash"></fa></button>');
                    delete_btn.click(function(e){
                        e.preventDefault();
                        inputObj.val("");
                        var noimg=mainObj.data("date-noimage");
                        if(!noimg){
                            noimg=base_url+"images/no-image-2.png";
                        }
                        mainObj.attr("src",noimg);
                        $(this).hide();
                        try{
                            if(typeof on_change == "function"){
                                on_change("");
                            }
                        }catch(e){ }
                    });
                    if(mainObj.data("show-delete")){
                        delete_btn.show();
                    }
                    mainObj.after(delete_btn);
                }
                mainObj.on("click", function() {
                    inputObj.trigger('click');
                });
                inputObj.on("change", function(e) {
                    var fr = new FileReader();
                    // when image is loaded, set the src of the image where you want to display it
                    fr.onload = function(e) {
                        imgObj.attr("src", this.result);
                        mainObj.after(inputObj);
                        try{
                            if(delete_btn){
                                delete_btn.show();
                            }
                        }catch(e){}
                        try{
                            gcl(on_change);
                            if(typeof on_change == "function"){
                                on_change(this.result);
                            }
                        }catch(e){ }
                    };
                    fr.readAsDataURL(this.files[0]);
                });
            } catch (e) {
                gcl(e.message);
            }
        });
    }
}
module.exports=FilePicker;
},{"./core":3}],8:[function(require,module,exports){
var Inputpicker= {
    SetColorPicker: function () {
        try {
            $('.app-color-picker').wpColorPicker();
        } catch (e) {
            try {
                $('.app-color-picker:not(.a-cp)').spectrum({
                    showInput: true,
                    palette: [
                        ["", "#fff", "#000", "#444", "#666", "#999", "#ccc", "#eee"],
                        ["#f00", "#f90", "#ff0", "#0f0", "#0ff", "#00f", "#90f", "#f0f"],
                        ["#f4cccc", "#fce5cd", "#fff2cc", "#d9ead3", "#d0e0e3", "#cfe2f3", "#d9d2e9", "#ead1dc"],
                        ["#ea9999", "#f9cb9c", "#ffe599", "#b6d7a8", "#a2c4c9", "#9fc5e8", "#b4a7d6", "#d5a6bd"],
                        ["#e06666", "#f6b26b", "#ffd966", "#93c47d", "#76a5af", "#6fa8dc", "#8e7cc3", "#c27ba0"],
                        ["#c00", "#e69138", "#f1c232", "#6aa84f", "#45818e", "#3d85c6", "#674ea7", "#a64d79"],
                        ["#900", "#b45f06", "#bf9000", "#38761d", "#134f5c", "#0b5394", "#351c75", "#741b47"],
                        ["#600", "#783f04", "#7f6000", "#274e13", "#0c343d", "#073763", "#20124d", "#4c1130"]
                    ]
                });
                $('.app-color-picker:not(.a-cp)').addClass('a-cp');
            } catch (e) {

            }
        }
    },
    SetIconPicker: function () {
        try {
            $('.app-icon-picker:not(.a-ip)').iconpicker({
                footer: true,
                header: true,
                search: true,
                iconset: 'fontawesome5'
            });
        } catch (e) {

        }

        try {
            $(".app-icon-picker:not(.a-ip)").closest(".mfp-wrap").removeAttr("tabindex");
        } catch (e) {
        }
        $('.app-icon-picker:not(.a-ip)').addClass('a-ip');
    },
    GetDefaultAjaxOption: function (url) {
        return {
            ajax: {
                url: url,
                data: function () {
                    var params = {
                        q: '{{{q}}}'
                    };
                    return params;
                }
            },
            locale: {
                emptyTitle: 'Search...',
                statusInitialized: 'Start typing a search querye',
                currentlySelected: 'Currently Selected',
                searchPlaceholder: "",
                errorText: 'Unable to retrieve results',
                statusNoResults: appGlobalLang.bs_noneResultsTex,
                statusTooShort: 'Please enter more characters',
                statusSearching: appGlobalLang.bs_seaching
            },
            minLength: 3,
            preprocessData: function (data) {
                return data;
            },
            preserveSelected: true
        }
    },
    SetSelectPicker: function () {
        try {
            try {
                jQuery.fn.selectpicker.Constructor.DEFAULTS.noneResultsText = appGlobalLang.bs_noneResultsText;
                jQuery.fn.selectpicker.Constructor.DEFAULTS.noneSelectedText = appGlobalLang.bs_noneSelectedText;
            } catch (e) {
            }


            $('.app-select-picker:not(.added-spick)').each(function () {
                if ($(this).data('live-url')) {
                    var newOption = Inputpicker.GetDefaultAjaxOption($(this).data('live-url'));
                    newOption.ajax.url = $(this).data('live-url');
                    if ($(this).data('src-type-str')) {
                        newOption.locale.statusInitialized = $(this).data('src-type-str');
                    }

                    $(this).addClass('added-spick')
                        .selectpicker()
                        .ajaxSelectPicker(newOption);
                } else {
                    $(this).addClass('added-spick')
                        .selectpicker();
                }

            });
        } catch (e) {
            console.log(e);
            console.log("No select picker lib found");
        }

        //now select 2
        try {
            $('.app-select2-picker:not(.added-s2pick)').each(function () {
                try {
                    var isParent = $(this).closest(".mfp-wrap,.modal");
                    if (jQuery.isEmptyObject(isParent) || $(this).closest(".mfp-wrap,.modal").length > 0) {
                        $(this).addClass('added-s2pick').select2({
                            theme: 'bootstrap4',
                            dropdownParent: isParent,
                            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
                            placeholder: $(this).data('placeholder'),
                            allowClear: Boolean($(this).data('allow-clear')),
                            closeOnSelect: !$(this).attr('multiple'),

                        });
                    } else {
                        $(this).addClass('added-s2pick').select2({
                            theme: 'bootstrap4',
                            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
                            placeholder: $(this).data('placeholder'),
                            allowClear: Boolean($(this).data('allow-clear')),
                            closeOnSelect: !$(this).attr('multiple'),
                        });

                    }
                } catch (e) {
                    $(this).select2({ theme: "bootstrap" });
                }



            });
        } catch (e) {

        }
    },
    SetEditableSelect: function () {
        try {
            $('.apbd-editable:not(.added-edpkr)').editableSelect().addClass('added-edpkr');
        } catch (e) {
        }
    },
    SetInputTag: function () {
        try {
            $('.apbd-tag-input:not(.added-tgin)').amsifySuggestags().addClass('added-tgin');
        } catch (e) {
        }
    }
}
module.exports=Inputpicker;
},{}],9:[function(require,module,exports){
'use strict';
var notification=require('./notification');
function onLightboxOpen(){
    if(!$(".mfp-content").hasClass('container')){
        $(".mfp-content").addClass("container");
    }
}
function beforeOpen(){
    $("body").addClass("apd-lg-showing");
}
function onCloseLGOpen(){
    $("body").removeClass("apd-lg-showing");
}
function getLoadingLanguage(){
    try {
        var loadingText = appGlobalLang.Loading;
    } catch (e) {
        var loadingText = "Loading";
    }
    return loadingText+'..';
}

var setPopUpAjax= {
    AppLightboxValidatorObject: null,
    SetLightbox: function () {
        try {
            if ($.magnificPopup.instance) {
                $.magnificPopup.instance.popupsCache = {};
            }

            $(".popupform,.Popupform,.popupformWR,.PopupformWR,.popupimg,.Popupimg,.popupinline,.Popupinline,.PopupInline").each(function () {
                var effect = $(this).data("effect");
                if (!effect) {
                    $(this).attr("data-effect", "mfp-move-from-top");
                    $(this).data("effect", "mfp-move-from-top");
                }
            });
            $(".popupform:not(.apopf),.Popupform:not(.apopf)").magnificPopup({
                type: 'ajax',
                preloader: true,
                removalDelay: 500,
                closeOnBgClick: false,
                closeBtnInside: true,
                overflowY: 'auto',
                fixedBgPos: false,
                zoom: {enabled: false},
                tLoading: '<i class="fa fa-circle-o faa-burst animated"></i> &nbsp;'+getLoadingLanguage(),
                callbacks: {
                    beforeOpen: function () {
                        beforeOpen();
                        this.st.mainClass = this.st.el.attr('data-effect');
                    },
                    open: function () {
                        onLightboxOpen();
                    },
                    close: function () {
                        onCloseLGOpen();
                    },
                    updateStatus: function (data) {
                        if (data.status === 'ready') {
                            APPSBDAPPJS.core.CallOnLoadLightbox();
                        }
                    }
                }
            });
            $(".popupimg:not(.apopf),.Popupimg:not(.apopf)").magnificPopup({
                type: 'image',
                closeOnContentClick: true,
                mainClass: 'mfp-img-mobile',
                callbacks: {
                    resize: function () {
                        var img = this.content.find('img');
                        img.css('max-height', $(window).height() - 50);
                        img.css('width', 'auto');
                        img.css('max-width', 'auto');
                    }
                    ,
                    elementParse: function (qw) {
                        try {
                            if (qw.el.context.tagName.toUpperCase() == "IMG") {
                                qw.src = qw.el.attr('src');
                            }
                        } catch (e) {
                            try {
                                if (qw.el[0].nodeName == "IMG") {
                                    qw.src = qw.el[0].src;
                                }
                            } catch (eg) {
                            }
                        }
                    }
                }
            });

            $(".popupvid:not(.apopf),.Popupvid:not(.apopf)").magnificPopup({
                type: 'iframe',
                iframe: {
                    patterns: {
                        youtube: {
                            index: 'youtube.com/',
                            id: function(url) {
                                var m = url.match(/[\\?\\&]v=([^\\?\\&]+)/);
                                if ( !m || !m[1] ) return null;
                                return m[1];
                            },
                            src: '//www.youtube.com/embed/%id%?autoplay=1'
                        },
                        vimeo: {
                            index: 'vimeo.com/',
                            id: function(url) {
                                var m = url.match(/(https?:\/\/)?(www.)?(player.)?vimeo.com\/([a-z]*\/)*([0-9]{6,11})[?]?.*/);
                                if ( !m || !m[5] ) return null;
                                return m[5];
                            },
                            src: '//player.vimeo.com/video/%id%?autoplay=1'
                        }
                    }
                }
            });

            $(".popupinline:not(.apopf),.Popupinline:not(.apopf),.PopupInline:not(.apopf)").magnificPopup({
                type: 'inline',
                preloader: true,
                removalDelay: 500,
                closeBtnInside: true,
                overflowY: 'auto',
                closeOnBgClick: false,
                fixedBgPos: false,
                zoom: {enabled: false},
                tLoading: '<i class="fa fa-circle-o faa-burst animated"></i> &nbsp;'+getLoadingLanguage(),
                callbacks: {
                    beforeOpen: function () {
                        beforeOpen();
                        this.st.mainClass = this.st.el.attr('data-effect');
                        try {
                            $(this.st.el.attr('href')).addClass("mfp-with-anim");
                        } catch (e) {
                        }
                    },
                    open: function () {
                        onLightboxOpen();
                    },
                    close: function () {
                        onCloseLGOpen();
                    },
                    updateStatus: function (data) {
                        if (data.status === 'ready') {
                            APPSBDAPPJS.core.CallOnLoadLightbox();
                        }
                    }
                }
            });


        } catch (e) {
            gcl(e);
        }

        try {
            $(".popupformWR:not(.apopf),.PopupformWR:not(.apopf)").magnificPopup({
                type: 'ajax',
                preloader: true,
                removalDelay: 500,
                closeBtnInside: true,
                overflowY: 'auto',
                closeOnBgClick: false,
                fixedBgPos: false,
                zoom: {enabled: false},
                tLoading: '<i class="fa fa-circle-o faa-burst animated"></i> &nbsp;'+getLoadingLanguage(),
                callbacks: {
                    beforeOpen: function () {
                        beforeOpen();
                        this.st.mainClass = this.st.el.attr('data-effect');
                    },
                    open: function () {

                        onLightboxOpen();
                    },
                    close: function () {
                        onCloseLGOpen();
                        APPSBDAPPJS.core.CallOnCloseLightbox();
                    },
                    updateStatus: function (data) {
                        if (data.status === 'ready') {
                            APPSBDAPPJS.core.CallOnLoadLightbox();
                        }
                    }
                }
            });
        } catch (e) {
            gcl(e);
        }

        try {
            $(".popupformIF:not(.apopf),.PopupformIF:not(.apopf)").magnificPopup({
                type: 'iframe',
                preloader: true,
                removalDelay: 500,
                closeBtnInside: true,
                overflowY: 'auto',
                closeOnBgClick: false,
                fixedBgPos: false,
                zoom: {enabled: false},
                tLoading: '<i class="fa fa-circle-o faa-burst animated"></i> &nbsp;'+getLoadingLanguage(),
                callbacks: {
                    beforeOpen: function () {
                        beforeOpen();
                        this.st.mainClass = this.st.el.attr('data-effect');
                    },
                    open: function () {
                        onLightboxOpen();
                    },
                    close: function () {
                        onCloseLGOpen();
                    },
                    updateStatus: function (data) {
                        if (data.status === 'ready') {
                            APPSBDAPPJS.core.CallOnLoadLightbox();
                        }
                    }
                }
            });
        } catch (e) {
            gcl(e);
        }
        try {
            $(".popupformWIF:not(.apopf),.PopupformWIF:not(.apopf)").magnificPopup({
                type: 'iframe',
                preloader: true,
                removalDelay: 500,
                closeBtnInside: true,
                overflowY: 'auto',
                closeOnBgClick: false,
                fixedBgPos: false,
                zoom: {enabled: false},
                tLoading: '<i class="fa fa-circle-o faa-burst animated"></i> &nbsp;'+getLoadingLanguage(),
                callbacks: {
                    beforeOpen: function () {
                        beforeOpen();
                        this.st.mainClass = this.st.el.attr('data-effect');
                    },
                    open: function () {
                        onLightboxOpen();
                    },
                    close: function () {
                        onCloseLGOpen();
                        APPSBDAPPJS.core.CallOnCloseLightbox()
                    },
                    updateStatus: function (data) {
                        if (data.status === 'ready') {
                            APPSBDAPPJS.core.CallOnLoadLightbox();
                        }
                    }
                }
            });
        } catch (e) {
            gcl(e);
        }
        $(".popupform:not(.apopf),.Popupform:not(.apopf),.popupformWR:not(.apopf),.PopupformWR:not(.apopf),.popupimg:not(.apopf),.Popupimg:not(.apopf),.popupinline:not(.apopf),.Popupinline:not(.apopf),.PopupInline:not(.apopf)").addClass("apopf");

    },
    ShowPopupForm: function (url, isIframe) {
        if (typeof isIframe == "undefined") {
            isIframe = false;
        }
        var obj = $('<a data-effect="mfp-move-from-top">').attr("href", url);
        obj.magnificPopup({
            type: 'ajax',
            preloader: true,
            removalDelay: 500,
            closeBtnInside: true,
            overflowY: 'auto',
            closeOnBgClick: false,
            fixedBgPos: false,
            zoom: {enabled: false},
            tLoading: '<i class="fa fa-circle-o faa-burst animated"></i> &nbsp;'+getLoadingLanguage(),
            callbacks: {
                beforeOpen: function () {
                    beforeOpen();
                    this.st.mainClass = this.st.el.attr('data-effect');
                },
                open: function () {
                },
                close: onCloseLGOpen,
                updateStatus: function (data) {
                    if (data.status === 'ready') {
                        onLightboxOpen();
                    }
                }
            }
        }).click();
    },
    SetLightBoxAjax: function () {
        try {
            var Ajaxbostrapvalidator = $("form.app-lb-ajax-form").bootstrapValidator({
                excluded: ':disabled,:hidden',
                message: 'This value is not valid',
                feedbackIcons: {
                    valid: 'fa fa-check',
                    invalid: 'fa fa-times',
                    validating: 'fa fa-refresh'
                },
                onChangeStatus: function (validator, form) {

                },
                submitHandler: function (validator, form, submitButton) {

                    if (form.data("multipart")) {
                        $(".lightboxWraper").fadeIn();
                        var formData = new FormData(form[0]);
                        formData = APPSBDAPPJS.core.SetCsrfParam(formData);
                        var contentType = false;
                        var processData = false;
                        var async = false;
                        notification.ShowWaitinglight(true);
                    } else {
                        var formData = APPSBDAPPJS.core.SetCsrfParam(form.serialize());
                        var contentType = 'application/x-www-form-urlencoded; charset=UTF-8';
                        var processData = true;
                        var async = true;
                    }
                    var ajaxFunction=function(formData){
                        $.ajax({
                            type: "POST",
                            url: form.attr('action'),
                            data: formData,
                            processData: processData,
                            contentType: contentType,
                            cache: false,
                            async: async,
                            beforeSend: function () {
                                notification.ShowWaitinglight(true);
                            },
                            success: function (data) {
                                notification.ShowWaitinglight(false,function(){
                                    var rData = $('<div/>');
                                    rData.html(data);
                                    var LightboxB = rData.find('#LightBoxBody');
                                    $("#popup-container").attr("class", rData.find('#popup-container').attr("class"));
                                    $('#LightBoxBody').html(LightboxB.html());
                                    APPSBDAPPJS.core.CallOnLoadLightbox();
                                });
                            },
                            complete: function () {
                                setTimeout(function(){notification.ShowWaitinglight(false);},2000);
                            }
                        });
                    }
                    try {
                        var handler = form.data('handler');
                        if (typeof handler != "undefined") {
                            if (handler) {
                                if(typeof handler =="function"){
                                    handler(form, formData,ajaxFunction);
                                }else {
                                    var com = eval(handler);
                                    com(form, formData,ajaxFunction);
                                }
                            }
                        } else {
                            ajaxFunction(formData);
                        }
                    }catch(e){
                        ajaxFunction(formData);
                    }


                }
            });
            Ajaxbostrapvalidator.addClass("Addesdd");
            try {
                // Init iCheck elements
                Ajaxbostrapvalidator.find('.cbox-control').iCheck({
                    checkboxClass: 'icheckbox_square-green',
                    radioClass: 'iradio_square-green'
                })
                // Called when the radios/checkboxes are changed
                    .on('ifChanged', function (e) {
                        // Get the field name
                        try {
                            var field = $(this).attr('name');
                            var fromobj = $(this).closest("form");
                            fromobj
                            // Mark the field as not validated
                                .bootstrapValidator('updateStatus', field, 'NOT_VALIDATED')
                                // Validate field
                                .bootstrapValidator('validateField', field);
                        } catch (e) {
                        }
                    });
            } catch (e) {
            }
            setPopUpAjax.AppLightboxValidatorObject = Ajaxbostrapvalidator;
        } catch (e) {
            console.log(e);
        }

    },
    CloseLightBox:function(){
        try {
            $.magnificPopup.instance.close();
        } catch (e) {}
    }
}
jQuery(document).ready(function ($) {
    $("body").on("click", ".close-pop-up", function (e) {
        setPopUpAjax.CloseLightBox();
    });
});
module.exports=setPopUpAjax;
},{"./notification":12}],10:[function(require,module,exports){
(function($) {
    var CLOSE_EVENT = 'Close',
        BEFORE_CLOSE_EVENT = 'BeforeClose',
        AFTER_CLOSE_EVENT = 'AfterClose',
        BEFORE_APPEND_EVENT = 'BeforeAppend',
        MARKUP_PARSE_EVENT = 'MarkupParse',
        OPEN_EVENT = 'Open',
        CHANGE_EVENT = 'Change',
        NS = 'mfp',
        EVENT_NS = '.' + NS,
        READY_CLASS = 'mfp-ready',
        REMOVING_CLASS = 'mfp-removing',
        PREVENT_CLOSE_CLASS = 'mfp-prevent-close';


    var mfp, // As we have only one instance of MagnificPopup object, we define it locally to not to use 'this'
        MagnificPopup = function() {},
        _isJQ = !!(window.jQuery),
        _prevStatus,
        _window = $(window),
        _body,
        _document,
        _prevContentType,
        _wrapClasses,
        _currPopupType;

    var _mfpOn = function(name, f) {
            mfp.ev.on(NS + name + EVENT_NS, f);
        },
        _getEl = function(className, appendTo, html, raw) {
            var el = document.createElement('div');
            el.className = 'mfp-' + className;
            if (html) {
                el.innerHTML = html;
            }
            if (!raw) {
                el = $(el);
                if (appendTo) {
                    el.appendTo(appendTo);
                }
            } else if (appendTo) {
                appendTo.appendChild(el);
            }
            return el;
        },
        _mfpTrigger = function(e, data) {
            mfp.ev.triggerHandler(NS + e, data);

            if (mfp.st.callbacks) {
                // converts "mfpEventName" to "eventName" callback and triggers it if it's present
                e = e.charAt(0).toLowerCase() + e.slice(1);
                if (mfp.st.callbacks[e]) {
                    mfp.st.callbacks[e].apply(mfp, $.isArray(data) ? data : [data]);
                }
            }
        },
        _setFocus = function() {
            (mfp.st.focus ? mfp.content.find(mfp.st.focus).eq(0) : mfp.wrap).trigger('focus');
        },
        _getCloseBtn = function(type) {
            if (type !== _currPopupType || !mfp.currTemplate.closeBtn) {
                mfp.currTemplate.closeBtn = $(mfp.st.closeMarkup.replace('%title%', mfp.st.tClose));
                _currPopupType = type;
            }
            return mfp.currTemplate.closeBtn;
        },
        // Initialize Magnific Popup only when called at least once
        _checkInstance = function() {
            if (!$.magnificPopup.instance) {
                mfp = new MagnificPopup();
                mfp.init();
                $.magnificPopup.instance = mfp;
            }
        },
        // Check to close popup or not
        // "target" is an element that was clicked
        _checkIfClose = function(target) {

            if ($(target).hasClass(PREVENT_CLOSE_CLASS)) {
                return;
            }

            var closeOnContent = mfp.st.closeOnContentClick;
            var closeOnBg = mfp.st.closeOnBgClick;

            if (closeOnContent && closeOnBg) {
                return true;
            } else {

                // We close the popup if click is on close button or on preloader. Or if there is no content.
                if (!mfp.content || $(target).hasClass('mfp-close') || (mfp.preloader && target === mfp.preloader[0])) {
                    return true;
                }

                // if click is outside the content
                if ((target !== mfp.content[0] && !$.contains(mfp.content[0], target))) {
                    if (closeOnBg) {
                        // last check, if the clicked element is in DOM, (in case it's removed onclick)
                        if ($.contains(document, target)) {
                            return true;
                        }
                    }
                } else if (closeOnContent) {
                    return true;
                }

            }
            return false;
        },
        // CSS transition detection, http://stackoverflow.com/questions/7264899/detect-css-transitions-using-javascript-and-without-modernizr
        supportsTransitions = function() {
            var s = document.createElement('p').style, // 's' for style. better to create an element if body yet to exist
                v = ['ms', 'O', 'Moz', 'Webkit']; // 'v' for vendor

            if (s['transition'] !== undefined) {
                return true;
            }

            while (v.length) {
                if (v.pop() + 'Transition' in s) {
                    return true;
                }
            }

            return false;
        };

    MagnificPopup.prototype = {

        constructor: MagnificPopup,

        init: function() {
            var appVersion = navigator.appVersion;
            mfp.isIE7 = appVersion.indexOf("MSIE 7.") !== -1;
            mfp.isIE8 = appVersion.indexOf("MSIE 8.") !== -1;
            mfp.isLowIE = mfp.isIE7 || mfp.isIE8;
            mfp.isAndroid = (/android/gi).test(appVersion);
            mfp.isIOS = (/iphone|ipad|ipod/gi).test(appVersion);
            mfp.supportsTransition = supportsTransitions();

            // We disable fixed positioned lightbox on devices that don't handle it nicely.
            // If you know a better way of detecting this - let me know.
            mfp.probablyMobile = (mfp.isAndroid || mfp.isIOS || /(Opera Mini)|Kindle|webOS|BlackBerry|(Opera Mobi)|(Windows Phone)|IEMobile/i.test(navigator.userAgent));
            _body = $(document.body);
            _document = $(document);

            mfp.popupsCache = {};
        },

        open: function(data) {

            var i;

            if (data.isObj === false) {
                // convert jQuery collection to array to avoid conflicts later
                mfp.items = data.items.toArray();

                mfp.index = 0;
                var items = data.items,
                    item;
                for (i = 0; i < items.length; i++) {
                    item = items[i];
                    if (item.parsed) {
                        item = item.el[0];
                    }
                    if (item === data.el[0]) {
                        mfp.index = i;
                        break;
                    }
                }
            } else {
                mfp.items = $.isArray(data.items) ? data.items : [data.items];
                mfp.index = data.index || 0;
            }

            // if popup is already opened - we just update the content
            if (mfp.isOpen) {
                mfp.updateItemHTML();
                return;
            }

            mfp.types = [];
            _wrapClasses = '';
            if (data.mainEl && data.mainEl.length) {
                mfp.ev = data.mainEl.eq(0);
            } else {
                mfp.ev = _document;
            }

            if (data.key) {
                if (!mfp.popupsCache[data.key]) {
                    mfp.popupsCache[data.key] = {};
                }
                mfp.currTemplate = mfp.popupsCache[data.key];
            } else {
                mfp.currTemplate = {};
            }



            mfp.st = $.extend(true, {}, $.magnificPopup.defaults, data);
            mfp.fixedContentPos = mfp.st.fixedContentPos === 'auto' ? !mfp.probablyMobile : mfp.st.fixedContentPos;

            if (mfp.st.modal) {
                mfp.st.closeOnContentClick = false;
                mfp.st.closeOnBgClick = false;
                mfp.st.showCloseBtn = false;
                mfp.st.enableEscapeKey = false;
            }


            // Building markup
            // main containers are created only once
            if (!mfp.bgOverlay) {

                // Dark overlay
                mfp.bgOverlay = _getEl('bg').on('click' + EVENT_NS, function() {
                    mfp.close();
                });

                mfp.wrap = _getEl('wrap').attr('tabindex', -1).on('click' + EVENT_NS, function(e) {
                    if (_checkIfClose(e.target)) {
                        mfp.close();
                    }
                });

                mfp.container = _getEl('container', mfp.wrap);
            }

            mfp.contentContainer = _getEl('content');
            if (mfp.st.preloader) {
                mfp.preloader = _getEl('preloader', mfp.container, mfp.st.tLoading);
            }


            // Initializing modules
            var modules = $.magnificPopup.modules;
            for (i = 0; i < modules.length; i++) {
                var n = modules[i];
                n = n.charAt(0).toUpperCase() + n.slice(1);
                mfp['init' + n].call(mfp);
            }
            _mfpTrigger('BeforeOpen');


            if (mfp.st.showCloseBtn) {
                // Close button
                if (!mfp.st.closeBtnInside) {
                    mfp.wrap.append(_getCloseBtn());
                } else {
                    _mfpOn(MARKUP_PARSE_EVENT, function(e, template, values, item) {
                        values.close_replaceWith = _getCloseBtn(item.type);
                    });
                    _wrapClasses += ' mfp-close-btn-in';
                }
            }

            if (mfp.st.alignTop) {
                _wrapClasses += ' mfp-align-top';
            }



            if (mfp.fixedContentPos) {
                mfp.wrap.css({
                    overflow: mfp.st.overflowY,
                    overflowX: 'hidden',
                    overflowY: mfp.st.overflowY
                });
            } else {
                mfp.wrap.css({
                    top: _window.scrollTop(),
                    position: 'absolute'
                });
            }
            if (mfp.st.fixedBgPos === false || (mfp.st.fixedBgPos === 'auto' && !mfp.fixedContentPos)) {
                mfp.bgOverlay.css({
                    height: _document.height(),
                    position: 'absolute'
                });
            }



            if (mfp.st.enableEscapeKey) {
                // Close on ESC key
                _document.on('keyup' + EVENT_NS, function(e) {
                    if (e.keyCode === 27) {
                        mfp.close();
                    }
                });
            }

            _window.on('resize' + EVENT_NS, function() {
                mfp.updateSize();
            });


            if (!mfp.st.closeOnContentClick) {
                _wrapClasses += ' mfp-auto-cursor';
            }

            if (_wrapClasses)
                mfp.wrap.addClass(_wrapClasses);


            // this triggers recalculation of layout, so we get it once to not to trigger twice
            var windowHeight = mfp.wH = _window.height();
            var windowStyles = {};
            var classesToadd = mfp.st.mainClass;
            if (mfp.isIE7) {
                classesToadd += ' mfp-ie7';
            }
            if (classesToadd) {
                mfp._addClassToMFP(classesToadd);
            }

            // add content
            mfp.updateItemHTML();

            _mfpTrigger('BuildControls');


            // remove scrollbar, add padding e.t.c
            $('html').css(windowStyles);

            // add everything to DOM
            mfp.bgOverlay.add(mfp.wrap).prependTo(document.body);



            // Save last focused element
            mfp._lastFocusedEl = document.activeElement;

            // Wait for next cycle to allow CSS transition
            setTimeout(function() {

                if (mfp.content) {
                    mfp._addClassToMFP(READY_CLASS);
                    _setFocus();
                } else {
                    // if content is not defined (not loaded e.t.c) we add class only for BG
                    mfp.bgOverlay.addClass(READY_CLASS);
                }

                // Trap the focus in popup
                _document.on('focusin' + EVENT_NS, function(e) {
                    if (e.target !== mfp.wrap[0] && !$.contains(mfp.wrap[0], e.target)) {
                        _setFocus();
                        return false;
                    }
                });

            }, 16);

            mfp.isOpen = true;
            mfp.updateSize(windowHeight);
            _mfpTrigger(OPEN_EVENT);
        },
        close: function() {
            if (!mfp.isOpen) return;
            _mfpTrigger(BEFORE_CLOSE_EVENT);

            mfp.isOpen = false;
            // for CSS3 animation
            if (mfp.st.removalDelay && !mfp.isLowIE && mfp.supportsTransition) {
                mfp._addClassToMFP(REMOVING_CLASS);
                setTimeout(function() {
                    mfp._close();
                }, mfp.st.removalDelay);
            } else {
                mfp._close();
            }
        },


        _close: function() {
            _mfpTrigger(CLOSE_EVENT);

            var classesToRemove = REMOVING_CLASS + ' ' + READY_CLASS + ' ';

            mfp.bgOverlay.detach();
            mfp.wrap.detach();
            mfp.container.empty();

            if (mfp.st.mainClass) {
                classesToRemove += mfp.st.mainClass + ' ';
            }

            mfp._removeClassFromMFP(classesToRemove);

            if (mfp.fixedContentPos) {
                var windowStyles = {
                    paddingRight: ''
                };
                if (mfp.isIE7) {
                    $('body, html').css('overflow', '');
                } else {
                    windowStyles.overflow = '';
                }
                $('html').css(windowStyles);
            }

            _document.off('keyup' + EVENT_NS + ' focusin' + EVENT_NS);
            mfp.ev.off(EVENT_NS);

            // clean up DOM elements that aren't removed
            mfp.wrap.attr('class', 'mfp-wrap').removeAttr('style');
            mfp.bgOverlay.attr('class', 'mfp-bg');
            mfp.container.attr('class', 'mfp-container');

            // remove close button from target element
            if (mfp.st.showCloseBtn &&
                (!mfp.st.closeBtnInside || mfp.currTemplate[mfp.currItem.type] === true)) {
                if (mfp.currTemplate.closeBtn)
                    mfp.currTemplate.closeBtn.detach();
            }


            if (mfp._lastFocusedEl) {
                $(mfp._lastFocusedEl).trigger('focus'); // put tab focus back
            }
            mfp.currItem = null;
            mfp.content = null;
            mfp.currTemplate = null;
            mfp.prevHeight = 0;

            _mfpTrigger(AFTER_CLOSE_EVENT);
        },

        updateSize: function(winHeight) {

            if (mfp.isIOS) {
                // fixes iOS nav bars https://github.com/dimsemenov/Magnific-Popup/issues/2
                var zoomLevel = document.documentElement.clientWidth / window.innerWidth;
                var height = window.innerHeight * zoomLevel;
                mfp.wrap.css('height', height);
                mfp.wH = height;
            } else {
                mfp.wH = winHeight || _window.height();
            }
            // Fixes #84: popup incorrectly positioned with position:relative on body
            if (!mfp.fixedContentPos) {
                mfp.wrap.css('height', mfp.wH);
            }

            _mfpTrigger('Resize');

        },

        /**
         * Set content of popup based on current index
         */
        updateItemHTML: function() {
            var item = mfp.items[mfp.index];

            // Detach and perform modifications
            mfp.contentContainer.detach();

            if (mfp.content)
                mfp.content.detach();

            if (!item.parsed) {
                item = mfp.parseEl(mfp.index);
            }

            var type = item.type;

            _mfpTrigger('BeforeChange', [mfp.currItem ? mfp.currItem.type : '', type]);
            // BeforeChange event works like so:
            mfp.currItem = item
            if (!mfp.currTemplate[type]) {
                var markup = mfp.st[type] ? mfp.st[type].markup : false;

                // allows to modify markup
                _mfpTrigger('FirstMarkupParse', markup);

                if (markup) {
                    mfp.currTemplate[type] = $(markup);
                } else {
                    // if there is no markup found we just define that template is parsed
                    mfp.currTemplate[type] = true;
                }
            }

            if (_prevContentType && _prevContentType !== item.type) {
                mfp.container.removeClass('mfp-' + _prevContentType + '-holder');
            }

            var newContent = mfp['get' + type.charAt(0).toUpperCase() + type.slice(1)](item, mfp.currTemplate[type]);
            mfp.appendContent(newContent, type);

            item.preloaded = true;

            _mfpTrigger(CHANGE_EVENT, item);
            _prevContentType = item.type;

            // Append container back after its content changed
            mfp.container.prepend(mfp.contentContainer);

            _mfpTrigger('AfterChange');
        },


        /**
         * Set HTML content of popup
         */
        appendContent: function(newContent, type) {
            mfp.content = newContent;

            if (newContent) {
                if (mfp.st.showCloseBtn && mfp.st.closeBtnInside &&
                    mfp.currTemplate[type] === true) {
                    // if there is no markup, we just append close button element inside
                    if (!mfp.content.find('.mfp-close').length) {
                        mfp.content.append(_getCloseBtn());
                    }
                } else {
                    mfp.content = newContent;
                }
            } else {
                mfp.content = '';
            }

            _mfpTrigger(BEFORE_APPEND_EVENT);
            mfp.container.addClass('mfp-' + type + '-holder');

            mfp.contentContainer.append(mfp.content);
        },




        /**
         * Creates Magnific Popup data object based on given data
         * @param  {int} index Index of item to parse
         */
        parseEl: function(index) {
            var item = mfp.items[index],
                type = item.type;

            if (item.tagName) {
                item = {
                    el: $(item)
                };
            } else {
                item = {
                    data: item,
                    src: item.src
                };
            }

            if (item.el) {
                var types = mfp.types;

                // check for 'mfp-TYPE' class
                for (var i = 0; i < types.length; i++) {
                    if (item.el.hasClass('mfp-' + types[i])) {
                        type = types[i];
                        break;
                    }
                }

                item.src = item.el.attr('data-mfp-src');
                if (!item.src) {
                    item.src = item.el.attr('href');
                }
            }

            item.type = type || mfp.st.type || 'inline';
            item.index = index;
            item.parsed = true;
            mfp.items[index] = item;
            _mfpTrigger('ElementParse', item);

            return mfp.items[index];
        },


        /**
         * Initializes single popup or a group of popups
         */
        addGroup: function(el, options) {
            var eHandler = function(e) {
                e.mfpEl = this;
                mfp._openClick(e, el, options);
            };

            if (!options) {
                options = {};
            }

            var eName = 'click.magnificPopup';
            options.mainEl = el;

            if (options.items) {
                options.isObj = true;
                el.off(eName).on(eName, eHandler);
            } else {
                options.isObj = false;
                if (options.delegate) {
                    el.off(eName).on(eName, options.delegate, eHandler);
                } else {
                    options.items = el;
                    el.off(eName).on(eName, eHandler);
                }
            }
        },
        _openClick: function(e, el, options) {
            var midClick = options.midClick !== undefined ? options.midClick : $.magnificPopup.defaults.midClick;


            if (!midClick && (e.which === 2 || e.ctrlKey || e.metaKey)) {
                return;
            }

            var disableOn = options.disableOn !== undefined ? options.disableOn : $.magnificPopup.defaults.disableOn;

            if (disableOn) {
                if ($.isFunction(disableOn)) {
                    if (!disableOn.call(mfp)) {
                        return true;
                    }
                } else { // else it's number
                    if (_window.width() < disableOn) {
                        return true;
                    }
                }
            }

            if (e.type) {
                e.preventDefault();

                // This will prevent popup from closing if element is inside and popup is already opened
                if (mfp.isOpen) {
                    e.stopPropagation();
                }
            }


            options.el = $(e.mfpEl);
            if (options.delegate) {
                options.items = el.find(options.delegate);
            }
            mfp.open(options);
        },


        /**
         * Updates text on preloader
         */
        updateStatus: function(status, text) {

            if (mfp.preloader) {
                if (_prevStatus !== status) {
                    mfp.container.removeClass('mfp-s-' + _prevStatus);
                }

                if (!text && status === 'loading') {
                    text = mfp.st.tLoading;
                }

                var data = {
                    status: status,
                    text: text
                };
                // allows to modify status
                _mfpTrigger('UpdateStatus', data);

                status = data.status;
                text = data.text;

                mfp.preloader.html(text);

                mfp.preloader.find('a').on('click', function(e) {
                    e.stopImmediatePropagation();
                });

                mfp.container.addClass('mfp-s-' + status);
                _prevStatus = status;
            }
        },


        /*
		"Private" helpers that aren't private at all
	 */
        _addClassToMFP: function(cName) {
            mfp.bgOverlay.addClass(cName);
            mfp.wrap.addClass(cName);
        },
        _removeClassFromMFP: function(cName) {
            this.bgOverlay.removeClass(cName);
            mfp.wrap.removeClass(cName);
        },
        _hasScrollBar: function(winHeight) {
            return ((mfp.isIE7 ? _document.height() : document.body.scrollHeight) > (winHeight || _window.height()));
        },
        _parseMarkup: function(template, values, item) {
            var arr;
            if (item.data) {
                values = $.extend(item.data, values);
            }
            _mfpTrigger(MARKUP_PARSE_EVENT, [template, values, item]);

            $.each(values, function(key, value) {
                if (value === undefined || value === false) {
                    return true;
                }
                arr = key.split('_');
                if (arr.length > 1) {
                    var el = template.find(EVENT_NS + '-' + arr[0]);

                    if (el.length > 0) {
                        var attr = arr[1];
                        if (attr === 'replaceWith') {
                            if (el[0] !== value[0]) {
                                el.replaceWith(value);
                            }
                        } else if (attr === 'img') {
                            if (el.is('img')) {
                                el.attr('src', value);
                            } else {
                                el.replaceWith('<img src="' + value + '" class="' + el.attr('class') + '" />');
                            }
                        } else {
                            el.attr(arr[1], value);
                        }
                    }

                } else {
                    template.find(EVENT_NS + '-' + key).html(value);
                }
            });
        },

        _getScrollbarSize: function() {
            // thx David
            if (mfp.scrollbarSize === undefined) {
                var scrollDiv = document.createElement("div");
                scrollDiv.id = "mfp-sbm";
                scrollDiv.style.cssText = 'width: 99px; height: 99px; overflow: scroll; position: absolute; top: -9999px;';
                document.body.appendChild(scrollDiv);
                mfp.scrollbarSize = scrollDiv.offsetWidth - scrollDiv.clientWidth;
                document.body.removeChild(scrollDiv);
            }
            return mfp.scrollbarSize;
        }

    };




    /**
     * Public static functions
     */
    $.magnificPopup = {
        instance: null,
        proto: MagnificPopup.prototype,
        modules: [],

        open: function(options, index) {
            _checkInstance();

            if (!options)
                options = {};

            options.isObj = true;
            options.index = index || 0;
            return this.instance.open(options);
        },

        close: function() {
            return $.magnificPopup.instance.close();
        },

        registerModule: function(name, module) {
            if (module.options) {
                $.magnificPopup.defaults[name] = module.options;
            }
            $.extend(this.proto, module.proto);
            this.modules.push(name);
        },

        defaults: {

            // Info about options is in docs:
            // http://dimsemenov.com/plugins/magnific-popup/documentation.html#options

            disableOn: 0,

            key: null,

            midClick: true,

            mainClass: '',

            preloader: true,

            focus: '', // CSS selector of input to focus after popup is opened

            closeOnContentClick: false,

            closeOnBgClick: true,

            closeBtnInside: false,

            showCloseBtn: true,

            enableEscapeKey: true,

            modal: false,

            alignTop: false,

            removalDelay: 300,

            fixedContentPos: 'auto',

            fixedBgPos: 'auto',

            overflowY: 'hidden',

            closeMarkup: '<button title="%title%" type="button" class="mfp-close">&times;</button>',

            tClose: 'Close (Esc)',

            tLoading: 'Loading...',

            callbacks: {
                beforeOpen: function() {
                    $('html').css('overflow-y', 'hidden');
                    this.st.image.markup = this.st.image.markup.replace('mfp-figure', 'mfp-figure mfp-with-anim');
                    this.st.mainClass = this.st.el.attr('data-effect');

                },
                afterClose: function() {
                    $('html').css('overflow-y', '');
                }
            }

        }
    };



    $.fn.magnificPopup = function(options) {
        _checkInstance();

        var jqEl = $(this);

        // We call some API method of first param is a string
        if (typeof options === "string") {

            if (options === 'open') {
                var items,
                    itemOpts = _isJQ ? jqEl.data('magnificPopup') : jqEl[0].magnificPopup,
                    index = parseInt(arguments[1], 10) || 0;

                if (itemOpts.items) {
                    items = itemOpts.items[index];
                } else {
                    items = jqEl;
                    if (itemOpts.delegate) {
                        items = items.find(itemOpts.delegate);
                    }
                    items = items.eq(index);
                }
                mfp._openClick({
                    mfpEl: items
                }, jqEl, itemOpts);
            } else {
                if (mfp.isOpen)
                    mfp[options].apply(mfp, Array.prototype.slice.call(arguments, 1));
            }

        } else {

            /*
             * As Zepto doesn't support .data() method for objects
             * and it works only in normal browsers
             * we assign "options" object directly to the DOM element. FTW!
             */
            if (_isJQ) {
                jqEl.data('magnificPopup', options);
            } else {
                jqEl[0].magnificPopup = options;
            }

            mfp.addGroup(jqEl, options);

        }
        return jqEl;
    };
    var INLINE_NS = 'inline',
        _hiddenClass,
        _inlinePlaceholder,
        _lastInlineElement,
        _putInlineElementsBack = function() {
            if (_lastInlineElement) {
                _inlinePlaceholder.after(_lastInlineElement.addClass(_hiddenClass)).detach();
                _lastInlineElement = null;
            }
        };

    $.magnificPopup.registerModule(INLINE_NS, {
        options: {
            hiddenClass: 'hide', // will be appended with `mfp-` prefix
            markup: '',
            tNotFound: 'Content not found'
        },
        proto: {

            initInline: function() {
                mfp.types.push(INLINE_NS);

                _mfpOn(CLOSE_EVENT + '.' + INLINE_NS, function() {
                    _putInlineElementsBack();
                });
            },

            getInline: function(item, template) {

                _putInlineElementsBack();

                if (item.src) {
                    var inlineSt = mfp.st.inline,
                        el = $(item.src);

                    if (el.length) {

                        // If target element has parent - we replace it with placeholder and put it back after popup is closed
                        var parent = el[0].parentNode;
                        if (parent && parent.tagName) {
                            if (!_inlinePlaceholder) {
                                _hiddenClass = inlineSt.hiddenClass;
                                _inlinePlaceholder = _getEl(_hiddenClass);
                                _hiddenClass = 'mfp-' + _hiddenClass;
                            }
                            // replace target inline element with placeholder
                            _lastInlineElement = el.after(_inlinePlaceholder).detach().removeClass(_hiddenClass);
                        }

                        mfp.updateStatus('ready');
                    } else {
                        mfp.updateStatus('error', inlineSt.tNotFound);
                        el = $('<div>');
                    }

                    item.inlineElement = el;
                    return el;
                }

                mfp.updateStatus('ready');
                mfp._parseMarkup(template, {}, item);
                return template;
            }
        }
    });

    /*>>inline*/

    /*>>ajax*/
    var AJAX_NS = 'ajax',
        _ajaxCur,
        _removeAjaxCursor = function() {
            if (_ajaxCur) {
                _body.removeClass(_ajaxCur);
            }
        };

    $.magnificPopup.registerModule(AJAX_NS, {

        options: {
            settings: null,
            cursor: 'mfp-ajax-cur',
            tError: '<a href="%url%">The content</a> could not be loaded.'
        },

        proto: {
            initAjax: function() {
                mfp.types.push(AJAX_NS);
                _ajaxCur = mfp.st.ajax.cursor;

                _mfpOn(CLOSE_EVENT + '.' + AJAX_NS, function() {
                    _removeAjaxCursor();
                    if (mfp.req) {
                        mfp.req.abort();
                    }
                });
            },

            getAjax: function(item) {

                if (_ajaxCur)
                    _body.addClass(_ajaxCur);

                mfp.updateStatus('loading');

                var opts = $.extend({
                    url: item.src,
                    success: function(data, textStatus, jqXHR) {
                        var temp = {
                            data: data,
                            xhr: jqXHR
                        };

                        _mfpTrigger('ParseAjax', temp);

                        mfp.appendContent($(temp.data), AJAX_NS);

                        item.finished = true;

                        _removeAjaxCursor();

                        _setFocus();

                        setTimeout(function() {
                            mfp.wrap.addClass(READY_CLASS);
                        }, 16);

                        mfp.updateStatus('ready');

                        _mfpTrigger('AjaxContentAdded');
                    },
                    error: function() {
                        _removeAjaxCursor();
                        item.finished = item.loadError = true;
                        mfp.updateStatus('error', mfp.st.ajax.tError.replace('%url%', item.src));
                    }
                }, mfp.st.ajax.settings);

                mfp.req = $.ajax(opts);

                return '';
            }
        }
    });







    /*>>ajax*/

    /*>>image*/
    var _imgInterval,
        _getTitle = function(item) {
            if (item.data && item.data.title !== undefined)
                return item.data.title;

            var src = mfp.st.image.titleSrc;

            if (src) {
                if ($.isFunction(src)) {
                    return src.call(mfp, item);
                } else if (item.el) {
                    return item.el.attr(src) || '';
                }
            }
            return '';
        };

    $.magnificPopup.registerModule('image', {

        options: {
            markup: '<div class="mfp-figure">' +
                '<div class="mfp-close"></div>' +
                '<div class="mfp-img"></div>' +
                '<div class="mfp-bottom-bar">' +
                '<div class="mfp-title"></div>' +
                '<div class="mfp-counter"></div>' +
                '</div>' +
                '</div>',
            cursor: 'mfp-zoom-out-cur',
            titleSrc: 'title',
            verticalFit: true,
            tError: '<a href="%url%">The image</a> could not be loaded.'
        },

        proto: {
            initImage: function() {
                var imgSt = mfp.st.image,
                    ns = '.image';

                mfp.types.push('image');

                _mfpOn(OPEN_EVENT + ns, function() {
                    if (mfp.currItem.type === 'image' && imgSt.cursor) {
                        _body.addClass(imgSt.cursor);
                    }
                });

                _mfpOn(CLOSE_EVENT + ns, function() {
                    if (imgSt.cursor) {
                        _body.removeClass(imgSt.cursor);
                    }
                    _window.off('resize' + EVENT_NS);
                });

                _mfpOn('Resize' + ns, mfp.resizeImage);
                if (mfp.isLowIE) {
                    _mfpOn('AfterChange', mfp.resizeImage);
                }
            },
            resizeImage: function() {
                var item = mfp.currItem;
                if (!item.img) return;

                if (mfp.st.image.verticalFit) {
                    var decr = 0;
                    // fix box-sizing in ie7/8
                    if (mfp.isLowIE) {
                        decr = parseInt(item.img.css('padding-top'), 10) + parseInt(item.img.css('padding-bottom'), 10);
                    }
                    item.img.css('max-height', mfp.wH - decr);
                }
            },
            _onImageHasSize: function(item) {
                if (item.img) {

                    item.hasSize = true;

                    if (_imgInterval) {
                        clearInterval(_imgInterval);
                    }

                    item.isCheckingImgSize = false;

                    _mfpTrigger('ImageHasSize', item);

                    if (item.imgHidden) {
                        if (mfp.content)
                            mfp.content.removeClass('mfp-loading');

                        item.imgHidden = false;
                    }

                }
            },

            /**
             * Function that loops until the image has size to display elements that rely on it asap
             */
            findImageSize: function(item) {

                var counter = 0,
                    img = item.img[0],
                    mfpSetInterval = function(delay) {

                        if (_imgInterval) {
                            clearInterval(_imgInterval);
                        }
                        // decelerating interval that checks for size of an image
                        _imgInterval = setInterval(function() {
                            if (img.naturalWidth > 0) {
                                mfp._onImageHasSize(item);
                                return;
                            }

                            if (counter > 200) {
                                clearInterval(_imgInterval);
                            }

                            counter++;
                            if (counter === 3) {
                                mfpSetInterval(10);
                            } else if (counter === 40) {
                                mfpSetInterval(50);
                            } else if (counter === 100) {
                                mfpSetInterval(500);
                            }
                        }, delay);
                    };

                mfpSetInterval(1);
            },

            getImage: function(item, template) {

                var guard = 0,

                    // image load complete handler
                    onLoadComplete = function() {
                        if (item) {
                            if (item.img[0].complete) {
                                item.img.off('.mfploader');

                                if (item === mfp.currItem) {
                                    mfp._onImageHasSize(item);

                                    mfp.updateStatus('ready');
                                }

                                item.hasSize = true;
                                item.loaded = true;

                                _mfpTrigger('ImageLoadComplete');

                            } else {
                                // if image complete check fails 200 times (20 sec), we assume that there was an error.
                                guard++;
                                if (guard < 200) {
                                    setTimeout(onLoadComplete, 100);
                                } else {
                                    onLoadError();
                                }
                            }
                        }
                    },

                    // image error handler
                    onLoadError = function() {
                        if (item) {
                            item.img.off('.mfploader');
                            if (item === mfp.currItem) {
                                mfp._onImageHasSize(item);
                                mfp.updateStatus('error', imgSt.tError.replace('%url%', item.src));
                            }

                            item.hasSize = true;
                            item.loaded = true;
                            item.loadError = true;
                        }
                    },
                    imgSt = mfp.st.image;


                var el = template.find('.mfp-img');
                if (el.length) {
                    var img = new Image();
                    img.className = 'mfp-img';
                    item.img = $(img).on('load.mfploader', onLoadComplete).on('error.mfploader', onLoadError);
                    img.src = item.src;

                    // without clone() "error" event is not firing when IMG is replaced by new IMG
                    // TODO: find a way to avoid such cloning
                    if (el.is('img')) {
                        item.img = item.img.clone();
                    }
                    if (item.img[0].naturalWidth > 0) {
                        item.hasSize = true;
                    }
                }

                mfp._parseMarkup(template, {
                    title: _getTitle(item),
                    img_replaceWith: item.img
                }, item);

                mfp.resizeImage();

                if (item.hasSize) {
                    if (_imgInterval) clearInterval(_imgInterval);

                    if (item.loadError) {
                        template.addClass('mfp-loading');
                        mfp.updateStatus('error', imgSt.tError.replace('%url%', item.src));
                    } else {
                        template.removeClass('mfp-loading');
                        mfp.updateStatus('ready');
                    }
                    return template;
                }

                mfp.updateStatus('loading');
                item.loading = true;

                if (!item.hasSize) {
                    item.imgHidden = true;
                    template.addClass('mfp-loading');
                    mfp.findImageSize(item);
                }

                return template;
            }
        }
    });



    /*>>image*/

    /*>>zoom*/
    var hasMozTransform,
        getHasMozTransform = function() {
            if (hasMozTransform === undefined) {
                hasMozTransform = document.createElement('p').style.MozTransform !== undefined;
            }
            return hasMozTransform;
        };

    $.magnificPopup.registerModule('zoom', {

        options: {
            enabled: false,
            easing: 'ease-in-out',
            duration: 300,
            opener: function(element) {
                return element.is('img') ? element : element.find('img');
            }
        },

        proto: {

            initZoom: function() {
                var zoomSt = mfp.st.zoom,
                    ns = '.zoom';

                if (!zoomSt.enabled || !mfp.supportsTransition) {
                    return;
                }

                var duration = zoomSt.duration,
                    getElToAnimate = function(image) {
                        var newImg = image.clone().removeAttr('style').removeAttr('class').addClass('mfp-animated-image'),
                            transition = 'all ' + (zoomSt.duration / 1000) + 's ' + zoomSt.easing,
                            cssObj = {
                                position: 'fixed',
                                zIndex: 9999,
                                left: 0,
                                top: 0,
                                '-webkit-backface-visibility': 'hidden'
                            },
                            t = 'transition';

                        cssObj['-webkit-' + t] = cssObj['-moz-' + t] = cssObj['-o-' + t] = cssObj[t] = transition;

                        newImg.css(cssObj);
                        return newImg;
                    },
                    showMainContent = function() {
                        mfp.content.css('visibility', 'visible');
                    },
                    openTimeout,
                    animatedImg;

                _mfpOn('BuildControls' + ns, function() {
                    if (mfp._allowZoom()) {

                        clearTimeout(openTimeout);
                        mfp.content.css('visibility', 'hidden');

                        // Basically, all code below does is clones existing image, puts in on top of the current one and animated it

                        image = mfp._getItemToZoom();

                        if (!image) {
                            showMainContent();
                            return;
                        }

                        animatedImg = getElToAnimate(image);

                        animatedImg.css(mfp._getOffset());

                        mfp.wrap.append(animatedImg);

                        openTimeout = setTimeout(function() {
                            animatedImg.css(mfp._getOffset(true));
                            openTimeout = setTimeout(function() {

                                showMainContent();

                                setTimeout(function() {
                                    animatedImg.remove();
                                    image = animatedImg = null;
                                    _mfpTrigger('ZoomAnimationEnded');
                                }, 16); // avoid blink when switching images

                            }, duration); // this timeout equals animation duration

                        }, 16); // by adding this timeout we avoid short glitch at the beginning of animation


                        // Lots of timeouts...
                    }
                });
                _mfpOn(BEFORE_CLOSE_EVENT + ns, function() {
                    if (mfp._allowZoom()) {

                        clearTimeout(openTimeout);

                        mfp.st.removalDelay = duration;

                        if (!image) {
                            image = mfp._getItemToZoom();
                            if (!image) {
                                return;
                            }
                            animatedImg = getElToAnimate(image);
                        }


                        animatedImg.css(mfp._getOffset(true));
                        mfp.wrap.append(animatedImg);
                        mfp.content.css('visibility', 'hidden');

                        setTimeout(function() {
                            animatedImg.css(mfp._getOffset());
                        }, 16);
                    }

                });

                _mfpOn(CLOSE_EVENT + ns, function() {
                    if (mfp._allowZoom()) {
                        showMainContent();
                        if (animatedImg) {
                            animatedImg.remove();
                        }
                    }
                });
            },

            _allowZoom: function() {
                return mfp.currItem.type === 'image';
            },

            _getItemToZoom: function() {
                if (mfp.currItem.hasSize) {
                    return mfp.currItem.img;
                } else {
                    return false;
                }
            },

            // Get element postion relative to viewport
            _getOffset: function(isLarge) {
                var el;
                if (isLarge) {
                    el = mfp.currItem.img;
                } else {
                    el = mfp.st.zoom.opener(mfp.currItem.el || mfp.currItem);
                }

                var offset = el.offset();
                var paddingTop = parseInt(el.css('padding-top'), 10);
                var paddingBottom = parseInt(el.css('padding-bottom'), 10);
                offset.top -= ($(window).scrollTop() - paddingTop);


                /*

			Animating left + top + width/height looks glitchy in Firefox, but perfect in Chrome. And vice-versa.

			 */
                var obj = {
                    width: el.width(),
                    // fix Zepto height+padding issue
                    height: (_isJQ ? el.innerHeight() : el[0].offsetHeight) - paddingBottom - paddingTop
                };

                // I hate to do this, but there is no another option
                if (getHasMozTransform()) {
                    obj['-moz-transform'] = obj['transform'] = 'translate(' + offset.left + 'px,' + offset.top + 'px)';
                } else {
                    obj.left = offset.left;
                    obj.top = offset.top;
                }
                return obj;
            }

        }
    });



    /*>>zoom*/

    /*>>iframe*/

    var IFRAME_NS = 'iframe',
        _emptyPage = '//about:blank',

        _fixIframeBugs = function(isShowing) {
            if (mfp.currTemplate[IFRAME_NS]) {
                var el = mfp.currTemplate[IFRAME_NS].find('iframe');
                if (el.length) {
                    // reset src after the popup is closed to avoid "video keeps playing after popup is closed" bug
                    if (!isShowing) {
                        el[0].src = _emptyPage;
                    }

                    // IE8 black screen bug fix
                    if (mfp.isIE8) {
                        el.css('display', isShowing ? 'block' : 'none');
                    }
                }
            }
        };

    $.magnificPopup.registerModule(IFRAME_NS, {

        options: {
            markup: '<div class="mfp-iframe-scaler">' +
                '<div class="mfp-close"></div>' +
                '<iframe class="mfp-iframe" src="//about:blank" frameborder="0" allowfullscreen></iframe>' +
                '</div>',

            srcAction: 'iframe_src',

            // we don't care and support only one default type of URL by default
            patterns: {
                youtube: {
                    index: 'youtube.com',
                    id: 'v=',
                    src: '//www.youtube.com/embed/%id%?autoplay=1'
                },
                vimeo: {
                    index: 'vimeo.com/',
                    id: '/',
                    src: '//player.vimeo.com/video/%id%?autoplay=1'
                },
                gmaps: {
                    index: '//maps.google.',
                    src: '%id%&output=embed'
                }
            }
        },

        proto: {
            initIframe: function() {
                mfp.types.push(IFRAME_NS);

                _mfpOn('BeforeChange', function(e, prevType, newType) {
                    if (prevType !== newType) {
                        if (prevType === IFRAME_NS) {
                            _fixIframeBugs(); // iframe if removed
                        } else if (newType === IFRAME_NS) {
                            _fixIframeBugs(true); // iframe is showing
                        }
                    }
                });

                _mfpOn(CLOSE_EVENT + '.' + IFRAME_NS, function() {
                    _fixIframeBugs();
                });
            },

            getIframe: function(item, template) {
                var embedSrc = item.src;
                var iframeSt = mfp.st.iframe;

                $.each(iframeSt.patterns, function() {
                    if (embedSrc.indexOf(this.index) > -1) {
                        if (this.id) {
                            if (typeof this.id === 'string') {
                                embedSrc = embedSrc.substr(embedSrc.lastIndexOf(this.id) + this.id.length, embedSrc.length);
                            } else {
                                embedSrc = this.id.call(this, embedSrc);
                            }
                        }
                        embedSrc = this.src.replace('%id%', embedSrc);
                        return false; // break;
                    }
                });

                var dataObj = {};
                if (iframeSt.srcAction) {
                    dataObj[iframeSt.srcAction] = embedSrc;
                }
                mfp._parseMarkup(template, dataObj, item);

                mfp.updateStatus('ready');

                return template;
            }
        }
    });



    /*>>iframe*/

    /*>>gallery*/
    /**
     * Get looped index depending on number of slides
     */
    var _getLoopedId = function(index) {
            var numSlides = mfp.items.length;
            if (index > numSlides - 1) {
                return index - numSlides;
            } else if (index < 0) {
                return numSlides + index;
            }
            return index;
        },
        _replaceCurrTotal = function(text, curr, total) {
            return text.replace('%curr%', curr + 1).replace('%total%', total);
        };

    $.magnificPopup.registerModule('gallery', {

        options: {
            enabled: false,
            arrowMarkup: '<button title="%title%" type="button" class="mfp-arrow mfp-arrow-%dir%"></button>',
            preload: [0, 2],
            navigateByImgClick: true,
            arrows: true,

            tPrev: 'Previous (Left arrow key)',
            tNext: 'Next (Right arrow key)',
            tCounter: '%curr% of %total%'
        },

        proto: {
            initGallery: function() {

                var gSt = mfp.st.gallery,
                    ns = '.mfp-gallery',
                    supportsFastClick = Boolean($.fn.mfpFastClick);

                mfp.direction = true; // true - next, false - prev

                if (!gSt || !gSt.enabled) return false;

                _wrapClasses += ' mfp-gallery';

                _mfpOn(OPEN_EVENT + ns, function() {

                    if (gSt.navigateByImgClick) {
                        mfp.wrap.on('click' + ns, '.mfp-img', function() {
                            if (mfp.items.length > 1) {
                                mfp.next();
                                return false;
                            }
                        });
                    }

                    _document.on('keydown' + ns, function(e) {
                        if (e.keyCode === 37) {
                            mfp.prev();
                        } else if (e.keyCode === 39) {
                            mfp.next();
                        }
                    });
                });

                _mfpOn('UpdateStatus' + ns, function(e, data) {
                    if (data.text) {
                        data.text = _replaceCurrTotal(data.text, mfp.currItem.index, mfp.items.length);
                    }
                });

                _mfpOn(MARKUP_PARSE_EVENT + ns, function(e, element, values, item) {
                    var l = mfp.items.length;
                    values.counter = l > 1 ? _replaceCurrTotal(gSt.tCounter, item.index, l) : '';
                });

                _mfpOn('BuildControls' + ns, function() {
                    if (mfp.items.length > 1 && gSt.arrows && !mfp.arrowLeft) {
                        var markup = gSt.arrowMarkup,
                            arrowLeft = mfp.arrowLeft = $(markup.replace('%title%', gSt.tPrev).replace('%dir%', 'left')).addClass(PREVENT_CLOSE_CLASS),
                            arrowRight = mfp.arrowRight = $(markup.replace('%title%', gSt.tNext).replace('%dir%', 'right')).addClass(PREVENT_CLOSE_CLASS);

                        var eName = supportsFastClick ? 'mfpFastClick' : 'click';
                        arrowLeft[eName](function() {
                            mfp.prev();
                        });
                        arrowRight[eName](function() {
                            mfp.next();
                        });

                        // Polyfill for :before and :after (adds elements with classes mfp-a and mfp-b)
                        if (mfp.isIE7) {
                            _getEl('b', arrowLeft[0], false, true);
                            _getEl('a', arrowLeft[0], false, true);
                            _getEl('b', arrowRight[0], false, true);
                            _getEl('a', arrowRight[0], false, true);
                        }

                        mfp.container.append(arrowLeft.add(arrowRight));
                    }
                });

                _mfpOn(CHANGE_EVENT + ns, function() {
                    if (mfp._preloadTimeout) clearTimeout(mfp._preloadTimeout);

                    mfp._preloadTimeout = setTimeout(function() {
                        mfp.preloadNearbyImages();
                        mfp._preloadTimeout = null;
                    }, 16);
                });


                _mfpOn(CLOSE_EVENT + ns, function() {
                    _document.off(ns);
                    mfp.wrap.off('click' + ns);

                    if (mfp.arrowLeft && supportsFastClick) {
                        mfp.arrowLeft.add(mfp.arrowRight).destroyMfpFastClick();
                    }
                    mfp.arrowRight = mfp.arrowLeft = null;
                });

            },
            next: function() {
                mfp.direction = true;
                mfp.index = _getLoopedId(mfp.index + 1);
                mfp.updateItemHTML();
            },
            prev: function() {
                mfp.direction = false;
                mfp.index = _getLoopedId(mfp.index - 1);
                mfp.updateItemHTML();
            },
            goTo: function(newIndex) {
                mfp.direction = (newIndex >= mfp.index);
                mfp.index = newIndex;
                mfp.updateItemHTML();
            },
            preloadNearbyImages: function() {
                var p = mfp.st.gallery.preload,
                    preloadBefore = Math.min(p[0], mfp.items.length),
                    preloadAfter = Math.min(p[1], mfp.items.length),
                    i;

                for (i = 1; i <= (mfp.direction ? preloadAfter : preloadBefore); i++) {
                    mfp._preloadItem(mfp.index + i);
                }
                for (i = 1; i <= (mfp.direction ? preloadBefore : preloadAfter); i++) {
                    mfp._preloadItem(mfp.index - i);
                }
            },
            _preloadItem: function(index) {
                index = _getLoopedId(index);

                if (mfp.items[index].preloaded) {
                    return;
                }

                var item = mfp.items[index];
                if (!item.parsed) {
                    item = mfp.parseEl(index);
                }

                _mfpTrigger('LazyLoad', item);

                if (item.type === 'image') {
                    item.img = $('<img class="mfp-img" />').on('load.mfploader', function() {
                        item.hasSize = true;
                    }).on('error.mfploader', function() {
                        item.hasSize = true;
                        item.loadError = true;
                    }).attr('src', item.src);
                }


                item.preloaded = true;
            }
        }
    });
    var RETINA_NS = 'retina';

    $.magnificPopup.registerModule(RETINA_NS, {
        options: {
            replaceSrc: function(item) {
                return item.src.replace(/\.\w+$/, function(m) {
                    return '@2x' + m;
                });
            },
            ratio: 1 // Function or number.  Set to 1 to disable.
        },
        proto: {
            initRetina: function() {
                if (window.devicePixelRatio > 1) {

                    var st = mfp.st.retina,
                        ratio = st.ratio;

                    ratio = !isNaN(ratio) ? ratio : ratio();

                    if (ratio > 1) {
                        _mfpOn('ImageHasSize' + '.' + RETINA_NS, function(e, item) {
                            item.img.css({
                                'max-width': item.img[0].naturalWidth / ratio,
                                'width': '100%'
                            });
                        });
                        _mfpOn('ElementParse' + '.' + RETINA_NS, function(e, item) {
                            item.src = st.replaceSrc(item, ratio);
                        });
                    }
                }

            }
        }
    });
    (function() {
        var ghostClickDelay = 1000,
            supportsTouch = 'ontouchstart' in window,
            unbindTouchMove = function() {
                _window.off('touchmove' + ns + ' touchend' + ns);
            },
            eName = 'mfpFastClick',
            ns = '.' + eName;


        // As Zepto.js doesn't have an easy way to add custom events (like jQuery), so we implement it in this way
        $.fn.mfpFastClick = function(callback) {

            return $(this).each(function() {

                var elem = $(this),
                    lock;

                if (supportsTouch) {

                    var timeout,
                        startX,
                        startY,
                        pointerMoved,
                        point,
                        numPointers;

                    elem.on('touchstart' + ns, function(e) {
                        pointerMoved = false;
                        numPointers = 1;

                        point = e.originalEvent ? e.originalEvent.touches[0] : e.touches[0];
                        startX = point.clientX;
                        startY = point.clientY;

                        _window.on('touchmove' + ns, function(e) {
                            point = e.originalEvent ? e.originalEvent.touches : e.touches;
                            numPointers = point.length;
                            point = point[0];
                            if (Math.abs(point.clientX - startX) > 10 ||
                                Math.abs(point.clientY - startY) > 10) {
                                pointerMoved = true;
                                unbindTouchMove();
                            }
                        }).on('touchend' + ns, function(e) {
                            unbindTouchMove();
                            if (pointerMoved || numPointers > 1) {
                                return;
                            }
                            lock = true;
                            e.preventDefault();
                            clearTimeout(timeout);
                            timeout = setTimeout(function() {
                                lock = false;
                            }, ghostClickDelay);
                            callback();
                        });
                    });

                }

                elem.on('click' + ns, function() {
                    if (!lock) {
                        callback();
                    }
                });
            });
        };

        $.fn.destroyMfpFastClick = function() {
            $(this).off('touchstart' + ns + ' click' + ns);
            if (supportsTouch) _window.off('touchmove' + ns + ' touchend' + ns);
        };
    })();
})(window.jQuery || window.Zepto);
},{}],11:[function(require,module,exports){
'use strict';
var sideMenu={
	SetMenuSidebar : function() {
		try {
			$('#apd-main-btn').on('click', function () {
				var cookieId = $(this).closest(".APPSBDWP").data("cookie-id");
				$(this).removeClass("on-pre-mini")
				$(this).toggleClass('mini-menu');
				$('#apd-sidebar').toggleClass('active');
				if ($('#apd-sidebar').hasClass("active")) {
					$('#apd-sidebar .app-tooltip').tooltip('enable');
					APPSBDAPPJS.core.SetCookie(cookieId + "_sel_menu", 1, 30, '/');
				} else {
					$('#apd-sidebar .app-tooltip').tooltip('disable');
					APPSBDAPPJS.core.SetCookie(cookieId + "_sel_menu", 0, 30, '/');
				}
				setTimeout(function () {
					try {
						$(window).trigger("resize");
					} catch (e) {
					}
				}, 500);
			});
		}catch(e){}
	}
}
jQuery(document).ready(function ($) {
		if (!$('#apd-sidebar').hasClass("active")) {
			setTimeout(function() {
				$('#apd-sidebar .app-tooltip').tooltip('disable');
			},500);
		}

	if($(window).width() < 576 && $('#apd-main-btn').hasClass("mini-menu") && $('#apd-main-btn').hasClass("on-pre-mini")){
		$('#apd-sidebar').removeClass("active");
		$('#apd-main-btn').removeClass("mini-menu").removeClass("on-pre-mini");
		setTimeout(function(){
			$(window).trigger('resize');
		},1000)
	}
});
module.exports=sideMenu;
},{}],12:[function(require,module,exports){
var notification= {
    ShowGritterMsg: function (msg, IsSuccess, IsSticky, title, icon, timeouttime) {
        try {
            if (typeof (IsSuccess) == 'undefined') {
                IsSuccess = true;
            }

            if (typeof (IsSticky) == 'undefined') {
                IsSticky = false;
            }
            if (typeof (title) == 'undefined' || !title) {
                title = "Notification";
            }
            if (typeof (icon) == 'undefined') {
                icon = "";
            }
            if (typeof (timeouttime) == 'undefined') {
                timeouttime = 5000;
            }
            if(!msg){
                msg="Message doesn't set";
            }
            try {
                var options = {
                    title: title,
                    style: IsSuccess ? 'success' : 'error',
                    theme: 'right-bottom.css',
                    timeout: timeouttime,
                    message: msg,
                    icon: icon
                };
                if (IsSticky) {
                    options.timeout = null;
                }
                var n = new notify(options);
                n.show();
            } catch (e) {
                console.log(e);
                try {
                    $.gritter.add({
                        position: 'bottom-left',
                        text: msg,
                        image: IsSuccess ? base_url + 'images/statusOk.png' : base_url + 'images/statuserror.png',
                        sticky: IsSticky,
                        time: 5000
                    });
                }catch (e) {
                    alert(e.message);
                }
            }
        } catch (e) {
            console.log(e);
        }
    },
    ShowWaitinglight: function (isShow,callback,msg) {
        if (typeof (isShow) == "undefined") {
            isShow = true;
        }
        if (typeof (callback) == "undefined") {
            callback = function(){}
        }
        var defaultData=$(".lightboxWraper #waiting h4").data("default-msg");
        if (typeof (msg) != "undefined") {
            $(".lightboxWraper #waiting h4").text(msg);
        }else{
            if(defaultData) {
                $(".lightboxWraper #waiting h4").text(defaultData);
            }
        }
        if (isShow) {
           
            $(".lightboxWraper").fadeIn('fast',callback);
        } else {
            $(".lightboxWraper").fadeOut('fast',callback);
        }
    },
    ShowAppLoader: function (isShow,callback,msg) {
        if (typeof (isShow) == "undefined") {
            isShow = true;
        }
        if (typeof (callback) == "undefined") {
            callback = function(){}
        }
        var defaultData=$("#apbd-app-loader #apbd-app-waiting h4").data("default-msg");
        if (typeof (msg) != "undefined") {
            $("#apbd-app-loader #apbd-app-waiting h4").text(msg);
        }else{
            if(defaultData) {
                $("#apbd-app-loader #apbd-app-waiting h4").text(defaultData);
            }
        }
        if (isShow) {            
            $("#apbd-app-loader").fadeIn('fast',callback);
        } else {
            $("#apbd-app-loader").fadeOut('fast',callback);
        }
    },
    ShowSwal:function(rdata){
        var isNew=false;
        try {
            if (typeof (Swal) !='undefined' && typeof (Swal.fire) == "function") {
                isNew=true;
            }
        }catch(ex){ }
        var okText = "";
        if (typeof (appGlobalLang.okText) != "undefined") {
            try {
                okText = appGlobalLang.okText;

            }catch (e){
                okText="Ok";
            }
        } else {
            okText="Ok";
        }
        var cData={
            title: "",
            text:  rdata.msg,
            html:  rdata.msg,
            type: (rdata.status ? "success" : "error"),
            icon:"",
            showCancelButton: false,
            confirmButtonClass: "btn-success",
            confirmButtonText:okText,
            closeOnConfirm: false,
            closeOnCancel: false
        };
        cData.icon=cData.type;
        try {
            if (isNew) {
                Swal.fire(cData);
            } else {
                swal(cData);
            }
        }catch(e){
            try{
                notification.ShowGritterMsg(rdata.msg, rdata.status, rdata.is_sticky, rdata.title, rdata.icon);
            }catch(e){}
        }
    },
    ShowResponseSwal:function(rdata){
        notification.ShowSwal(rdata);
    },
    ConfirmAlert:function (msg,callBack,showLoaderOnConfirm){
        if(typeof (showLoaderOnConfirm)=="undefined"){
            showLoaderOnConfirm=false;
        }
        if(typeof (callBack)=="undefined"){
            callBack=function(){};
        }
        try {
            var yesText = "";
            var noText = "";
            if (typeof (appGlobalLang.yesText) != "undefined") {
                try {
                    yesText = appGlobalLang.yesText;
                    noText = appGlobalLang.noText;
                } catch (e) {
                    yesText = "Yes";
                    noText = "No";
                }
            } else {
                yesText = "Yes";
                noText = "No";
            }

            var isNew=false;
            try {
                if (typeof (Swal) !='undefined' && typeof (Swal.fire) == "function") {
                    isNew=true;
                } else if (typeof (swal) == "function") {
                    targetMethod = swal;
                }
            }catch(ex){
                if (typeof (swal) == "function") {
                    targetMethod = swal;
                }
            }
            var cData={
                title: "",
                html: msg,
                text: msg,
                type: "warning",
                icon: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: yesText,
                cancelButtonText: noText,
                closeOnConfirm: !showLoaderOnConfirm,
                closeOnCancel: true,
                showLoaderOnConfirm: showLoaderOnConfirm,
                showDenyButton: !showLoaderOnConfirm
            };
            if(isNew) {
                Swal.fire(cData).then(function(result){
                    if(!result.isConfirmed){
                        try {
                            Swal.close();
                        }catch (e){
                            console.log(e.message);
                        }
                    }else {
                        swal.showLoading();
                        callBack(result.isConfirmed);
                    }
                });
            }else {
                swal(cData, function (isConfirm) {
                    callBack(isConfirm);
                });
            }
        }catch (ex){
            console.log(ex.message);
            var r = confirm(msg);
            callBack(r);
        }
    },
    Alert:function(msg,alert_type){
        if(typeof (alert_type)=="undefined"){
            alert_type="error";
        }
        var targetMethod=null;
        try {
            if (typeof (Swal) !='undefined' && typeof (Swal.fire) == "function") {
                targetMethod = Swal.fire;
            } else if (typeof (swal) == "function") {
                targetMethod = swal;
            }
        }catch(ex){
            if (typeof (swal) == "function") {
                targetMethod = swal;
            }
        }
        if (typeof (targetMethod) == "function") {
            targetMethod({
                title: "",
                text:  msg,
                type: alert_type,
                icon: alert_type,
                showCancelButton: false,
                confirmButtonClass: "btn-success",
                confirmButtonText: appGlobalLang.okText,
                closeOnConfirm: false,
                closeOnCancel: false
            });
        } else {
            alert(msg);
        }
    },
    Snackbar:function(msg,stay_time){
        if (typeof (stay_time) == "undefined") {
            stay_time=2000;
        }

        var snackbar=jQuery('<div class="apbd-snackbar"></div>');
        snackbar.html(msg);
        snackbar.appendTo('body');
        snackbar.addClass("apbd-show");
        if(stay_time>0) {
            setTimeout(function () {
                snackbar.fadeOut('fast',function(){
                    snackbar.remove();
                })
            }, stay_time);
        }else{
            snackbar.on("click",function(e){
                e.preventDefault();
                e.stopPropagation();
                snackbar.fadeOut('fast',function(){
                    snackbar.remove();
                })
            });
        }
    }
}
module.exports=notification;



},{}],13:[function(require,module,exports){
jQuery(document).ready(function ($) {
    try{
        $(".apbd-app-multi-select").select2();
    }catch (e) {}

    $(".apbd-pro-btn").on("click",function (e) {
        e.preventDefault();
        $('#apbd_pro_modal').modal('show');
    });
    $('#apbd_pro_modal').on('show.bs.modal', function (e) {
        var datain=$('#apbd_pro_modal').data('in-anim');
        var dataout=$('#apbd_pro_modal').data('out-anim');
        $('#apbd_pro_modal .modal-dialog').removeClass(dataout).addClass(datain);
    });
    $('#apbd_pro_modal').on('hide.bs.modal', function (e) {
        var datain=$('#apbd_pro_modal').data('in-anim');
        var dataout=$('#apbd_pro_modal').data('out-anim');
        $('#apbd_pro_modal .modal-dialog').removeClass(datain).addClass(dataout);
    });
});

},{}],14:[function(require,module,exports){
var slider_input= {
    Init:function(){
        slider_input.SetSliderInput();
    },
    SetSliderInput: function () {
        try {
            $(".app-slider-input:not(.added-slider)").each(function(){
                var mainObj=$(this);
                $(this).addClass('added-slider');
                var slider = mainObj.find('>input[type=range]');
                var postFix=slider.data('unit')?slider.data('unit'):'';
                var valueFilter;
                try{
                    valueFilter=slider.data('format')?eval(slider.data('format')):function(value,postfix,type){
                        return value+postfix;
                    };
                }catch (e) {
                    valueFilter=function(value,postfix,type){
                        return value+postfix;
                    };
                }


                mainObj.append('<span class="app-slider-min-container">'+valueFilter(slider.attr('min'),postFix,'M')+'</span> ');
                mainObj.append('<span class="app-slider-max-container">'+valueFilter(slider.attr('max'),postFix,'X')+'</span> ');

                var currentValue=$('<span class="app-slider-current-container" ></span>');
                currentValue.html(valueFilter(slider.val(),postFix,'C'));
                mainObj.append(currentValue);
                slider.on("input",function(){
                    currentValue.html(valueFilter(slider.val(),postFix,'C'));
                });

            });

        } catch (e) {
            console.log(e);
        }
    }

}
module.exports=slider_input;

},{}],15:[function(require,module,exports){
var WPEditorObj= {
    Init:function() {
        $('.apd-wp-editor').each(function () {
           try {
               WPEditorObj.InitEditorControl(this.id);
           }catch (e) {
               console.log(e.message);
           }
        });
    },
    SetInsertButton:function(){
        $("body").on("click",".apbd-editor-insert-btn:not(.adt-insb)",function (e) {
            e.preventDefault();
            var text=$(this).data("text");
            if(text && text.length>0){
                tinymce.activeEditor.execCommand('mceInsertContent', false, text);
            }
        });
    },
    InitEditorControl:function(id) {
        try {
            try{ tinymce.EditorManager.execCommand('mceRemoveEditor',false, id);}catch (e) {}
            var config=wp.editor.getDefaultSettings();
            $.extend(config,{
                mediaButtons: true
            });
            $.extend(config.tinymce, {
                plugins    : 'charmap,hr,media,paste,tabfocus,textcolor,link,fullscreen,wordpress,wpeditimage,wpgallery,wpdialogs,wpview',
                resize     : 'vertical',
                menubar    : false,
                indent     : false,
                toolbar1   : 'bold,italic,strikethrough,bullist,numlist,blockquote,hr,alignleft,aligncenter,alignright,link,unlink,wp_more,spellchecker,fullscreen,wp_adv',
                toolbar2   : 'formatselect,underline,alignjustify,forecolor,pastetext,removeformat,charmap,outdent,indent,undo,redo,wp_help',
                setup: function (editor) {
                    editor.on('change', function (e) {
                        editor.save();
                    });
                    editor.on('submit', function (e) {
                        editor.save();
                    });
                }

            });
            wp.editor.initialize(id,config);

        }catch (e) {
            console.log(e.message);
        }
    }
}
module.exports=WPEditorObj;
},{}],16:[function(require,module,exports){
var WPMediaChooseObj= {
    mediaUploader:null,
    Init:function() {
        $('body').on('click','.apd-wp-image-chooser',function () {
            var targetElem = $(this).data('target');
            var inputbox = null;
            var fileType = "";
            var title = "Choose Media";
            var button = "Choose Media";
            var thisobj = $(this);
            if (targetElem) {
                inputbox = $(targetElem)
            } else {
                inputbox = $(this);
            }
            if ($(this).data("file-type")) {
                fileType = $(this).data("file-type");
                fileType= fileType.split(",");
            }
            if ($(this).data("title")) {
                title = $(this).data("title");
            }
            if ($(this).data("button")) {
                button = $(this).data("button");
            }
            WPMediaChooseObj.mediaUploader = wp.media({
                title: title,
                library: {
                    type: fileType
                },
                button: {
                    text: button
                }, multiple: false
            });
            WPMediaChooseObj.mediaUploader.on('select', function () {
                var attachment = WPMediaChooseObj.mediaUploader.state().get('selection').first().toJSON();
                try {
                    inputbox.val(attachment.url).trigger('input');
                } catch (e) {
                }
                try {
                    if(thisobj.data("on-select")){
                       var callback= eval(thisobj.data("on-select"));
                       callback(attachment);
                    }
                } catch (e) {
                    console.log(e);
                }
            });
            WPMediaChooseObj.mediaUploader.on('close', function () {
                try {
                    WPMediaChooseObj.mediaUploader.$el.closest(".supports-drag-drop").remove();
                } catch (e) {
                }

            });
            WPMediaChooseObj.mediaUploader.open();
        });

    }
}
module.exports=WPMediaChooseObj;
},{}]},{},[1]);
