<?php

namespace UsuarioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use UsuarioBundle\Entity\Usuario;
use Symfony\Component\HttpFoundation\Session\Session;
use ProductoBundle\Entity\Producto;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('UsuarioBundle:Default:index.html.twig');
    }

    public function productosCodificados()
    {
        $prod=$this->getDoctrine()->getEntityManager();
        $productos = new Producto();
        $consulta = "SELECT p.codigo,count(p.id) as cantidad FROM ProductoBundle\Entity\Producto p GROUP BY p.codigo";
        $query = $prod->createQuery($consulta);
        $productos = $query->getResult();

       return json_encode($productos);
    }

    public function productosTipo()
    {
        $prod=$this->getDoctrine()->getEntityManager();
        $productos = new Producto();
        $consulta = "SELECT p.tipo,count(p.id) as cantidad FROM ProductoBundle\Entity\Producto p GROUP BY p.tipo";
        $query = $prod->createQuery($consulta);
        $productos = $query->getResult();

       return json_encode($productos);
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
            $data = $this->productosCodificados();
            $dataTipo = $this->productosTipo();
			return $this->render('default/index.html.twig', array('productos' => $data,'productosTipo' => $dataTipo));
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
        $data = $this->productosCodificados();
        $dataTipo = $this->productosTipo();
        return $this->render('default/index.html.twig', array('productos' => $data,'productosTipo' => $dataTipo));
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
