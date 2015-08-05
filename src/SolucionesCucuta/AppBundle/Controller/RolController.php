<?php

namespace SolucionesCucuta\AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use SolucionesCucuta\AppBundle\Entity\Rol;
use SolucionesCucuta\AppBundle\Form\RolType;

/**
 * Rol controller.
 *
 * @Route("/admin/rol")
 */
class RolController extends BasicController
{

    /**
     * Lists all Rol entities.
     *
     * @Route("/", name="rol")
     * @Route("/search", name="rol_search")
     * @Route("/page-{pageCurrent}", name="rol_page_current")
     * @Route("/page-{pageCurrent}/", name="rol_page_current_")
     * @Route("/items-{itemsPerPage}", name="rol_items_per_page")
     * @Route("/items-{itemsPerPage}/", name="rol_items_per_page_")
     * @Route("/items-{itemsPerPage}/page-{pageCurrent}", name="rol_items_per_page_page_current")
     * @Route("/items-{itemsPerPage}/page-{pageCurrent}/", name="rol_items_per_page_page_current_")
     * @Route("/page-{pageCurrent}/items-{itemsPerPage}", name="rol_page_current_items_per_page")
     * @Route("/page-{pageCurrent}/items-{itemsPerPage}/", name="rol_page_current_items_per_page_")
     * @Method("GET")
     * @Template()
     **/
    public function indexAction(Request $request, $pageCurrent = 1, $itemsPerPage = 10)
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('AppBundle:Rol')->getAll();

        $entity = new Rol();
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
        }

        return array_merge($this->paginate($query, $pageCurrent, $itemsPerPage), array(
            'form' => $form->createView(),
        ));
    }
    /**
     * Creates a new Rol entity.
     *
     * @Route("/", name="rol_create")
     * @Method("POST")
     * @Template("AppBundle:Rol:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Rol();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('rol_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Rol entity.
     *
     * @param Rol $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    protected function createCreateForm(Rol $entity, $search = false)
    {
        return parent::createCreateForm_($entity, 'rol', $search);
    }

    /**
     * Displays a form to create a new Rol entity.
     *
     * @Route("/new", name="rol_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Rol();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Rol entity.
     *
     * @Route("/{id}", name="rol_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Rol')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Rol entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Rol entity.
     *
     * @Route("/{id}/edit", name="rol_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Rol')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Rol entity.');
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
    * Creates a form to edit a Rol entity.
    *
    * @param Rol $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Rol $entity)
    {
        $form = $this->createForm(new RolType(), $entity, array(
            'action' => $this->generateUrl('rol_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Rol entity.
     *
     * @Route("/{id}", name="rol_update")
     * @Method("PUT")
     * @Template("AppBundle:Rol:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Rol')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Rol entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('rol_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Rol entity.
     *
     * @Route("/{id}", name="rol_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:Rol')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Rol entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('rol'));
    }

    /**
     * Creates a form to delete a Rol entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('rol_delete', array('id' => $id)))
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
