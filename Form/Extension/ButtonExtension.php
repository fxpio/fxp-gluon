<?php

/*
 * This file is part of the Fxp package.
 *
 * (c) François Pluchino <francois.pluchino@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fxp\Component\Gluon\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Button Form Extension.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
class ButtonExtension extends AbstractTypeExtension
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
        return ButtonType::class;
    }
}
