<?php

namespace SolucionesCucuta\AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use SolucionesCucuta\AppBundle\Entity\Archivo;
use SolucionesCucuta\AppBundle\Form\ArchivoType;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * Archivo controller.
 *
 * @Route("/admin/archivo")
 */
class ArchivoController extends BasicController
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
     * Lists all Archivo entities.
     *
     * @Route("/", name="archivo")
     * @Route("/search", name="archivo_search")
     * @Route("/page-{pageCurrent}", name="archivo_page_current")
     * @Route("/page-{pageCurrent}/", name="archivo_page_current_")
     * @Route("/items-{itemsPerPage}", name="archivo_items_per_page")
     * @Route("/items-{itemsPerPage}/", name="archivo_items_per_page_")
     * @Route("/items-{itemsPerPage}/page-{pageCurrent}", name="archivo_items_per_page_page_current")
     * @Route("/items-{itemsPerPage}/page-{pageCurrent}/", name="archivo_items_per_page_page_current_")
     * @Route("/page-{pageCurrent}/items-{itemsPerPage}", name="archivo_page_current_items_per_page")
     * @Route("/page-{pageCurrent}/items-{itemsPerPage}/", name="archivo_page_current_items_per_page_")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request, $pageCurrent = 1, $itemsPerPage = 10)
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('AppBundle:Archivo')->getAll();

        $entity = new Archivo();
        $form = $this->createCreateForm($entity, true);
        $form->handleRequest($request);

        if($form->isValid()){
            if ($entity->getNombre()) {
                $query->andWhere($query->expr()->like('a.nombre', $query->expr()->literal('%' . $entity->getNombre() . '%')));
            }
            if ($entity->getSlug()) {
                $query->andWhere($query->expr()->like('a.slug', $query->expr()->literal('%' . $entity->getSlug() . '%')));
            }
            if ($entity->getDescripcion()) {
                $query->andWhere($query->expr()->like('a.descripcion', $query->expr()->literal('%' . $entity->getDescripcion() . '%')));
            }
            if ($entity->getUsuario()) {
                $query
                    ->join('a.usuario', 'au')
                    ->andWhere($query->expr()->like('au.username', $query->expr()->literal('%' . $entity->getUsuario()->getSlug() . '%')));
            }
            if ($entity->getTipo()) {
                $query
                    ->join('a.tipo', 'at')
                    ->andWhere($query->expr()->like('at.slug', $query->expr()->literal('%' . $entity->getTipo()->getSlug() . '%')));
            }
            if ($entity->getPrints()) {

                $query->andWhere($query->expr()->gte('a.prints', $entity->getPrints()));
            }
            if ($entity->getClicks()) {
                $query->andWhere($query->expr()->gte('a.clicks', $entity->getClicks()));
            }
        }

        return array_merge($this->paginate($query, $pageCurrent, $itemsPerPage), array(
            'form' => $form->createView(),
        ));
        /*$entities = $em->getRepository('AppBundle:Archivo')->findAll();

        return array(
            'entities' => $entities,
        );*/
    }
    /**
     * Creates a new Archivo entity.
     *
     * @Route("/", name="archivo_create")
     * @Method("POST")
     * @Template("AppBundle:Archivo:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Archivo();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->upload();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('archivo_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Archivo entity.
     *
     * @param Archivo $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    protected function createCreateForm(Archivo $entity, $search = false)
    {
        return parent::createCreateForm_($entity, 'archivo', $search);
    }

    /**
     * Displays a form to create a new Archivo entity.
     *
     * @Route("/new", name="archivo_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Archivo();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Archivo entity.
     *
     * @Route("/{id}", name="archivo_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Archivo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Archivo entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Archivo entity.
     *
     * @Route("/{id}/edit", name="archivo_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Archivo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Archivo entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Archivo entity.
    *
    * @param Archivo $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Archivo $entity)
    {
        $form = $this->createForm(new ArchivoType(), $entity, array(
            'action' => $this->generateUrl('archivo_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Archivo entity.
     *
     * @Route("/{id}", name="archivo_update")
     * @Method("PUT")
     * @Template("AppBundle:Archivo:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Archivo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Archivo entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('archivo_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Archivo entity.
     *
     * @Route("/{id}", name="archivo_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:Archivo')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Archivo entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('archivo'));
    }

    /**
     * Creates a form to delete a Archivo entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('archivo_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array(
                'label' => 'Borrar',
                'attr'  => array(
                    'class' => 'uk-button uk-button-large uk-button-danger'
                ),
            ))
            ->getForm()
        ;
    }

}
