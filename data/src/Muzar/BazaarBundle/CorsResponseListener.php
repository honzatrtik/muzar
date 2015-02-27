<?php
/**
 * Date: 20/02/15
 * Time: 17:14
 */

namespace Muzar\BazaarBundle;

use Symfony\Component\HttpKernel\Event\FilterResponseEvent;


class CorsResponseListener
{
	public function onKernelResponse(FilterResponseEvent $event)
	{
		$responseHeaders = $event->getResponse()->headers;

		$responseHeaders->set('Access-Control-Allow-Headers', 'X-Requested-With, origin, content-type, accept');
		$responseHeaders->set('Access-Control-Allow-Origin', '*');
		$responseHeaders->set('Access-Control-Allow-Methods', 'POST, GET, PUT, DELETE, OPTIONS');
	}
}
