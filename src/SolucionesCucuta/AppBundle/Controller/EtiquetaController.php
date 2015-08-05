<?php

namespace SolucionesCucuta\AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use SolucionesCucuta\AppBundle\Entity\Etiqueta;
use SolucionesCucuta\AppBundle\Form\EtiquetaType;

/**
 * Etiqueta controller.
 *
 * @Route("/admin/etiqueta")
 */
class EtiquetaController extends BasicController
{

    /**
     * Lists all Etiqueta entities.
     *
     * @Route("/", name="etiqueta")
     * @Route("/search", name="etiqueta_search")
     * @Route("/page-{pageCurrent}", name="etiqueta_page_current")
     * @Route("/page-{pageCurrent}/", name="etiqueta_page_current_")
     * @Route("/items-{itemsPerPage}", name="etiqueta_items_per_page")
     * @Route("/items-{itemsPerPage}/", name="etiqueta_items_per_page_")
     * @Route("/items-{itemsPerPage}/page-{pageCurrent}", name="etiqueta_items_per_page_page_current")
     * @Route("/items-{itemsPerPage}/page-{pageCurrent}/", name="etiqueta_items_per_page_page_current_")
     * @Route("/page-{pageCurrent}/items-{itemsPerPage}", name="etiqueta_page_current_items_per_page")
     * @Route("/page-{pageCurrent}/items-{itemsPerPage}/", name="etiqueta_page_current_items_per_page_")
     * @Method("GET")
     * @Template()
     **/
    public function indexAction(Request $request, $pageCurrent = 1, $itemsPerPage = 10)
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('AppBundle:Etiqueta')->getAll();

        $entity = new Etiqueta();
        $form = $this->createCreateForm($entity, true);
        $form->handleRequest($request);

        if($form->isValid()){
            if ($entity->getNombre()) {
                $query->andWhere($query->expr()->like('a.nombre', $query->expr()->literal('%' . $entity->getNombre() . '%')));
            }
            if ($entity->getSlug()) {
                $query->andWhere($query->expr()->like('a.slug', $query->expr()->literal('%' . $entity->getSlug() . '%')));
            }
            if ($entity->getIcon()) {
                $query->andWhere($query->expr()->like('a.icon', $query->expr()->literal('%' . $entity->getIcon() . '%')));
            }
            if ($entity->getDescripcion()) {
                $query->andWhere($query->expr()->like('a.descripcion', $query->expr()->literal('%' . $entity->getDescripcion() . '%')));
            }
            if ($entity->getTipo()) {
                $query
                    ->join('a.tipo', 'at')
                    ->andWhere($query->expr()->like('at.slug', $query->expr()->literal('%' . $entity->getTipo()->getSlug() . '%')));
            }
        }

        return array_merge($this->paginate($query, $pageCurrent, $itemsPerPage), array(
            'form' => $form->createView(),
        ));
    }
    /**
     * Creates a new Etiqueta entity.
     *
     * @Route("/", name="etiqueta_create")
     * @Method("POST")
     * @Template("AppBundle:Etiqueta:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Etiqueta();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('etiqueta_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Etiqueta entity.
     *
     * @param Etiqueta $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    protected function createCreateForm(Etiqueta $entity, $search = false)
    {
        return parent::createCreateForm_($entity, 'etiqueta', $search);
    }

    /**
     * Displays a form to create a new Etiqueta entity.
     *
     * @Route("/new", name="etiqueta_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Etiqueta();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Etiqueta entity.
     *
     * @Route("/{id}", name="etiqueta_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Etiqueta')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Etiqueta entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Etiqueta entity.
     *
     * @Route("/{id}/edit", name="etiqueta_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Etiqueta')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Etiqueta entity.');
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
    * Creates a form to edit a Etiqueta entity.
    *
    * @param Etiqueta $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Etiqueta $entity)
    {
        $form = $this->createForm(new EtiquetaType(), $entity, array(
            'action' => $this->generateUrl('etiqueta_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Etiqueta entity.
     *
     * @Route("/{id}", name="etiqueta_update")
     * @Method("PUT")
     * @Template("AppBundle:Etiqueta:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Etiqueta')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Etiqueta entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('etiqueta_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Etiqueta entity.
     *
     * @Route("/{id}", name="etiqueta_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:Etiqueta')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Etiqueta entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('etiqueta'));
    }

    /**
     * Creates a form to delete a Etiqueta entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('etiqueta_delete', array('id' => $id)))
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
