<?php

namespace SolucionesCucuta\AppBundle\Controller;

use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class BasicController extends Controller
{
    function paginate($dql, $currentPage, $pageSize)
    {
        $paginator = new Paginator($dql);

        $paginator
            ->getQuery()
            ->setFirstResult($pageSize * ($currentPage - 1)) // set the offset
            ->setMaxResults($pageSize); // set the limit
        $pages = array();
        $i = ceil(count($paginator)/$pageSize);
        while($i--){
            array_push($pages, $i);
        }
        return array(
            'entities'      => $paginator,
            'itemsTotal'    => count($paginator),
            'pagesTotal'    => ceil(count($paginator)/$pageSize),
            'pages'         => $pages,
            'pageCurrent'   => $currentPage,
            'itemFirst'     => $pageSize * ($currentPage - 1),
        );
    }

    /**
     * Creates a form to create a Object entity.
     *
     * @param Object $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    protected function createCreateForm_($entity, $entityname, $search = false)
    {
        $entityname = strtolower($entityname);
        $opts = array(
            'action' => $this->generateUrl($entityname.'_create'),
            'method' => 'POST',
        );
        $configsubmit = array('label' => 'Guardar');
        if($search){
            $opts = array(
                'action' => $this->generateUrl($entityname.'_search'),
                'method' => 'GET',
            );
            $configsubmit['label'] = 'Buscar';
        }
        $classname = 'SolucionesCucuta\\AppBundle\\Form\\'.ucfirst($entityname).'Type';
        $form = $this->createForm(new $classname($search), $entity, $opts);

        $form->add('submit', 'submit', $configsubmit);

        return $form;
    }
}
