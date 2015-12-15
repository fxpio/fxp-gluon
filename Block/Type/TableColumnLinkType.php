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
use Sonatra\Bundle\BlockBundle\Block\Extension\Core\Type\TwigType;
use Sonatra\Bundle\BootstrapBundle\Block\Type\TableColumnType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;

/**
 * Table Column Link Block Type.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class TableColumnLinkType extends AbstractType
{
    /**
     * @var string
     */
    protected $resource;

    /**
     * Constructor.
     *
     * @param string $resource
     */
    public function __construct($resource)
    {
        $this->resource = $resource;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'attribute' => null,
            'link_options' => array(),
            'route_name' => null,
            'route_options' => array(),
            'route_absolute' => false,
            'formatter' => TwigType::class,
        ));

        $resolver->addAllowedTypes('attribute', array('null', 'string'));
        $resolver->addAllowedTypes('link_options', 'array');
        $resolver->addAllowedTypes('route_name', array('null', 'string'));
        $resolver->addAllowedTypes('route_options', 'array');
        $resolver->addAllowedTypes('route_absolute', 'bool');

        $resolver->setNormalizer('attribute', function (Options $options, $value) {
            if (null === $value) {
                $value = $options['index'];
            }

            return $value;
        });

        $resolver->setNormalizer('formatter_options', function (Options $options, $value) {
            $variables = isset($value['variables']) ? $value['variables'] : array();
            $variables['link_options'] = $options['link_options'];
            $variables['route_name'] = $options['route_name'];
            $variables['route_options'] = $options['route_options'];
            $variables['route_absolute'] = $options['route_absolute'];

            $value['variables'] = $variables;
            $value['resource'] = $this->resource;
            $value['resource_block'] = 'table_column_link_content';
            $value['empty_data'] = $options['empty_data'];
            $value['empty_message'] = $options['empty_message'];

            return $value;
        });
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return TableColumnType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'table_column_link';
    }
}
