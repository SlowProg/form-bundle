<?php
/*
 * This file is part of NeutronFormBundle
 *
 * (c) Nikolay Georgiev <azazen09@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Neutron\FormBundle\Form\Type;

use Symfony\Component\Form\FormView;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\OptionsResolver\Options;

use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\FormInterface;

use Symfony\Component\Form\AbstractType;

/**
 * This class creates jquery autocomplete element
 *
 * @author Nikolay Georgiev <azazen09@gmail.com>
 * @since 1.0
 */
class AutocompleteType extends AbstractType
{

    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Form.AbstractType::buildView()
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['configs'] = $options['configs'];
    }
    
    
    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Form.AbstractType::setDefaultOptions()
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'configs' => array(
                'use_categories' => false,        
            ),
        ));
    
        $resolver->setNormalizers(array(
            'configs' => function (Options $options, $value) {

                if (!isset($value['source'])){
                    throw new \InvalidArgumentException('Option "configs:source" is not defined');
                }
                
                return $value;
            }
        ));
    }

    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Form.AbstractType::getParent()
     */
    public function getParent()
    {
        return 'text';
    }

    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Form.FormTypeInterface::getName()
     */
    public function getName()
    {
        return 'neutron_autocomplete';
    }

}