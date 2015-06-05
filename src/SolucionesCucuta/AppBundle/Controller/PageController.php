<?php

namespace SolucionesCucuta\AppBundle\Controller;

use Proxies\__CG__\SolucionesCucuta\AppBundle\Entity\Etiqueta;
use SolucionesCucuta\AppBundle\Entity\Archivo;
use SolucionesCucuta\AppBundle\Entity\Usuario;
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
        $bs = $repository->getArchivosTipo('banner-superior-presentacion');

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
     * @Route("/Search", name="search")
     * @Route("/Search/", name="search_")
     * @Template()
     */
    public function searchAction(Request $request)
    {
        $query = $request->get('search', false);
        $repository = $this->getDoctrine()->getRepository('AppBundle:Usuario');
        $usuarios = $repository->searchCliente($query);
        $rta = array('results' => array());
        foreach($usuarios as $usuario){
            $rta['results'][] = array(
                'value' => $usuario->getId(),
                'title' => $usuario->getNombre(),
                'classStyle' => 'cliente',
                'url'   => $this->generateUrl('cliente',array('slug' => $usuario->getSlug())),
                'text'  => $usuario->getDescripcion()
            );
        }
        $repository = $this->getDoctrine()->getRepository('AppBundle:Etiqueta');
        $etiquetas = $repository->searchEtiquetasTipo($query, 'menu');
        foreach($etiquetas as $etiqueta){
            //$etiqueta = new Etiqueta();
            if($etiqueta->getPadre()){
                $title = $etiqueta->getPadre()->getNombre().'/'.$etiqueta->getNombre().'/';
                $url = $this->generateUrl('clientes_hijo',array(
                    'slugpadre' => $etiqueta->getPadre()->getSlug(),
                    'slughijo' => $etiqueta->getSlug(),
                ));
            }else{
                $title = $etiqueta->getNombre().'/';
                $url = $this->generateUrl('clientes',array('slug' => $etiqueta->getSlug()));
            }
            $rta['results'][] = array(
                'value' => $etiqueta->getId(),
                'title' => $title,
                'classStyle' => 'etiqueta',
                'url'   => $url,
                'text'  => $etiqueta->getDescripcion()
            );
        }
        
        if($request->isXmlHttpRequest()){
            return JsonResponse::create($rta);
        }
        $repository = $this->getDoctrine()->getRepository('AppBundle:Archivo');
        $bs = $repository->getArchivosTipo('banner-superior');

        $repository = $this->getDoctrine()->getRepository('AppBundle:Etiqueta');
        $menus = $repository->getEtiquetasTipo('menu');

        return array(
            'menus' => $menus,
            'clientes' => $usuarios,
            'bannersSuperiores' => $bs,
        );
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
     * @Route("/registrar-usuario/", name="registrarUsuario")
     */
    public function registrarAction(Request $request)
    {
        $email = $request->get('email', false);
        $msg = 'Muchas gracias, recibirá información en su email';
        $error = false;

        if(!$email){
            $msg = 'El email es necesario';
            $error = true;
        }else{
            $repository = $this->getDoctrine()->getRepository('AppBundle:Usuario');
            $usuario = $repository->findOneBy(array('email' => $email));

            if(!$usuario){
                $em = $this->getDoctrine()->getManager();
                $usuario = new Usuario();
                $usuario->setEmail($email);
                $em->persist($usuario);
                $em->flush();
            }else{
                //$msg = $usuario->getNombre().' te encuentras registrado';
            }
        }

        return new JsonResponse(array(
            'msg' => $msg,
            'error' => $error
        ));
    }

    /**
     * @Route("/Sumar/{event}/{id}", name="suma_print_click")
     * @Template()
     */
    public function sumaAction(Request $request, $event, $id){
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:Archivo');
        $noHash = $repo->findByHash(NULL);
        if(count($noHash)){
            $em->transactional(function($em) use ($noHash){
                foreach($noHash as $el){
                    $el->setHash();
                    $em->persist($el);
                }
            });
        }
        $obj = $repo->findOneByHash($id);
        $rta = array(
            'type'  => 'error',
            'title' => 'Archivo no Exncontrado',
            'text'  => 'El archivo buscado no se encuentra disponible.',
        );
        if($obj){
            $event = strtolower($event);
            $mod = false;
            if($event == 'print'){
                $obj->addPrint();
                $mod = true;
                $rta = array(
                    'title' => 'Conteo de Impresiones',
                    'text'  => 'Se agregó una impresión más a "'.$obj->getNombre().'".',
                );
            }elseif($event == 'click'){
                $obj->addClick();
                $mod = true;
                $rta = array(
                    'title' => "Conteo de Click's",
                    'text'  => 'Se agregó un click más a "'.$obj->getNombre().'".',
                );
            }else{
                $rta = array(
                    'type'  => 'error',
                    'title' => 'Evento no identificado',
                    'text'  => 'El evento enviado no fué identificado.',
                );
            }
            if($mod){
                $em->persist($obj);
                $em->flush();
                $rta = array_merge($rta, array(
                    'type'  => 'success'
                ));
            }
        }
        return new JsonResponse($rta);
    }

    /**
     * @Route("/lista-de-{slug}/", name="clientes")
     * @Route("/lista-de-{slugpadre}/{slughijo}", name="clientes_hijo")
     * @Template()
     */
    public function clientesAction(Request $request, $clientes = null)
    {
        $slug = $request->get('slug', $request->get('slugpadre', false));
        $slughijo = $request->get('slughijo', false);

        if(!$slug){
            throw $this->createNotFoundException('Necesita el nombre de la sección para buscar empresas');
        }

        $repository = $this->getDoctrine()->getRepository('AppBundle:Usuario');
        $clientes = $repository->getClientesBySlug($slug, $slughijo);

        /*if(empty($clientes)){
            throw $this->createNotFoundException(ucfirst($slug).' no encontradas');
        }*/

        $repository = $this->getDoctrine()->getRepository('AppBundle:Archivo');
        $bs = $repository->getArchivosTipoBySlug('banner-superior', $slug, $slughijo);

        $repository = $this->getDoctrine()->getRepository('AppBundle:Etiqueta');
        $menus = $repository->getEtiquetasTipo('menu');

        if($slughijo){
            $tag = $repository->findOneBy(array('slug' => $slughijo));
        }else{
            $tag = $repository->findOneBy(array('slug' => $slug));
        }
        $tag_ = $tag;
        if($tag){
            $infografia = null;
            while(is_null($infografia)){
                $infografia = $tag->getInfografias();
                if(count($infografia) <= 0){
                    $infografia = null;
                    if($tag->getPadre()){
                        $tag = $tag->getPadre();
                    }else{
                        $infografia = false;
                    }
                }
            }
            if(method_exists($infografia,'first')){
                $infografia = $infografia->first();
            }else{
                $infografia = null;
            }
        }

        return array(
            'menus' => $menus,
            'clientes' => $clientes,
            'bannersSuperiores' => $bs,
            'infografia' => $infografia,
            'submenus' => count($tag_->getHijos())?$tag_->getHijos():array(),
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
