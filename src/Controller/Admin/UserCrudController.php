<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $passwordField = TextField::new('plainPassword', 'Password')
            ->setRequired(true)
            ->setFormType(PasswordType::class)
            ->setFormTypeOption('attr', ['autocomplete' => 'off'])
            ->onlyOnForms();

        if (Crud::PAGE_EDIT === $pageName) {
            $passwordField->setHelp('Leave it blank for no password change');
        }

        return [
            TextField::new('email', 'Email')
                ->setFormTypeOption('attr', ['autocomplete' => 'off']),
            $passwordField,
            ChoiceField::new('roles')
                ->setRequired(false)
                ->setFormTypeOption('multiple', true)
                ->setChoices([
                    'User' => 'ROLE_AUDITOR',
                    'Admin' => 'ROLE_ADMIN',
                ])
                ->setPermission('ROLE_ADMIN'),
        ];
    }
}
