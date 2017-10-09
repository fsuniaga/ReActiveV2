<?php

namespace ProductoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use ProductoBundle\Entity\Producto;
use Symfony\Component\Validator\Constraints\DateTime;
use Doctrine\ORM\Tools\Pagination\Paginator;
use UsuarioBundle\Entity\Usuario;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('ProductoBundle:Default:index.html.twig');
    }

    public function crearAction()
    {
        return $this->render('ProductoBundle:Default:crear.html.twig', array('ruta' => 'registrar'));
    }

    public function registrarAction(Request $request)
    {
        try{
        	$nombre = $request->get('nombre');
        	$tipo = $request->get('tipo');
        	$fechaVencimiento = $request->get('fechaVencimiento');
           
        	$codigo = $request->get('codigo'); 
         	$observacionCodigo = $request->get('observacionCodigo');
        	$cantidad = $request->get('cantidad');
        	$idUsuario = $request->getSession()->get('idUsuario');
        	$repository = $this->getDoctrine() ->getRepository('ProductoBundle:Producto');
        	$producto = new Producto();

            $usu = $this->getDoctrine()->getEntityManager();
            $usuario = $usu->getRepository('UsuarioBundle:Usuario');
            $usuario = $usuario->find($idUsuario);

        	$producto->setNombre($nombre);
        	$producto->setTipo($tipo);
            $producto->setFechavencimiento($fechaVencimiento);
        	$producto->setCodigo($codigo);
        	$producto->setObservacioncodigo($observacionCodigo);
        	$producto->setCantidad($cantidad);
        	$producto->setIdusuario($idUsuario);
            $producto->setUsuario($usuario);

    	    $conexion = $this->getDoctrine()->getManager();
    	    
            $conexion->persist($producto);
    	    $conexion->flush();

    	    $this->addFlash('mensaje', 'Producto Registrado'); 
        }
        catch (\Exception $e) {
            $this->addFlash('error',   $e->getMessage());
        }        

        return $this->render('ProductoBundle:Default:crear.html.twig', array('ruta' => 'crear'));

    }

    public function getPaginatePosts($pageSize,$currentPage){
        $prod=$this->getDoctrine()->getEntityManager();
         
        $consulta = "SELECT p,u FROM ProductoBundle\Entity\Producto p join p.usuario u where p.idusuario = u.id ORDER BY p.id asc";
        $query = $prod->createQuery($consulta)
                               ->setFirstResult($pageSize * ($currentPage - 1))
                               ->setMaxResults($pageSize);
 
        $paginator = new Paginator($query, $fetchJoinCollection = true);
 
        return $paginator;
    }

    public function listarAction($page)
    {
        $repository = $this->getDoctrine()->getRepository('ProductoBundle:Producto');
        $productos = new Producto();

        $pageSize=8;
        $productos=$this->getPaginatePosts($pageSize,$page);
        
        $totalProductos = count($productos);
        $pagesCount = ceil($totalProductos / $pageSize);

        return $this->render('ProductoBundle:Default:listar.html.twig', array('productos' => $productos,'pagesCount' => $pagesCount));
    }   

    public function eliminarAction($id)
    {
        $prod = $this->getDoctrine()->getEntityManager();
        $producto = $prod->getRepository('ProductoBundle:Producto');
        $producto = $producto->find($id);
        $prod->remove($producto);
        $flush=$prod->flush();

        $this->addFlash('mensaje', 'Producto Eliminado');         

        return $this->listarAction(1);
    }     

    public function editarAction($id)
    {
        $prod = $this->getDoctrine()->getEntityManager();
        $producto = $prod->getRepository('ProductoBundle:Producto');
        $producto = $producto->find($id);

        return $this->render('ProductoBundle:Default:crear.html.twig', array('producto' => $producto, 'ruta' => 'actualizar'));
    }

   public function actualizarAction(Request $request){

        $id = $request->get('id');
        $nombre = $request->get('nombre');
        $tipo = $request->get('tipo');
        $fechaVencimiento = $request->get('fechaVencimiento');
        $codigo = $request->get('codigo'); 
        $observacionCodigo = $request->get('observacionCodigo');
        $cantidad = $request->get('cantidad');
        $idUsuario = $request->getSession()->get('idUsuario');

        $prod = $this->getDoctrine()->getEntityManager();
        $producto = $prod->getRepository('ProductoBundle:Producto');
        $producto = $producto->find($id);

        $producto->setNombre($nombre);
        $producto->setTipo($tipo);
        $producto->setFechavencimiento($fechaVencimiento);
        $producto->setCodigo($codigo);
        $producto->setObservacioncodigo($observacionCodigo);
        $producto->setCantidad($cantidad);
        $producto->setIdusuario($idUsuario);

        $conexion = $this->getDoctrine()->getManager();
        $conexion->persist($producto);
        $conexion->flush();

        $this->addFlash('mensaje', 'Producto Actualizado'); 

        return $this->render('ProductoBundle:Default:crear.html.twig', array('producto' => $producto, 'ruta' => 'actualizar'));    
   } 
}


