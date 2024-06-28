<?php

declare(strict_types=1);

namespace Arobases\SyliusRightsManagementPlugin\Form\Type\Admin;

use Arobases\SyliusRightsManagementPlugin\Repository\Role\RoleRepository;
use Symfony\Bridge\Doctrine\Form\DataTransformer\CollectionToArrayTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bundle\SecurityBundle\Security;

final class RoleChoiceType extends AbstractType
{
    private RoleRepository $roleRepository;

    /**
     * RoleChoiceType constructor.
     */
    public function __construct(
        RoleRepository $roleRepository,
        private Security $security
    )
    {
        $this->roleRepository = $roleRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if ($options['multiple']) {
            $builder->addModelTransformer(new CollectionToArrayTransformer());
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $user = $this->security->getUser();

        if ('SUPER_ADMIN' !== $user->getRole()->getCode()) {
            $roles = array_filter($this->roleRepository->findAll(), function ($role) use ($user) {
                return 'SUPER_ADMIN' !== $role->getCode();
            });
        } else {
            $roles = $this->roleRepository->findAll();
        }

        $resolver->setDefaults([
            'choices' => $roles,
            'choice_value' => 'code',
            'choice_label' => 'name',
            'choice_translation_domain' => true,
            'placeholder' => 'arobases_sylius_rights_management_plugin.ui.no_role',
        ]);
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }

    public function getBlockPrefix(): string
    {
        return 'arobases_sylius_rights_management_plugin_role_choice';
    }
}
