<?php

namespace SolucionesCucuta\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
     * @Route("/Search/results.json", name="search")
     */
    public function searchAction(Request $request)
    {
        $query = $request->get('query', false);
        $repository = $this->getDoctrine()->getRepository('AppBundle:Usuario');
        $usuarios = $repository->searchCliente($query);
        $rta = array('results' => array());
        foreach($usuarios as $usuario){
            $rta['results'][] = array(
                'value' => $usuario->getId(),
                'title' => $usuario->getNombre(),
                'url'   => $this->generateUrl('cliente',array('slug' => $usuario->getSlug())),
                'text'  => $usuario->getDescripcion()
            );
        }

        return JsonResponse::create($rta);
    }


    /**
     * @Route("/video/{name}", name="video")
     */
    public function videoResponse(Request $request){
        $name = $request->get('name');
        //Target service filesystem
        $finder = new Finder();

        //Get the video filetype
        $fileType = 'mp4';

        //Fetch binary video content
        //$content = $filesystem->read($this->container->getParameter('assetic.write_to').'/'.$name);
        $finder->files()->name($name.'.'.$fileType)->in($this->container->getParameter('assetic.write_to').'/uploads/');
        $content = '';
        foreach($finder as $file){
            $content = $file->getContents();
        }

        return new Response($content, 200, array(
            'Content-Type'        => 'video/' . $fileType
        ));
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
     * @Route("/lista-de-{slug}/", name="clientes")
     * @Route("/lista-de-{slugpadre}/{slughijo}", name="clientes_hijo")
     * @Template()
     */
    public function clientesAction(Request $request)
    {
        $slug = $request->get('slug', $request->get('slugpadre', false));
        $slughijo = $request->get('slughijo', false);

        if(!$slug){
            throw $this->createNotFoundException('Necesita el nombre de la sección para buscar empresas');
        }
        $repository = $this->getDoctrine()->getRepository('AppBundle:Usuario');
        $clientes = $repository->getClientesBySlug($slug, $slughijo);

        if(empty($clientes)){
            throw $this->createNotFoundException(ucfirst($slug).' no encontradas');
        }

        $repository = $this->getDoctrine()->getRepository('AppBundle:Archivo');
        $bs = $repository->getArchivosTipoBySlug('banner-superior', $slug, $slughijo);

        $repository = $this->getDoctrine()->getRepository('AppBundle:Etiqueta');
        $menus = $repository->getEtiquetasTipo('menu');

        return array(
            'menus' => $menus,
            'clientes' => $clientes,
            'bannersSuperiores' => $bs,
        );
    }

    /**
     * @Route("/{slug}/", name="cliente")
     * @Template()
     */
    public function clienteAction(Request $request)
    {
        $slug = $request->get('slug', false);

        if(!$slug){
            throw $this->createNotFoundException('Necesita el nombre de la empresa a buscar');
        }
        $repository = $this->getDoctrine()->getRepository('AppBundle:Usuario');
        $cliente = $repository->findOneBySlug($slug);

        if(!$cliente){
            throw $this->createNotFoundException('Empresa no encontrada');
        }

        $repository = $this->getDoctrine()->getRepository('AppBundle:Archivo');
        $bs = $repository->getArchivosTipo('banner-superior');

        $repository = $this->getDoctrine()->getRepository('AppBundle:Etiqueta');
        $menus = $repository->getEtiquetasTipo('menu');


        return array(
            'menus' => $menus,
            'cliente' => $cliente,
            'bannersSuperiores' => $bs,
        );
    }

}
