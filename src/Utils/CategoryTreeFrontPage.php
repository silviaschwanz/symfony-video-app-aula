<?php

namespace App\Utils;

use App\Twig\AppExtension;
use App\Utils\AbstractClasses\CategoryTreeAbstract;

class CategoryTreeFrontPage extends CategoryTreeAbstract
{

/*    protected $slugger;
    protected $mainParentName;
    protected $mainParentId;
    protected $currentCategoryName;*/




    public function getCategoryListAndParent(int $id):string
    {
        $this->slugger = new AppExtension(); //Twig extension to slugify url's for categories
        $parentData = $this->getMainParent($id);
        $this->mainParentName = $parentData['name'];
        $this->mainParentId = $parentData['id'];
        $key = array_search($id, array_column($this->categoriesArrayFromDb, 'id'));
        $this->currentCategoryName = $this->categoriesArrayFromDb[$key]['name'];
        $categories_array = $this->buildTree($parentData['id']);
        return $this->getCategoryList($categories_array);
    }


    public function getCategoryList(array $categories_array)
    {
        $this->categorylist .= '<ul>';
        foreach ($categories_array as $value)
        {
            $catName = $this->slugger->slugify($value['name']);

            $url = $this->urlgenerator->generate('video_list', ['category_name'=>$catName, 'id'=>$value['id']]);
            $this->categorylist .= '<li>' . '<a href="' . $url . '">' . $value['name'] . '</a>';
            if(!empty($value['children']))
            {
                $this->getCategoryList($value['children']);
            }
            $this->categorylist .= '</li>';

        }
        $this->categorylist .= '</ul>';
        return $this->categorylist;
    }

    public function getMainParent(int $id): array
    {
        $key = array_search($id, array_column($this->categoriesArrayFromDb, 'id'));
        if($this->categoriesArrayFromDb[$key]['parent_id'] != null)
        {
            return $this->getMainParent($this->categoriesArrayFromDb[$key]['parent_id']);
        }

        else
        {
            return [
                'id' => $this->categoriesArrayFromDb[$key]['id'],
                'name' => $this->categoriesArrayFromDb[$key]['name'] ];
        }
    }
}
