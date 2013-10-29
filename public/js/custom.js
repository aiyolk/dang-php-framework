/*
 * blockUi默认配制
 * http://www.malsup.com/jquery/block/#options
 */
// override these in your code to change the default behavior and style
$.blockUI.defaults = {
    // message displayed when blocking (use null for no message)
    message:  '<h1>Please wait...</h1>',

    title: null,        // title string; only used when theme == true
    draggable: true,    // only used when theme == true (requires jquery-ui.js to be loaded)

    theme: false, // set to true to use with jQuery UI themes

    // styles for the message when blocking; if you wish to disable
    // these and use an external stylesheet then do this in your code:
    // $.blockUI.defaults.css = {};
    css: {
        padding:        10,
        margin:         0,
        width:          '30%',
        top:            '40%',
        left:           '35%',
        textAlign:      'center',
        color:          '#000',
        border:         '3px solid #aaa',
        backgroundColor:'#fff',
        cursor:         'wait'
    },

    // minimal style set used when themes are used
    themedCSS: {
        width:  '30%',
        top:    '40%',
        left:   '35%'
    },

    // styles for the overlay
    overlayCSS:  {
        backgroundColor: '#000',
        opacity:         0.6,
        cursor:          'wait'
    },

    // style to replace wait cursor before unblocking to correct issue
    // of lingering wait cursor
    cursorReset: 'default',

    // styles applied when using $.growlUI
    growlCSS: {
        width:    '350px',
        top:      '10px',
        left:     '',
        right:    '10px',
        border:   'none',
        padding:  '5px',
        opacity:   0.6,
        cursor:    null,
        color:    '#fff',
        backgroundColor: '#000',
        '-webkit-border-radius': '10px',
        '-moz-border-radius':    '10px'
    },

    // IE issues: 'about:blank' fails on HTTPS and javascript:false is s-l-o-w
    // (hat tip to Jorge H. N. de Vasconcelos)
    iframeSrc: /^https/i.test(window.location.href || '') ? 'javascript:false' : 'about:blank',

    // force usage of iframe in non-IE browsers (handy for blocking applets)
    forceIframe: false,

    // z-index for the blocking overlay
    baseZ: 1000,

    // set these to true to have the message automatically centered
    centerX: true, // <-- only effects element blocking (page block controlled via css above)
    centerY: true,

    // allow body element to be stetched in ie6; this makes blocking look better
    // on "short" pages.  disable if you wish to prevent changes to the body height
    allowBodyStretch: true,

    // enable if you want key and mouse events to be disabled for content that is blocked
    bindEvents: true,

    // be default blockUI will supress tab navigation from leaving blocking content
    // (if bindEvents is true)
    constrainTabKey: true,

    // fadeIn time in millis; set to 0 to disable fadeIn on block
    fadeIn:  200,

    // fadeOut time in millis; set to 0 to disable fadeOut on unblock
    fadeOut:  400,

    // time in millis to wait before auto-unblocking; set to 0 to disable auto-unblock
    timeout: 0,

    // disable if you don't want to show the overlay
    showOverlay: true,

    // if true, focus will be placed in the first available input field when
    // page blocking
    focusInput: true,

    // suppresses the use of overlay styles on FF/Linux (due to performance issues with opacity)
    // no longer needed in 2012
    // applyPlatformOpacityRules: true,

    // callback method invoked when fadeIn has completed and blocking message is visible
    onBlock: null,

    // callback method invoked when unblocking has completed; the callback is
    // passed the element that has been unblocked (which is the window object for page
    // blocks) and the options that were passed to the unblock call:
    //   onUnblock(element, options)
    onUnblock: null,

    // don't ask; if you really must know: http://groups.google.com/group/jquery-en/browse_thread/thread/36640a8730503595/2f6a79a77a78e493#2f6a79a77a78e493
    quirksmodeOffsetHack: 4,

    // class name of the message block
    blockMsgClass: 'blockMsg',

    // if it is already blocked, then ignore it (don't unblock and reblock)
    ignoreIfBlocked: false
};

/*
 * 提交表单
 */
var bindFormToSubmit = function (formId, successUrl) {
    $('#' + formId).submit(function() {
        // inside event callbacks 'this' is the DOM element so we first
        // wrap it in a jQuery object and then invoke ajaxSubmit
        $(this).ajaxSubmit({
            type:"POST",
            dataType : "text",
            buttons:$('#' + formId).find("button[name='button']"),
            async : true,
            cache: false,
            success:function(responseText, statusText)
            {
                var json = jQuery.parseJSON(responseText);

                //提交失败，输出失败信息
                errorCode = json.errorCode
                if(errorCode != "0"){
                    $.blockUI({
                        message: '<h3>' + json.message + '</h3>',
                        fadeIn: 0, //设置数字后，会感觉有延迟，不流畅
                        fadeOut: 0,
                        timeout: 2000
                    });
                    //点击页面的任何位置，即刻关闭
                    $('body').click(function() {
                        $.unblockUI({
                            fadeOut: 0 //设置数字后，会感觉有延迟，不流畅
                        });
                    });
                    return false;
                }

                //表单提交成功后，做下面的事情
                //设置successUrl默认值
                successUrl = typeof successUrl !=='undefined'? successUrl : false;
                //不重定向，直接给出成功信息
                if(successUrl == false){
                    bootbox.dialog({
                        message: json.message
                    });
                //给出成功信息并重定向
                }else{
                    bootbox.dialog({
                        title: json.message,
                        message: "正在转向，请稍候..."
                    });
                    setTimeout(function() {
                        $.unblockUI;
                        location.href = successUrl;
                    }, 2000);
                }

                return true;
            }
        });

        // !!! Important !!!
        // always return false to prevent standard browser submit and page navigation
        return false;
    });

    return true;
}

$(document).ready(function(){
    //ajax遮罩层loading
    $(document).ajaxStart(function() {
        $("body").showLoading();
    }).ajaxSuccess(function() {
        $("body").hideLoading();
    }).ajaxError(function(a, b, e) {
        throw e;
    });

});