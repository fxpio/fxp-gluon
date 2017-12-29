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
use Fxp\Component\Block\Extension\Core\Type\TwigType;
use Fxp\Component\Bootstrap\Block\Type\TableColumnType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Table Column Link Block Type.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
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
        $resolver->setDefaults([
            'link_options' => [],
            'route_name' => null,
            'route_options' => [],
            'route_absolute' => false,
            'formatter' => TwigType::class,
        ]);

        $resolver->addAllowedTypes('link_options', 'array');
        $resolver->addAllowedTypes('route_name', ['null', 'string']);
        $resolver->addAllowedTypes('route_options', 'array');
        $resolver->addAllowedTypes('route_absolute', 'bool');

        $resolver->setNormalizer('formatter_options', function (Options $options, $value) {
            $variables = isset($value['variables']) ? $value['variables'] : [];
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
