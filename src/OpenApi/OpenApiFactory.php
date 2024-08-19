<?php

namespace App\OpenApi;

use ApiPlatform\Doctrine\Orm\Filter\FilterInterface;
use ApiPlatform\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\OpenApi\Model\Operation;
use ApiPlatform\OpenApi\Model\PathItem;
use ApiPlatform\OpenApi\Model\RequestBody;
use ApiPlatform\OpenApi\OpenApi;
use ApiPlatform\OpenApi\Model\Parameter;

class OpenApiFactory implements OpenApiFactoryInterface
{
    const HIDDEN_FACTORY = 'hidden';

    public function __construct(private OpenApiFactoryInterface $decorated)
    {
        
    }

    public function __invoke(array $context = []): OpenApi
    {
        $openApi = $this->decorated->__invoke($context);

        /** @var PathItem $path */
        foreach ($openApi->getPaths()->getPaths() as $key => $path) {
            if ($path->getGet() && $path->getGet()->getSummary() === self::HIDDEN_FACTORY) {
                $openApi->getPaths()->addPath($key, $path->withSet(null));
            }
        }

        /* Setting up security schema */
        $schemas = $openApi->getComponents()->getSecuritySchemes();
        $schemas['cookieAuth'] = new \ArrayObject([
            'type' => 'apiKey',
            'in' => 'cookie',
            'name' => 'PHPSESSID'
        ]);

        $schemas = $openApi->getComponents()->getSchemas();
        $schemas['Credentials'] = new \ArrayObject([
            'type' => 'object',
            'properties' => [
                'username' => [
                    'type' => 'string',
                    'example' => 'raiitra.rakotoson@raketa.mg'
                ],
                'password' => [
                    'type' => 'string',
                    'example' => 'raitra1234'
                ]
            ]
        ]);

        $schemas['Login'] = new \ArrayObject([
            'type' => 'object',
            'properties' => [
                'user_data' => [
                    'type' => 'array',
                    'example' => [
                        'status' => 'success',
                        'email' => 'raitra.gitlab@raketa.mg',
                        'role' => [
                            "ROLE_SUPER_ADMIN",
                            "ROLE_USER"
                        ]
                    ]
                ],
                'menu_data' => [
                    'type' => 'array',
                    'example' => [
                        "id" => 1,
                        "parent_id" => "NULL",
                        "root_id" => 1,
                        "title" => "Tableau de Bord",
                        "route_name" => "app_admin_dashboard",
                        "icon" => "bi bi-grid",
                        "is_show" => true,
                        "lft" => 1,
                        "rgt" => 2,
                        "lvl" => 0,
                        "children" => []
                    ]
                ]
                
            ]
        ]);

        $pathItemLogin = new PathItem(
            post: new Operation(
                operationId: 'postApiLogin',
                tags: ['Auth'],
                requestBody: new RequestBody(
                    content: new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                '$ref' => '#/components/schemas/Credentials'
                            ]
                        ]
                    ])
                ),
                responses: [
                    '200' => [
                        'description' => 'Utilisateur connecté',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/Login'
                                ]
                            ]
                        ]
                    ],
                ]
            )
        );

        $pathItemLogout = new PathItem(
            post: new Operation(
                operationId: 'postApiLogout',
                tags: ['Auth'],
                responses: [
                    '204' => [
                        'description' => 'No content',
                    ],
                ]
            )
        );
                           
        $pathItemExport = new PathItem(
            get: new Operation(
                operationId: 'getExport',
                tags: ['Export'],
                responses: [
                    '204' => [
                        'description' => 'No content',
                    ],
                ], 
                parameters: [
                    new Parameter(
                            name : 'entity',
                            in : 'query',
                            description : 'The entity parameter',
                            required : true,
                            schema : [
                                'type' => 'string',                               
                            ],                        
                    ),
                ],              
            ),
            
        );

        $openApi->getPaths()->addPath('/api/login', $pathItemLogin);
        $openApi->getPaths()->addPath('/api/logout', $pathItemLogout);
        $openApi->getPaths()->addPath('/export', $pathItemExport);
        return $openApi;
    }
}