<?php
namespace App\Doctrine\Filter;
use ApiPlatform\Doctrine\Orm\Filter\AbstractFilter;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use App\Service\DqlManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

;
class GlobalSearchFilter extends AbstractFilter
{
    const STUDENT_ENTITY = 'student';
    const TEACHER_ENTITY = 'teacher';
    const SUBJECT_ENTITY = 'subject';
    const EXAM_ENTITY = 'exams';
    
    protected ManagerRegistry $managerRegistry;

    
    public function __construct(
        private EntityManagerInterface $em,
        protected ?array $properties = null,
        ManagerRegistry $managerRegistry,
        private DqlManager $dqlManager
    )
    {
        $this->managerRegistry = $managerRegistry;
        parent::__construct($this->managerRegistry);
        
    }
    public function getDescription(string $resourceClass): array
    {
        return [
            'search' => [
                'property' => null,
                'type' => 'string',
                'required' => false,
                'swagger' => [
                    'description' => 'Search on all fields',
                    'name' => 'Global Search',
                    'type' => 'string',
                ],
            ],
        ];
    }
    
    protected function filterProperty(
        string $property,
        $value,
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        Operation $operation = null,
        array $context = []
    ): void {
        
        if ($property !== 'search' || null === $value || '' === $value) {
            return;
        }
        
        $alias = $queryBuilder->getRootAliases()[0];
        $expr = $queryBuilder->expr();
        $orX = $expr->orX();
    
        $classMetadata = $this->em->getClassMetadata($resourceClass);
        $fieldNames = $classMetadata->getFieldNames();
        $associationMappings = $classMetadata->getAssociationMappings();
        $names =[];
        
        foreach ($fieldNames as $fieldName) {
            
            if (
                $fieldName == 'createdAt'
                || $fieldName == 'updatedAt'
                || $fieldName == 'startAt'
                || $fieldName == 'endAt'
            ) {
                continue;
            }
            $names[]= $fieldName;
    
            $parameterName = $queryNameGenerator->generateParameterName($fieldName);
            $orX->add($expr->like(sprintf('%s.%s', $alias, $fieldName), sprintf(':%s', $parameterName)));
            $queryBuilder->setParameter($parameterName, '%' . $value . '%');
        }

        foreach ($associationMappings as $association) {
            if($association['inversedBy'] === self::EXAM_ENTITY){
                break;
            } 
            if($association['mappedBy']){
                continue;
            }            
            $targetEntity = $association['targetEntity'];
            $targetAlias = strtolower(substr($targetEntity, strrpos($targetEntity, '\\') + 1, 3)); 
            $targetMetadata = $this->em->getClassMetadata($targetEntity);
            $targetFieldNames = $targetMetadata->getFieldNames();
            if($association['fieldName'] === self::STUDENT_ENTITY || 
                $association['fieldName'] === self::TEACHER_ENTITY ||
                $association['fieldName'] === self::SUBJECT_ENTITY
              )
            {
                $nameAlias = $targetFieldNames[2];   
            }else{
                $nameAlias = $targetFieldNames[1];
            }
            $targetParameter = $queryNameGenerator->generateParameterName($targetFieldNames[1]);
            $orX->add($expr->like(sprintf('%s.%s', $targetAlias, $nameAlias), sprintf(':%s', $targetParameter)));
            $queryBuilder->setParameter($targetParameter, '%' . $value . '%');
    
             $queryBuilder->leftJoin($targetEntity, $targetAlias, 'WITH', "{$targetAlias}.id = {$alias}.{$association['fieldName']}");
        }

        if ($orX->count() > 0) {
            $queryBuilder->andWhere($orX);
            
        }
    }
}