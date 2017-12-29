<?php

/*
 * This file is part of the Fxp package.
 *
 * (c) François Pluchino <francois.pluchino@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fxp\Component\Gluon\Block\Extension;

use Fxp\Component\Block\AbstractTypeExtension;
use Fxp\Component\Block\BlockInterface;
use Fxp\Component\Block\BlockView;
use Fxp\Component\Bootstrap\Block\Type\TableType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Footable Block Extension.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
class FootableExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function buildView(BlockView $view, BlockInterface $block, array $options)
    {
        if ($options['footable']['enabled']) {
            $fOptions = array(
                'breakpoints' => array(
                    'phone' => $options['footable']['breakpoints_phone'],
                    'tablet' => $options['footable']['breakpoints_tablet'],
                ),
                'striped' => array(
                    'enabled' => $options['striped'],
                ),
            );

            if (null !== $options['footable']['delay']) {
                $fOptions['delay'] = $options['footable']['delay'];
            }

            if (null !== $options['footable']['add_row_toggle']) {
                $fOptions['addRowToggle'] = $options['footable']['add_row_toggle'];
            }

            $view->vars = array_replace($view->vars, array(
                'footable_options' => $fOptions,
            ));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'footable' => array(),
        ));

        $resolver->addAllowedTypes('footable', 'array');

        $resolver->setNormalizer('footable', function (Options $options, $value) {
            $footableResolver = new OptionsResolver();

            $footableResolver->setDefaults(array(
                'enabled' => true,
                'delay' => null,
                'breakpoints_phone' => 767,
                'breakpoints_tablet' => 991,
                'add_row_toggle' => null,
            ));

            $footableResolver->setAllowedTypes('enabled', 'bool');
            $footableResolver->setAllowedTypes('delay', array('null', 'int'));
            $footableResolver->setAllowedTypes('breakpoints_phone', 'int');
            $footableResolver->setAllowedTypes('breakpoints_tablet', 'int');
            $footableResolver->setAllowedTypes('add_row_toggle', array('null', 'bool'));

            return $footableResolver->resolve($value);
        });
        $resolver->setNormalizer('render_id', function (Options $options, $value) {
            if ($options['footable']['enabled']) {
                return true;
            }

            return $value;
        });
        $resolver->setNormalizer('responsive', function (Options $options, $value) {
            if ($options['footable']['enabled']) {
                return true;
            }

            return $value;
        });
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return TableType::class;
    }
}
