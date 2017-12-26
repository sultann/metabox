/**
 * Pluginever Framework
 * https://pluginever.com/
 *
 * Copyright (c) 2017 PluginEver
 * Licensed under the GPLv2+ license.
 */

/*jslint browser: true */
/*global jQuery:false */

window.Pluginever_Framework = (function(window, document, $, undefined){
	'use strict';

	var app = {};

	app.init = function() {
        console.log('working');
	};

	$(document).ready( app.init );

	return app;

})(window, document, jQuery);

