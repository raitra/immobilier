<?php

namespace App\Service;

use App\Entity\Menu;
use App\Repository\MenuRepository;

class MenuManager 
{
    public function __construct(private MenuRepository $menuRepository)
    {
    }

    public function buildMenuTree($data, $parentId = null)
{
    $tree = [];

    foreach ($data as $item) {
        // Si le parent_id de l'item correspond à $parentId ou si le parent_id est null et $parentId est également null
        if (($item->getParent() && $item->getParent()->getId() === $parentId) || ($item->getParent() === null && $parentId === null)) {
            $children = $this->buildMenuTree($data, $item->getId());
            $parents = $this->getParentIdsRecursive($item);
            

            $menuItem = [
                'id' => $item->getId(),
                'parent_id' => $item->getParent() ? $item->getParent()->getId() : 'NULL',
                'parents' => $parents,
                'root_id' => $item->getRoot()->getId(),
                'title' => $item->getTitle(),
                'route_name' => $item->getRouteName(),
                'icon' => $item->getIcon(),
                'is_show' => (bool)$item->isIsShow(),
                'lft' => $item->getLft(),
                'rgt' => $item->getRgt(),
                'lvl' => $item->getLvl(),
                'children' => $children,
            ];

            $tree[] = $menuItem;
        }
    }

    return $tree;
}

    public function buildMenuTreeObj(Menu $root = null)
    {
        $tree = [];
        
        if ($root === null) {
            return $tree;
        }
        
        $children = $root->getChildren();
    
        foreach ($children as $child) {
            $menuItem = [
                'id' => $child->getId(),
                'parent_id' => $child->getParent() ? $child->getParent()->getId() : 'NULL',
                'root_id' => $child->getRoot() ? $child->getRoot()->getId() : 'NULL',
                'title' => $child->getTitle(),
                'route_name' => $child->getRouteName(),
                'icon' => $child->getIcon(),
                'role_name_crypts' => $child->getRoleNameCrypts(),
                'position' => $child->getPosition(),
                'is_show' => $child->isIsShow(),
                'lft' => $child->getLft(),
                'rgt' => $child->getRgt(),
                'lvl' => $child->getLvl(),
                'created_at' => $child->getCreatedAt()->format('Y-m-d H:i:s'),
                'updated_at' => $child->getUpdatedAt()->format('Y-m-d H:i:s'),
                'children' => $this->buildMenuTreeObj($child),
            ];
    
            $tree[] = $menuItem;
        }
    
        return $tree;
    }

    public function getParentIdsRecursive(Menu $menu): array
    {
        $parentIds = [];

        // Utilisation du MenuRepository pour récupérer les parents
        $parents = $this->menuRepository->getPath($menu);

        foreach ($parents as $parent) {
            if($parent->getId() === $menu->getId()){
                continue;
            }
            $parentIds[] = $parent->getId();
        }

        return $parentIds;
    }
    
}