function getQueryStringValue (key) {
	return decodeURIComponent(window.location.search.replace(new RegExp("^(?:.*[&\\?]" + encodeURIComponent(key).replace(/[\.\+\*]/g, "\\$&") + "(?:\\=([^&]*))?)?.*$", "i"), "$1"));
}

// borrowed from https://gist.github.com/niyazpk/f8ac616f181f6042d1e0
function updateUrlParameter (uri, key, value) {
	// remove the hash part before operating on the uri
	var
		i = uri.indexOf('#'),
		hash = i === -1 ? '' : uri.substr(i)
		;

	uri = i === -1 ? uri : uri.substr(0, i);

	var
		re = new RegExp("([?&])" + key + "=.*?(&|$)", "i"),
		separator = uri.indexOf('?') !== -1 ? "&" : "?"
		;

	if (!value) {
		// remove key-value pair if value is empty
		uri = uri.replace(new RegExp("([?&]?)" + key + "=[^&]*", "i"), '');

		if (uri.slice(-1) === '?') {
			uri = uri.slice(0, -1);
		}
		// replace first occurrence of & by ? if no ? is present

		if (uri.indexOf('?') === -1) {
			uri = uri.replace(/&/, '?');
		}

	} else if (uri.match(re)) {
		uri = uri.replace(re, '$1' + key + "=" + value + '$2');
	} else {
		uri = uri + separator + key + "=" + value;
	}
	return uri + hash;
}

var
	lang = getQueryStringValue('lang') || 'en',
	stretching = getQueryStringValue('stretching') || 'auto'
;




$(document).ready(function () {
	mejs.i18n.language(lang);
    var attrstring = " ";
	/*if (youtube.attributes_string === undefined || youtube.attributes_string === null) {

	} else {
		attrstring = youtube.attributes_string
	}
	*/
	$('video, audio').mediaelementplayer({
		stretching: stretching,
		pluginPath: 'mediaelement/',
		//youtube: {attrstring},
		success: function (media) {
			$(media).closest('.media-wrapper').children('div:first').attr('lang', mejs.i18n.language());

			var renderer = $('#' + media.id + '-rendername');

			media.addEventListener('loadedmetadata', function (e) {
				var src = media.originalNode.getAttribute('src').replace('&amp;', '&');
				if (src !== null && src !== undefined) {
					renderer.find('.src').html('<a href="' + src + '" target="_blank">' + src + '</a>')
					.end()
					.find('.renderer').html(media.rendererName)
					.end()
					.find('.error').html('')
					;
				}
			}, false);
			
			media.play();

			
			
			media.addEventListener('error', function (e) {
				renderer.find('.error').html('<strong>Error</strong>: ' + e.message);
			}, false);
		}
	});
});