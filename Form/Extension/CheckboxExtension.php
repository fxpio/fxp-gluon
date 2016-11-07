<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Component\Gluon\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Checkbox Form Extension.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class CheckboxExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars = array_replace($view->vars, array(
            'style' => $options['style'],
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'style' => null,
        ));

        $resolver->setAllowedTypes('style', array('null', 'string'));

        $resolver->setAllowedValues('style', array(null, 'default', 'primary', 'accent', 'success', 'info', 'warning', 'danger', 'link'));
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return CheckboxType::class;
    }
}
