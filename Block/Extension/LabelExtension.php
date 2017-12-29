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
use Fxp\Component\Bootstrap\Block\Type\LabelType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Label Block Extension.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
class LabelExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->addAllowedValues('style', ['accent']);
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return LabelType::class;
    }
}
