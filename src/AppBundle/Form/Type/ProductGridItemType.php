<?php

namespace AppBundle\Form\Type;

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\FormBuilderInterface;

class ProductGridItemType extends AbstractResourceType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('position', 'integer', ['label' => 'app.ui.position'])
            ->add('product', 'sylius_product_choice', ['label' => 'app.ui.product'])
            ->add('enabled', 'checkbox', ['label' => 'sylius.ui.enabled'])
            ->add('file', 'file', ['label' => 'app.ui.file', 'required' => false])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'app_product_grid_item';
    }
}
