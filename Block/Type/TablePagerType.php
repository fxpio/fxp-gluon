<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Component\Gluon\Block\Type;

use Sonatra\Component\Ajax\AjaxEvents;
use Sonatra\Component\Block\AbstractType;
use Sonatra\Component\Block\BlockBuilder;
use Sonatra\Component\Block\BlockInterface;
use Sonatra\Component\Block\BlockView;
use Sonatra\Component\Block\Extension\Core\Type\TextType;
use Sonatra\Component\Block\Util\BlockUtil;
use Sonatra\Component\Gluon\Event\GetAjaxTableEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

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
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * Constructor.
     *
     * @param EventDispatcherInterface $dispatcher
     * @param RequestStack             $requestStack
     * @param RouterInterface          $router
     */
    public function __construct(EventDispatcherInterface $dispatcher, RequestStack $requestStack, RouterInterface $router)
    {
        $this->request = $requestStack->getMasterRequest();
        $this->dispatcher = $dispatcher;
        $this->router = $router;
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(BlockView $view, BlockInterface $block, array $options)
    {
        $url = $this->request->getRequestUri();
        $source = $block->getParent()->getData();
        $sortOrder = array();

        if (null === $options['route']) {
            $event = new GetAjaxTableEvent($view->parent->vars['id'], $this->request, $source);
            $this->dispatcher->dispatch(AjaxEvents::INJECTION, $event);
        } else {
            $routeParams = $options['route_parameters'];
            $routeReferenceType = $options['route_reference_type'];
            $url = $this->router->generate($options['route'], $routeParams, $routeReferenceType);
        }

        foreach ($source->getSortColumns() as $def) {
            $sortOrder[] = $def['name'];
        }

        $view->vars = array_replace($view->vars, array(
            'tabindex' => $options['tab_index'],
            'source' => $source,
            'attr' => array_replace($view->vars['attr'], array(
                'data-table-pager' => 'true',
                'data-table-id' => $view->parent->vars['id'],
                'data-locale' => $source->getLocale(),
                'data-page-size' => $source->getPageSize(),
                'data-page-number' => $source->getPageNumber(),
                'data-size' => $source->getSize(),
                'data-parameters' => json_encode($source->getParameters()),
                'data-ajax-id' => null === $options['route'] ? $view->parent->vars['id'] : null,
                'data-url' => $url,
                'data-multi-sortable' => $options['multi_sortable'] ? 'true' : 'false',
                'data-sort-order' => json_encode($sortOrder),
            )),
        ));

        if (null !== $options['affix_target']) {
            BlockUtil::addAttribute($view, 'data-affix-target', $options['affix_target']);
        }

        if (null !== $options['affix_min_height']) {
            BlockUtil::addAttribute($view, 'data-affix-min-height', $options['affix_min_height']);
        }

        if (null !== $options['affix_class']) {
            BlockUtil::addAttribute($view, 'data-affix-class', $options['affix_class']);
        }

        if (null !== $options['empty_type']) {
            /* @var BlockBuilder $config */
            $config = $block->getConfig();
            $factory = $config->getBlockFactory();
            $empty = $factory->create($options['empty_type'], null, $options['empty_options']);

            $view->vars['empty_block'] = $empty->createView();
        }

        foreach ($source->getColumns() as $child) {
            /* @var BlockInterface $child */
            if ($child->getOption('sortable')) {
                $view->vars['attr']['data-sortable'] = 'true';
                break;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'locale' => \Locale::getDefault(),
            'page_size' => null,
            'page_number' => null,
            'route' => null,
            'route_parameters' => array(),
            'route_reference_type' => (bool) RouterInterface::ABSOLUTE_PATH,
            'multi_sortable' => false,
            'affix_target' => null,
            'affix_min_height' => null,
            'affix_class' => null,
            'tab_index' => 0,
            'empty_type' => function (Options $options, $value) {
                if (null === $value && isset($options['empty_options']['data'])) {
                    $value = TextType::class;
                }

                return $value;
            },
            'empty_options' => array(),
        ));

        $resolver->addAllowedTypes('locale', 'string');
        $resolver->addAllowedTypes('page_size', array('null', 'int'));
        $resolver->addAllowedTypes('page_number', array('null', 'int'));
        $resolver->addAllowedTypes('route', array('null', 'string'));
        $resolver->addAllowedTypes('route_parameters', 'array');
        $resolver->addAllowedTypes('route_reference_type', 'bool');
        $resolver->addAllowedTypes('multi_sortable', 'bool');
        $resolver->setAllowedTypes('empty_type', array('null', 'string'));
        $resolver->setAllowedTypes('empty_options', 'array');

        $resolver->setNormalizer('empty_options', function (Options $options, $value) {
            if (null !== $options['empty_message'] && !isset($value['data'])) {
                $value['data'] = $options['empty_message'];
            }

            if (!array_key_exists('wrapped', $value)) {
                $value['wrapped'] = false;
            }

            return $value;
        });
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'table_pager';
    }
}
