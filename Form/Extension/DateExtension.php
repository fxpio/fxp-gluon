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

use Fxp\Component\Block\Util\BlockUtil;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Date Form Extension.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
class DateExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if (\in_array($options['widget'], ['text', 'choice'])) {
            BlockUtil::addAttributeClass($view, 'date-'.$options['widget'].'-wrapper');

            if ($options['text_block']) {
                BlockUtil::addAttributeClass($view, 'date-'.$options['widget'].'-block');
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        if ('text' === $options['widget']) {
            $date = new \DateTime('now - 23 years');

            BlockUtil::addAttribute($view->children['year'], 'placeholder', $date->format('Y'));
            BlockUtil::addAttribute($view->children['month'], 'placeholder', 5);
            BlockUtil::addAttribute($view->children['day'], 'placeholder', 23);

            $view->children['year']->vars['translation_domain'] = false;
            $view->children['month']->vars['translation_domain'] = false;
            $view->children['day']->vars['translation_domain'] = false;

            $view->children['year']->vars['attr'] = array_merge(
                $view->children['year']->vars['attr'],
                $options['text_attr']
            );
            $view->children['month']->vars['attr'] = array_merge(
                $view->children['month']->vars['attr'],
                $options['text_attr']
            );
            $view->children['day']->vars['attr'] = array_merge(
                $view->children['day']->vars['attr'],
                $options['text_attr']
            );
        }

        if (isset($view->vars['date_pattern'])) {
            $view->vars['date_pattern'] = str_replace('/', '<span>/</span>', $view->vars['date_pattern']);
            $view->vars['date_pattern'] = str_replace('-', '<span>-</span>', $view->vars['date_pattern']);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'text_attr' => [],
            'text_block' => true,
        ]);

        $resolver->setAllowedTypes('text_attr', 'array');
        $resolver->setAllowedTypes('text_block', 'bool');
    }

    /**
     * {@inheritdoc}
     */
    public static function getExtendedTypes()
    {
        return [DateType::class];
    }
}
