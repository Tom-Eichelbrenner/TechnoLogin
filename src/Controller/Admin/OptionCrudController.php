<?php

namespace App\Controller\Admin;

use App\Entity\Option;
use App\Repository\OptionRepository;
use Doctrine\DBAL\Types\BooleanType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\EntityCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\HiddenField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;

class OptionCrudController extends AbstractCrudController
{
    public function __construct(
        private OptionRepository $optionRepository,
    )
    {
    }

    public static function getEntityFqcn(): string
    {
        return Option::class;
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        return $this->optionRepository->getIndexQueryBuilder();
    }

    public function index(AdminContext $context): KeyValueStore|Response
    {
        $response = parent::index($context);

        if ($response instanceof Response) {
            return $response;
        }

        /** @var EntityCollection $entities */
        $entities = $response->get('entities');

        foreach ($entities as $entity) {
            $fields = $entity->getFields();
            $fields->unset($fields->getByProperty('type'));
        }
        return $response;


    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->remove(Crud::PAGE_INDEX, Action::BATCH_DELETE)
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
            ->remove(Crud::PAGE_INDEX, Action::NEW);
    }

    public function createEditForm(EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormInterface
    {
        $formBuilder = parent::createEditForm($entityDto, $formOptions, $context);

        $value = $formBuilder->getViewData()->getValue();
        $type = $formBuilder->get('type')->getData();
        if ($type === BooleanType::class) {
            $formBuilder->add('value', CheckboxType::class, [
                'label' => 'Valeur',
                'required' => false,
                'data' => boolval($value),
            ]);
        }
        if ($type === TextareaType::class) {
            $formBuilder->add('value', TextareaType::class, [
                'label' => 'Valeur',
                'required' => false,
                'data' => $value,
            ]);
        }
        return $formBuilder;
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        /** @var Option $entityInstance */
        $option = $entityInstance;
        if ($option->getType() === BooleanType::class) {
            $value = $option->getValue() ? '1' : '0';
            $option->setValue($value);
        }

        parent::updateEntity($entityManager, $entityInstance);
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityPermission('ROLE_ADMIN')
            ->setSearchFields(null)
            ->setEntityLabelInPlural('R??glages g??n??raux')
            ->setEntityLabelInSingular('R??glage g??n??ral')
            ->showEntityActionsInlined(true);
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('label', 'Option')
            ->setFormTypeOption('attr', [
                'readonly' => true,
            ]);
        yield TextField::new('value', 'Valeur');
        yield HiddenField::new('type');
    }
}
