<?php

/*
 * This file is part of the Fxp package.
 *
 * (c) François Pluchino <francois.pluchino@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fxp\Component\Gluon\Block\Type;

use Fxp\Component\Block\AbstractType;
use Fxp\Component\Block\BlockBuilderInterface;
use Fxp\Component\Block\BlockInterface;
use Fxp\Component\Block\BlockView;
use Fxp\Component\Bootstrap\Block\Type\LinkType;
use Fxp\Component\Gluon\Block\DataTransformer\LookupTransformer;
use Fxp\Component\RoutingExtra\Routing\RouterExtraInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
class LookupType extends AbstractType
{
    /**
     * @var RouterExtraInterface
     */
    private $routerExtra;

    /**
     * @var PropertyAccessorInterface
     */
    private $propertyAccessor;

    /**
     * Constructor.
     *
     * @param RouterExtraInterface      $routerExtra      The rooter extra
     * @param PropertyAccessorInterface $propertyAccessor The property accessor
     */
    public function __construct(RouterExtraInterface $routerExtra, PropertyAccessorInterface $propertyAccessor = null)
    {
        $this->routerExtra = $routerExtra;
        $this->propertyAccessor = $propertyAccessor ?: PropertyAccess::createPropertyAccessor();
    }

    /**
     * {@inheritdoc}
     */
    public function buildBlock(BlockBuilderInterface $builder, array $options)
    {
        $builder->addViewTransformer(new LookupTransformer($options['view_property_path'], $this->propertyAccessor));
        $builder->setAttribute('route_parameters', $options['route_parameters']);
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(BlockView $view, BlockInterface $block, array $options)
    {
        if (null === $block->getData()) {
            return;
        }

        $view->vars = array_replace($view->vars, array(
            'attr' => array_merge($view->vars['attr'], array(
                'href' => $this->routerExtra->generate($options['route_name'],
                    $block->getAttribute('route_parameters'),
                    $block->getData(), RouterInterface::ABSOLUTE_URL),
            )),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'view_property_path' => function (Options $options) {
                return $options['property_path'];
            },
            'route_name' => null,
            'route_parameters' => array(),
        ));

        $resolver->setRequired('route_name');
        $resolver->setRequired('route_parameters');

        $resolver->addAllowedTypes('route_name', 'string');
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return LinkType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'lookup';
    }
}
