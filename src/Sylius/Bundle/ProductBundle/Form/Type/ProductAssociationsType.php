<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\Bundle\ProductBundle\Form\Type;

use Sylius\Bundle\ResourceBundle\Form\Type\FixedCollectionType;
use Sylius\Component\Product\Model\ProductAssociationTypeInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ProductAssociationsType extends AbstractType
{
    private RepositoryInterface $productAssociationTypeRepository;

    private DataTransformerInterface $productsToProductAssociationsTransformer;

    public function __construct(
        RepositoryInterface $productAssociationTypeRepository,
        DataTransformerInterface $productsToProductAssociationsTransformer
    ) {
        $this->productAssociationTypeRepository = $productAssociationTypeRepository;
        $this->productsToProductAssociationsTransformer = $productsToProductAssociationsTransformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $a = new \reflectionobject($builder->getForm());
       // var_dump($builder->getForm()->offsetExists('product'));exit;
        $builder->addModelTransformer($this->productsToProductAssociationsTransformer);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'entries' => $this->productAssociationTypeRepository->findAll(),
            'entry_type' => TextType::class,
            'entry_name' => function (ProductAssociationTypeInterface $productAssociationType) {
                return $productAssociationType->getCode();
            },
            'entry_options' => function (ProductAssociationTypeInterface $productAssociationType) {
                return [
                    'label' => $productAssociationType->getName(),
                ];
            },
        ]);
    }

    public function getParent(): string
    {
        return FixedCollectionType::class;
    }

    public function getBlockPrefix(): string
    {
        return 'sylius_product_associations';
    }
}
