/**
 * Pluginever Framework - v1.0.0 - 2018-01-11 | http://pluginever.com | Copyright (c) 2018; | Licensed GPLv2+
 */

window.Pluginever_Framework=function(window,document,$,undefined){"use strict";var app={};return app.init=function(){$(".select2").length>0&&$(".select2").select2(),$(".plvr-tooltip").tooltipster({maxWidth:300}),$(".conditional").conditionize(),setTimeout(function(){$(".plvr-lazy-loading").removeClass("loading").addClass("loaded")},1e3)},$(document).ready(app.init),app}(window,document,jQuery);