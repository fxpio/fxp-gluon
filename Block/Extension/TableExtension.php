<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Component\Gluon\Block\Extension;

use Sonatra\Component\Block\AbstractTypeExtension;
use Sonatra\Component\Block\BlockBuilderInterface;
use Sonatra\Component\Bootstrap\Block\Type\TableType;
use Sonatra\Component\Gluon\Block\Type\TableColumnRowNumberType;
use Sonatra\Component\Gluon\Block\Type\TableColumnSelectType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;

/**
 * Table Block Extension.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class TableExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function buildBlock(BlockBuilderInterface $builder, array $options)
    {
        if ($options['row_number']) {
            $builder->add('_row_number', TableColumnRowNumberType::class);
        }

        if ($options['selectable']) {
            $builder->add('_selectable', TableColumnSelectType::class, array(
                'multiple' => $options['multi_selectable'],
                'selected' => $options['selected'],
            ));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'render_id' => true,
            'responsive' => true,
            'row_number' => true,
            'selectable' => false,
            'multi_selectable' => false,
            'selected' => false,
        ));

        $resolver->addAllowedTypes('row_number', 'bool');
        $resolver->addAllowedTypes('selectable', 'bool');
        $resolver->addAllowedTypes('multi_selectable', 'bool');
        $resolver->addAllowedTypes('selected', 'bool');

        $resolver->setNormalizer('selectable', function (Options $options, $value) {
            if ($options['multi_selectable']) {
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
