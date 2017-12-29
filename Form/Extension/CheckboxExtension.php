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
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Checkbox Form Extension.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
class CheckboxExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars = array_replace($view->vars, [
            'style' => $options['style'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'style' => null,
        ]);

        $resolver->setAllowedTypes('style', ['null', 'string']);

        $resolver->setAllowedValues('style', [null, 'default', 'primary', 'accent', 'success', 'info', 'warning', 'danger', 'link']);
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return CheckboxType::class;
    }
}
