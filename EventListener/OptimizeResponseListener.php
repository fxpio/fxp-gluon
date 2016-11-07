<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Component\Gluon\EventListener;

use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

/**
 * OptimizeResponseListener remove all return line and spaces in html response.
 *
 * The onKernelResponse method must be connected to the kernel.response event.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class OptimizeResponseListener
{
    const ENV_ENABLED = 'dev';

    protected $env;

    /**
     * Constroctor of Class.
     *
     * @param string $env A type of environment (dev, prod, etc...)
     */
    public function __construct($env = self::ENV_ENABLED)
    {
        $this->env = (string) $env;
    }

    /**
     * Method for a dependency injection.
     *
     * @param FilterResponseEvent $event A event object
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        // do not capture redirects or modify XML HTTP Requests
        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()
                || 'html' !== $event->getRequest()->getRequestFormat()
                || self::ENV_ENABLED === $this->env) {
            return;
        }

        $response = $event->getResponse();
        $contentType = $response->headers->get('content-type');

        // only html content
        if (false === strpos($contentType, 'text/html')) {
            return;
        }

        $content = $response->getContent();
        $content = preg_replace('/(\n[\s]+)|\n/', '', $content);
        $content = preg_replace('/\s\s+/', ' ', $content);
        $response->setContent($content);
    }
}
