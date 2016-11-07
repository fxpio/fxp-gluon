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
use Sonatra\Component\Bootstrap\Block\Type\LabelType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Label Block Extension.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class LabelExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->addAllowedValues('style', array('accent'));
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return LabelType::class;
    }
}
