/*
 * QtWebKit-powered headless test runner using PhantomJS
 *
 * PhantomJS binaries: http://phantomjs.org/download.html
 * Requires PhantomJS 1.6+ (1.7+ recommended)
 *
 * Run with:
 *   phantomjs runner.js [url-of-your-qunit-testsuite]
 *
 * e.g.
 *   phantomjs runner.js http://localhost/qunit/test/index.html
 */

/*jshint latedef:false */
/*global phantom:false, require:false, console:false, window:false, QUnit:false */

(function() {
	'use strict';

	var args = require('system').args,
		timeoutRef,
		timeLimit = 60000;

	// arg[0]: scriptName, args[1...]: arguments
	if (args.length !== 2) {
		console.error('Usage:\n  phantomjs runner.js [url-of-your-qunit-testsuite]');
		phantom.exit(1);
	}

	var url = args[1],
		page = require('webpage').create();

	// Route `console.log()` calls from within the Page context to the main Phantom context (i.e. current `this`)
	page.onConsoleMessage = function(msg) {
		console.log(msg);
	};

	page.onInitialized = function() {
		page.evaluate(addLogging);
		// Start a timeout counter. If this is exceeded the phantom will exit.
		timeoutRef = setTimeout(function(){
			console.log('')
			console.error('Test Run Failed. Timeout Exceeded. Took longer than '+ timeLimit / 1000 +' seconds.')
			console.log('')
			phantom.exit(1)
		}, timeLimit)
	};

	page.onCallback = function(message) {
		var result,
			failed;

		if (message) {
			if (message.name === 'QUnit.done') {
				result = message.data;
				failed = !result || result.failed;

				clearTimeout(timeoutRef) // Get rid of this timeout so it doesn't mess up other phantom+qunit runs (could it?)
				phantom.exit(failed ? 1 : 0);
			}
		}
	};

	page.open(url, function(status) {
		if (status !== 'success') {
			console.error('Unable to access network: ' + status);
			phantom.exit(1);
		} else {
			// Cannot do this verification with the 'DOMContentLoaded' handler because it
			// will be too late to attach it if a page does not have any script tags.
			var qunitMissing = page.evaluate(function() { return (typeof QUnit === 'undefined' || !QUnit); });
			if (qunitMissing) {
				console.error('The `QUnit` object is not present on this page.');
				phantom.exit(1);
			}
			// Do nothing... the callback mechanism will handle everything!
		}
	});

	function addLogging() {
		window.document.addEventListener('DOMContentLoaded', function() {
			var current_test_assertions = [];

			QUnit.log(function(details) {
				var response;

				// Ignore passing assertions
				if (details.result) {
					return;
				}

				response = details.message || '';

				if (typeof details.expected !== 'undefined') {
					if (response) {
						response += ', ';
					}

					response += 'expected: ' + details.expected + ', but was: ' + details.actual;
					if (details.source) {
						response += "\n" + details.source;
					}
				}

				current_test_assertions.push('Failed assertion: ' + response);
			});

			QUnit.testDone(function(result) {
				var i,
					len,
					name = result.module ? result.module + ': ' + result.name : result.name;
				if (result.failed) {
					console.log('')
					console.error('-' + result.failed + ' of ' + result.total + ' failed: "' + name + '"');

					for (i = 0, len = current_test_assertions.length; i < len; i++) {
						console.log('    ' + current_test_assertions[i]);
					}
					console.log('')
				} else if (result.passed) {
					console.log('+' + result.passed + " passed")
				}

				current_test_assertions.length = 0;
			});

			QUnit.done(function(r) {
				if (r.total === 0) {
					// QUnit can run and call done without doing any tests. This means QUnit is loaded but the unit tests aren't loaded yet.
					console.log('PhantomJS & QUnit loaded, no tests ready yet.');
				} else {
					if (r.failed) {
						console.log('');
						console.log(r.failed + ' Assertions Failed');
					}
					console.log('Took ' + r.runtime +  'ms to run ' + r.total + ' assertions. ' + r.passed + ' passed, ' + r.failed + ' failed.');
					console.log('');
					if (typeof window.callPhantom === 'function') {
						window.callPhantom({
							'name': 'QUnit.done',
							'data': r
						});
					}
				}
			});
		}, false);
	}
})();