<?php

namespace SolucionesCucuta\AppBundle\Controller;

use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use SolucionesCucuta\AppBundle\Entity\Tipo;
use SolucionesCucuta\AppBundle\Form\TipoType;

/**
 * Tipo controller.
 *
 * @Route("/admin/tipo")
 */
class TipoController extends BasicController
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
     * Lists all Tipo entities.
     *
     * @Route("/", name="tipo")
     * @Route("/search", name="tipo_search")
     * @Route("/page-{pageCurrent}", name="tipo_page_current")
     * @Route("/page-{pageCurrent}/", name="tipo_page_current_")
     * @Route("/items-{itemsPerPage}", name="tipo_items_per_page")
     * @Route("/items-{itemsPerPage}/", name="tipo_items_per_page_")
     * @Route("/items-{itemsPerPage}/page-{pageCurrent}", name="tipo_items_per_page_page_current")
     * @Route("/items-{itemsPerPage}/page-{pageCurrent}/", name="tipo_items_per_page_page_current_")
     * @Route("/page-{pageCurrent}/items-{itemsPerPage}", name="tipo_page_current_items_per_page")
     * @Route("/page-{pageCurrent}/items-{itemsPerPage}/", name="tipo_page_current_items_per_page_")
     * @Method("GET")
     * @Template()
    **/
    public function indexAction(Request $request, $pageCurrent = 1, $itemsPerPage = 10)
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('AppBundle:Tipo')->getAll();

        $entity = new Tipo();
        $form = $this->createCreateForm($entity, true);
        $form->handleRequest($request);

        if($form->isValid()){
            if ($entity->getNombre()) {
                $query->andWhere($query->expr()->like('a.nombre', $query->expr()->literal('%' . $entity->getNombre() . '%')));
            }
            if ($entity->getSlug()) {
                $query->andWhere($query->expr()->like('a.slug', $query->expr()->literal('%' . $entity->getSlug() . '%')));
            }
            if ($entity->getDominio()) {
                $query->andWhere($query->expr()->gte('a.dominio', $entity->getDominio()));
            }
            if ($entity->getDescripcion()) {
                $query->andWhere($query->expr()->like('a.descripcion', $query->expr()->literal('%' . $entity->getDescripcion() . '%')));
            }
        }

        return array_merge($this->paginate($query, $pageCurrent, $itemsPerPage), array(
            'form' => $form->createView(),
        ));
    }
    /**
     * Creates a new Tipo entity.
     *
     * @Route("/", name="tipo_create")
     * @Method("POST")
     * @Template("AppBundle:Tipo:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Tipo();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('tipo_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Tipo entity.
     *
     * @param Tipo $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    protected function createCreateForm($entity, $search = false)
    {
        return parent::createCreateForm_($entity, 'tipo', $search);
    }

    /**
     * Displays a form to create a new Tipo entity.
     *
     * @Route("/new", name="tipo_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Tipo();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Tipo entity.
     *
     * @Route("/{id}", name="tipo_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Tipo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tipo entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Tipo entity.
     *
     * @Route("/{id}/edit", name="tipo_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Tipo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tipo entity.');
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
    * Creates a form to edit a Tipo entity.
    *
    * @param Tipo $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Tipo $entity)
    {
        $form = $this->createForm(new TipoType(), $entity, array(
            'action' => $this->generateUrl('tipo_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Tipo entity.
     *
     * @Route("/{id}", name="tipo_update")
     * @Method("PUT")
     * @Template("AppBundle:Tipo:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Tipo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tipo entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('tipo_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Tipo entity.
     *
     * @Route("/{id}", name="tipo_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:Tipo')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Tipo entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('tipo'));
    }

    /**
     * Creates a form to delete a Tipo entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('tipo_delete', array('id' => $id)))
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
