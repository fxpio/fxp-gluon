<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Component\Gluon\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

/**
 * Toggle Form Type.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class ToggleType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return CheckboxType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'toggle';
    }
}
