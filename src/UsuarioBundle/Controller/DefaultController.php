<?php

namespace UsuarioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use UsuarioBundle\Entity\Usuario;
use Symfony\Component\HttpFoundation\Session\Session;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('UsuarioBundle:Default:index.html.twig');
    }

    public function loginAction (Request $request)
    {
    	$correo = $request->get('correo');
    	$clave = $request->get('clave');
        //echo crypt($clave,'ReActive');

		$repository = $this->getDoctrine() ->getRepository('UsuarioBundle:Usuario');
    	$usuario = new Usuario();
		$usuario = $repository->findOneBy(array('correo'  => $correo, 'clave' => crypt($clave,'ReActive')));

		if ($usuario){
			$session = $request->getSession();
			$session->set('nombre', $usuario->getNombre().' '.$usuario->getApellido());
            $session->set('idUsuario', $usuario->getId());
			return $this->render('default/index.html.twig');
        }
        else {
			$this->addFlash('error', 'Usuario o Contraseña Invalida');    
        	return $this->render('UsuarioBundle:Default:index.html.twig');
        }
    }

    public function logoutAction()
    {
        $session = new Session();
        $session->invalidate();
        return $this->render('UsuarioBundle:Default:index.html.twig');
    } 

    public function inicioAction()
    {
        return $this->render('default/index.html.twig');
    } 

    public function crearAction()
    {
         return $this->render('UsuarioBundle:Default:crear.html.twig');
    }

    public function guardarAction(Request $request)
    {
        try{
            $nombre = $request->get('nombre');
            $apellido = $request->get('apellido');
            $correo = $request->get('correo');
            $clave = crypt($request->get('clave'),'Reactive'); 
            $rol = $request->get('rol');

            $usuario = new Usuario();

            $usuario->setNombre($nombre);
            $usuario->setApellido($apellido);
            $usuario->setCorreo($correo);
            $usuario->setClave($clave);
            $usuario->setRol($rol);
            $usuario->setActivo(1);
            $usuario->setCreatedAt(date('y-m-d H:m:s'));
            $usuario->setUpdatedAt(date('y-m-d H:m:s'));

            $conexion = $this->getDoctrine()->getManager();
            $conexion->persist($usuario);
            $conexion->flush();

            $this->addFlash('mensaje', 'Usuario Creado');
        }     
        catch (\Exception $e) {
            $this->addFlash('error',  'Error al Guardar la Información');
        }
        return $this->render('UsuarioBundle:Default:crear.html.twig');
    }
}
