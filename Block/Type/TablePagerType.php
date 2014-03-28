<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\GluonBundle\Block\Type;

use Sonatra\Bundle\BlockBundle\Block\AbstractType;
use Sonatra\Bundle\BlockBundle\Block\BlockView;
use Sonatra\Bundle\BlockBundle\Block\BlockInterface;
use Sonatra\Bundle\AjaxBundle\AjaxEvents;
use Sonatra\Bundle\GluonBundle\Event\GetAjaxTableEvent;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Table Pager Block Type.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class TablePagerType extends AbstractType
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var EventDispatcher
     */
    private $dispatcher;

    /**
     * Constructor.
     *
     * @param Request $request
     */
    public function __construct(ContainerInterface $container)
    {
        $this->request = $container->get('request');
        $this->dispatcher = $container->get('event_dispatcher');
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(BlockView $view, BlockInterface $block, array $options)
    {
        $source = $block->getParent()->getData();
        $event = new GetAjaxTableEvent($view->parent->vars['id'], $this->request, $source);
        $this->dispatcher->dispatch(AjaxEvents::INJECTION, $event);
        $sortOrder = array();

        foreach ($source->getSortColumns() as $i => $def) {
            $sortOrder[] = $def['name'];
        }

        $view->vars = array_replace($view->vars, array(
            'source' => $source,
            'attr'   => array_replace($view->vars['attr'], array(
                'data-table-pager'    => 'true',
                'data-locale'         => $source->getLocale(),
                'data-page-size'      => $source->getPageSize(),
                'data-page-number'    => $source->getPageNumber(),
                'data-size'           => $source->getSize(),
                'data-parameters'     => json_encode($source->getParameters()),
                'data-ajax-id'        => $view->parent->vars['id'],
                'data-url'            => $options['url'],
                'data-multi-sortable' => $options['multi_sortable'] ? 'true' : 'false',
                'data-sort-order'     => json_encode($sortOrder),
            )),
        ));

        foreach ($source->getColumns() as $name => $child) {
            if ($child->getConfig()->getOption('sortable')) {
                $view->vars['attr']['data-sortable'] = 'true';
                break;
            }
        }

        if (array_key_exists('data-table-select', $view->parent->vars['attr'])
                && 'true' === $view->parent->vars['attr']['data-table-select']) {
            $view->vars['attr']['data-table-id'] = $view->parent->vars['id'];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'locale'         => \Locale::getDefault(),
            'page_size'      => null,
            'page_number'    => null,
            'url'            => $this->request->getRequestUri(),
            'multi_sortable' => false,
        ));

        $resolver->addAllowedTypes(array(
            'locale'         => 'string',
            'page_size'      => array('null', 'int'),
            'page_number'    => array('null', 'int'),
            'url'            => 'string',
            'multi_sortable' => 'bool',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'table_pager';
    }
}
