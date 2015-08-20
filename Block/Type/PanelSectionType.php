<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\GluonBundle\Block\Type;

use Sonatra\Bundle\BlockBundle\Block\AbstractType;
use Sonatra\Bundle\BlockBundle\Block\BlockBuilderInterface;
use Sonatra\Bundle\BlockBundle\Block\BlockInterface;
use Sonatra\Bundle\BlockBundle\Block\BlockView;
use Sonatra\Bundle\BlockBundle\Block\Exception\InvalidConfigurationException;
use Sonatra\Bundle\BlockBundle\Block\Util\BlockUtil;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Panel Section Block Type.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class PanelSectionType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildBlock(BlockBuilderInterface $builder, array $options)
    {
        if (!BlockUtil::isEmpty($options['label'])) {
            $builder->add('_heading', 'heading', array(
                'size'  => 6,
                'label' => $options['label'],
            ));
        }

        if ($options['collapsible']) {
            $builder->add('_panel_section_actions', 'panel_actions', array());
            $builder->get('_panel_section_actions')->add('_button_collapse', 'button', array(
                'label'   => '',
                'attr'    => array('class' => 'btn-panel-collapse'),
                'prepend' => '<span class="caret"></span>',
            ));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addParent(BlockInterface $parent, BlockInterface $block, array $options)
    {
        if (!BlockUtil::isValidBlock('panel', $parent)) {
            $msg = 'The "panel_section" parent block (name: "%s") must be a "panel" block type';
            throw new InvalidConfigurationException(sprintf($msg, $block->getName()));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addChild(BlockInterface $child, BlockInterface $block, array $options)
    {
        if (BlockUtil::isValidBlock('heading', $child)) {
            if ($block->has('_heading')) {
                $msg = 'The panel section block "%s" has already panel section title. Removes the label option of the panel section block.';
                throw new InvalidConfigurationException(sprintf($msg, $block->getName()));
            }
        } elseif (BlockUtil::isValidBlock('panel_actions', $child)) {
            if ($block->getAttribute('already_actions')) {
                $actions = $block->get($block->getAttribute('already_actions'));

                foreach ($actions->all() as $action) {
                    $child->add($action);
                }

                $block->remove($block->getAttribute('already_actions'));
            } else {
                $block->setAttribute('already_actions', $child->getName());
            }
        } elseif (BlockUtil::isValidBlock('panel_row', $child)) {
            $cOptions = array();

            if (null !== $block->getOption('column')) {
                $cOptions['column'] = $block->getOption('column');
            }

            if (null !== $block->getOption('layout_max')) {
                $cOptions['layout_max'] = $block->getOption('layout_max');
            }

            if (null !== $block->getOption('layout_size')) {
                $cOptions['layout_size'] = $block->getOption('layout_size');
            }

            if (null !== $block->getOption('cell_label_style') && null === $child->getOption('cell_label_style')) {
                $cOptions['cell_label_style'] = $block->getOption('cell_label_style');
            }

            $child->setOptions($cOptions);
            $this->setLastRow($block, $child);
        } elseif (BlockUtil::isValidBlock('panel_cell', $child)) {
            $row = $this->getLastRow($block);
            $row->add($child);
            $block->remove($child->getName());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(BlockView $view, BlockInterface $block, array $options)
    {
        $view->vars = array_replace($view->vars, array(
            'rendered'         => $options['rendered'],
            'collapsible'      => $options['collapsible'],
            'collapsed'        => $options['collapsed'],
            'hidden_if_empty'  => $options['hidden_if_empty'],
            'column'           => $options['column'],
            'layout_max'       => $options['layout_max'],
            'layout_size'      => $options['layout_size'],
            'cell_label_style' => $options['cell_label_style'],
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function finishView(BlockView $view, BlockInterface $block, array $options)
    {
        $hasRow = 0;
        $hasRenderedRow = false;

        foreach ($view->children as $name => $child) {
            if (in_array('heading', $child->vars['block_prefixes'])) {
                $class = isset($child->vars['attr']['class']) ? $child->vars['attr']['class'] : '';
                $class .= ' panel-section-title';

                $child->vars['attr']['class'] = trim($class);

                $view->vars['panel_section_heading'] = $child;
                unset($view->children[$name]);
            } elseif (in_array('panel_actions', $child->vars['block_prefixes'])) {
                if (count($child->children) > 0 || isset($child->vars['panel_button_collapse'])) {
                    $view->vars['panel_section_actions'] = $child;
                }

                unset($view->children[$name]);
            } elseif (in_array('panel_row', $child->vars['block_prefixes'])) {
                $hasRow++;

                if (!$hasRenderedRow && $child->vars['rendered']) {
                    $hasRenderedRow = true;
                }
            }
        }

        if (!is_scalar($view->vars['value'])) {
            $view->vars['value'] = '';
        }

        if ($view->vars['hidden_if_empty'] && BlockUtil::isEmpty($view->vars['value'])
            && $hasRow === count($view->children)
            && !$hasRenderedRow) {
            $view->vars['rendered'] = false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'inherit_data'     => true,
            'rendered'         => true,
            'collapsible'      => false,
            'collapsed'        => false,
            'hidden_if_empty'  => true,
            'column'           => null,
            'layout_max'       => null,
            'layout_size'      => null,
            'cell_label_style' => null,
        ));

        $resolver->addAllowedTypes(array(
            'rendered'         => 'bool',
            'collapsible'      => 'bool',
            'collapsed'        => 'bool',
            'hidden_if_empty'  => 'bool',
            'column'           => array('null', 'int'),
            'layout_max'       => array('null', 'int'),
            'layout_size'      => array('null', 'string'),
            'cell_label_style' => array('null', 'string'),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'panel_section';
    }

    /**
     * Set the last row.
     *
     * @param BlockInterface $block
     * @param BlockInterface $row
     */
    protected function setLastRow(BlockInterface $block, BlockInterface $row)
    {
        if (!BlockUtil::isValidBlock('panel_row_spacer', $row)) {
            $block->setAttribute('last_row', $row->getName());
        }
    }

    /**
     * Get the last row.
     *
     * @param BlockInterface $block
     *
     * @return BlockInterface
     */
    protected function getLastRow(BlockInterface $block)
    {
        if ($block->hasAttribute('last_row')) {
            $row = $block->get($block->getAttribute('last_row'));

            // return current row
            if (count($row) < $row->getOption('column')) {
                return $row;
            }
        }

        // new row
        $rowName = BlockUtil::createUniqueName();
        $block->add($rowName, 'panel_row');

        return $block->get($rowName);
    }
}
