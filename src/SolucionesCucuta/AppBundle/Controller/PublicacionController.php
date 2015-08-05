<?php

namespace SolucionesCucuta\AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use SolucionesCucuta\AppBundle\Entity\Publicacion;
use SolucionesCucuta\AppBundle\Form\PublicacionType;

/**
 * Publicacion controller.
 *
 * @Route("/admin/publicacion")
 */
class PublicacionController extends BasicController
{

    /**
     * Lists all Publicacion entities.
     *
     * @Route("/", name="publicacion")
     * @Route("/search", name="publicacion_search")
     * @Route("/page-{pageCurrent}", name="publicacion_page_current")
     * @Route("/page-{pageCurrent}/", name="publicacion_page_current_")
     * @Route("/items-{itemsPerPage}", name="publicacion_items_per_page")
     * @Route("/items-{itemsPerPage}/", name="publicacion_items_per_page_")
     * @Route("/items-{itemsPerPage}/page-{pageCurrent}", name="publicacion_items_per_page_page_current")
     * @Route("/items-{itemsPerPage}/page-{pageCurrent}/", name="publicacion_items_per_page_page_current_")
     * @Route("/page-{pageCurrent}/items-{itemsPerPage}", name="publicacion_page_current_items_per_page")
     * @Route("/page-{pageCurrent}/items-{itemsPerPage}/", name="publicacion_page_current_items_per_page_")
     * @Method("GET")
     * @Template()
     **/
    public function indexAction(Request $request, $pageCurrent = 1, $itemsPerPage = 10)
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('AppBundle:Publicacion')->getAll();

        $entity = new Publicacion();
        $form = $this->createCreateForm($entity, true);
        $form->handleRequest($request);

        if($form->isValid()){
            if ($entity->getTitulo()) {
                $query->andWhere($query->expr()->like('a.titulo', $query->expr()->literal('%' . $entity->getTitulo() . '%')));
            }
            if ($entity->getSubtitulo()) {
                $query->andWhere($query->expr()->like('a.subtitulo', $query->expr()->literal('%' . $entity->getSubtitulo() . '%')));
            }
            if ($entity->getSlug()) {
                $query->andWhere($query->expr()->like('a.slug', $query->expr()->literal('%' . $entity->getSlug() . '%')));
            }
            if ($entity->getContenido()) {
                $query->andWhere($query->expr()->gte('a.contenido', $entity->getContenido()));
            }
            if ($entity->getTipo()) {
                $query
                    ->join('a.tipo', 'ar')
                    ->andWhere($query->expr()->like('ar.tipo', $query->expr()->literal('%' . $entity->getTipo()->getSlug() . '%')));
            }
            if ($entity->getEstado()) {
                $query
                    ->join('a.estado', 'ae')
                    ->andWhere($query->expr()->like('ae.estado', $query->expr()->literal('%' . $entity->getEstado()->getSlug() . '%')));
            }
        }

        return array_merge($this->paginate($query, $pageCurrent, $itemsPerPage), array(
            'form' => $form->createView(),
        ));
    }
    /**
     * Creates a new Publicacion entity.
     *
     * @Route("/", name="publicacion_create")
     * @Method("POST")
     * @Template("AppBundle:Publicacion:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Publicacion();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('publicacion_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Publicacion entity.
     *
     * @param Publicacion $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    protected function createCreateForm(Publicacion $entity, $search = false)
    {
        return parent::createCreateForm_($entity, 'publicacion', $search);
    }

    /**
     * Displays a form to create a new Publicacion entity.
     *
     * @Route("/new", name="publicacion_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Publicacion();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Publicacion entity.
     *
     * @Route("/{id}", name="publicacion_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Publicacion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Publicacion entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Publicacion entity.
     *
     * @Route("/{id}/edit", name="publicacion_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Publicacion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Publicacion entity.');
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
    * Creates a form to edit a Publicacion entity.
    *
    * @param Publicacion $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Publicacion $entity)
    {
        $form = $this->createForm(new PublicacionType(), $entity, array(
            'action' => $this->generateUrl('publicacion_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Publicacion entity.
     *
     * @Route("/{id}", name="publicacion_update")
     * @Method("PUT")
     * @Template("AppBundle:Publicacion:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Publicacion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Publicacion entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('publicacion_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Publicacion entity.
     *
     * @Route("/{id}", name="publicacion_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:Publicacion')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Publicacion entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('publicacion'));
    }

    /**
     * Creates a form to delete a Publicacion entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('publicacion_delete', array('id' => $id)))
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
