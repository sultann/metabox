/**
 * Pluginever Framework - v1.0.0 - 2017-12-26 | http://pluginever.com | Copyright (c) 2017; | Licensed GPLv2+
 */

window.Pluginever_Framework=function(window,document,$,undefined){"use strict";var app={};return app.init=function(){$(".select2").length>0&&$(".select2").select2(),$(".conditional").conditionize(),setTimeout(function(){$(".plvr-lazy-loading").removeClass("loading").addClass("loaded")},1e3)},$(document).ready(app.init),app}(window,document,jQuery);