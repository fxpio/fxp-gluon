<?php

/*
 * This file is part of the Fxp package.
 *
 * (c) François Pluchino <francois.pluchino@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fxp\Component\Gluon\Event;

use Fxp\Component\Ajax\Event\GetAjaxEvent;
use Fxp\Component\Bootstrap\Block\DataSource\DataSourceInterface;
use Fxp\Component\Gluon\Block\Helper\AjaxDataSourceHelper;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
class GetAjaxTableEvent extends GetAjaxEvent
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var DataSourceInterface
     */
    protected $source;

    /**
     * Constructor.
     *
     * @param string              $id
     * @param Request             $request
     * @param DataSourceInterface $source
     * @param string              $format
     */
    public function __construct($id, Request $request, DataSourceInterface $source, $format = 'json')
    {
        parent::__construct($id, $format);

        $this->request = $request;
        $this->source = $source;
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return AjaxDataSourceHelper::getData($this->request, $this->source, $this->getId().'_');
    }
}
