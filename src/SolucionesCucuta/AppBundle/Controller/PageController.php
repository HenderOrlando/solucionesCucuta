<?php

namespace SolucionesCucuta\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Page controller.
 *
 * @Route("/")
 */
class PageController extends Controller
{
    /**
     * @Route("/login/", name="login")
     * @Template()
     */
    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return array(
            'last_username' => $lastUsername,
            'error'         => $error,
        );
    }

    /**
     * @Route("/admin/login_check", name="login_check")
     */
    public function loginCheckAction(Request $request)
    {
        // this controller will not be executed,
        // as the route is handled by the Security system
    }

    /**
     * @Route("/admin/logout", name="logout")
     */
    public function logoutAction(Request $request)
    {

    }

    /**
     * @Route("/", name="homepage")
     * @Template()
     */
    public function indexAction()
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Archivo');
        $bs = $repository->getArchivosTipo('banner-superior');

        $repository = $this->getDoctrine()->getRepository('AppBundle:Etiqueta');
        $menus = $repository->getEtiquetasTipo('menu');

        $repository = $this->getDoctrine()->getRepository('AppBundle:Publicacion');
        $publicaciones = $repository->getPublicacionesTipo('presentacion');

        $repository = $this->getDoctrine()->getRepository('AppBundle:Usuario');
        $clientes = $repository->getUsuariosRol('cliente');

        return array(
            'menus' => $menus,
            'clientes' => $clientes,
            'bannersSuperiores' => $bs,
            'publicaciones' => $publicaciones,
        );
    }

    /**
     * @Route("/Search/", name="search")
     */
    public function searchAction(Request $request)
    {
        $query = $request->get('query');
        $usuarios = $this->getDoctrine()->getRepository('AppBundle:Usuario')->findAll();
        $rta = array();
        foreach($usuarios as $usuario){
            $rta[] = array(
                'value' => $usuario->getId(),
                'title' => $usuario->getNombre(),
                'url'   => $this->generateUrl('usuario',array('id' => $usuario->getId())),
                'text'  => $usuario->getDescripcion()
            );
        }

        return JsonResponse::create($rta);
    }


    /**
     * @Route("/admin/", name="clientes_admin")
     * @Template()
     */
    public function clientesAdminAction()
    {
        return array(
            // ...
        );
    }

    /**
     * @Route("/{slug}/", name="cliente")
     * @Template()
     */
    public function clienteAction()
    {
        return array(
                // ...
            );
    }
    /**
     * @Route("/lista-de-{slug}/", name="clientes")
     * @Route("/lista-de-{slugpadre}/{slughijo}", name="clientes_hijo")
     * @Template()
     */
    public function clientesAction()
    {
        return array(
                // ...
            );
    }

}
