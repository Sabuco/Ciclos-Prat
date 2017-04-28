<?php
/**
 * Created by PhpStorm.
 * User: Alvaro
 * Date: 14/03/2017
 * Time: 20:49
 */

namespace AppBundle\Controller;


use AppBundle\Form\ComentarioType;
use AppBundle\Form\TextType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Comentario;
use AppBundle\Entity\Producto;
use AppBundle\Form\ProductoType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class ProductoController extends Controller
{
    /**
     * @Route(path="/", name="app_producto_indice")
     *
     */
    public function indiceAction()
    {
        $m = $this->getDoctrine()->getManager();
        $repo = $m->getRepository('AppBundle:Producto');


        $m->flush();
        $productos = $repo->findAll();
        return $this->render(':productosTemplates:indice.html.twig',
            [
                'productos' => $productos
            ]);
    }


    /**
     * @Route(path="/insert", name="app_producto_insert")
     * @return \Symfony\Component\HttpFoundation\Response
     * @Security("has_role('ROLE_USER')")
     */
    public function insertAction()
    {
        $Producto = new Producto();
        $form = $this->createForm(ProductoType::class, $Producto);
        return $this->render(':productosTemplates:formulario.html.twig',
            [
                'form'      => $form->createView(),
                'action'    => $this->generateUrl('app_producto_doInsert')
            ]
        );


    }

    /**
     * @Route (path="/doInsert", name="app_producto_doInsert")
     * @Security("has_role('ROLE_USER')")
     */
    public function doInsertAction(Request $request)
    {
        $Producto = new Producto();
        $form = $this->createForm(ProductoType::class, $Producto);

        $form->handleRequest($request);

        if($form->isValid())
        {
            $user = $this->getUser();
            $Producto->setClients($user);
            $m = $this->getDoctrine()->getManager();
            $m->persist($Producto);
            $m->flush();

            return $this->redirectToRoute('app_index_index');
        }

        return $this->render(':productosTemplates:formulario.html.twig',
            [
                'form' => $form->createView(),
                'action' => $this->generateUrl('app_producto_doInsert')
            ]);
    }

    /**
     * @Route(path="/update/{id}", name="app_producto_update")
     * @Security("has_role('ROLE_USER')")
     */
    public function updateAction($id)
    {
        $m = $this->getDoctrine()->getManager();
        $repositorio = $m->getRepository('AppBundle:Producto');
        $Producto = $repositorio->find($id);
        $form = $this->createForm(ProductoType::class, $Producto);
        return $this->render(':productosTemplates:formulario.html.twig',
            [
                'form' => $form->CreateView(),
                'action' => $this->generateUrl('app_producto_doUpdate', ['id' => $id]),
            ]);
    }

    /**
     * @Route(path="/doUpdate/{id}", name="app_producto_doUpdate")
     * @Security("has_role('ROLE_USER')")
     */

    public function doUpdate($id, Request $request)
    {
        $m = $this->getDoctrine()->getManager();
        $repo = $m->getRepository('AppBundle:Producto');
        $Producto = $repo->find($id);
        $form = $this->createForm(ProductoType::class, $Producto);
        $form->handleRequest($request);


        if($form->isValid()){
            $m->flush();
            return $this->redirectToRoute('app_index_index');
        }


        return $this->render(':productosTemplates:formulario.html.twig',
            [
                'form' => $form->createView(),
                'action' => $this->generateUrl('app_producto_doUpdate', ['id' => $id]),
            ]);
    }


    /**
     * @Route(path="/remove/{id}", name="app_producto_remove")
     * @Security("has_role('ROLE_USER')")
     */

    public function removeAction($id)
    {
        $m = $this->getDoctrine()->getManager();
        $repo = $m->getRepository('AppBundle:Producto');

        $Producto = $repo->find($id);
        $m->remove($Producto);
        $m->flush();

        $this->addFlash('messages', 'Producto borrado');

        return $this->redirectToRoute('app_index_index');

    }

    //---------------------Comentarios-------------------------

    /**
     * @Route(path="/add/{id}", name="app_comentario_add")
     * @return \Symfony\Component\HttpFoundation\Response
     * @Security("has_role('ROLE_USER')")
     *
     */
    public function addAction($id)
    {
        $Comentario = new Comentario();
        $form = $this->createForm(ComentarioType::class, $Comentario);
        return $this->render(':comentarios:update.html.twig',
            [
                'form'      => $form->createView(),
                'action'    => $this->generateUrl('app_comentario_doAdd', ['id' => $id])
            ]
        );


    }


    /**
     * @Route (path="/doAdd/{id}", name="app_comentario_doAdd")
     * @Security("has_role('ROLE_USER')")
     */
    public function doAddAction(Request $request, Producto $id)
    {
        $m = $this->getDoctrine()->getManager();
        $r = $m->getRepository('AppBundle:Producto');
        $producto = $r->find($id);


        $Comentario = new Comentario();
        $form = $this->createForm(ComentarioType::class, $Comentario);


        $Comentario->setProducto($producto);

        $form->handleRequest($request);

        if($form->isValid())
        {

            $user = $this->getUser();
            $Comentario->setUsuario($user);
            $m = $this->getDoctrine()->getManager();
            $m->persist($Comentario);
            $m->flush();

            return $this->redirectToRoute('app_index_index');
        }

        return $this->render(':comentarios:update.html.twig',
            [
                'form' => $form->createView(),
                'action' => $this->generateUrl('app_comentario_doAdd', ['id' => $id])
            ]);
    }

    /**
     * @Route(path="/updatecomentario/{id}", name="app_comentario_update")
     * @Security("has_role('ROLE_USER')")
     */
    public function updateeAction($id)
    {
        $m = $this->getDoctrine()->getManager();
        $repositorio = $m->getRepository('AppBundle:Comentario');
        $Comentario = $repositorio->find($id);
        $form = $this->createForm(ComentarioType::class, $Comentario);
        return $this->render(':comentarios:update.html.twig',
            [
                'form' => $form->CreateView(),
                'action' => $this->generateUrl('app_comentario_doUpdate', ['id' => $id]),
            ]);
    }

    /**
     * @Route(path="/doUpdatecomentario/{id}", name="app_comentario_doUpdate")
     * @Security("has_role('ROLE_USER')")
     */

    public function doUpdatee($id, Request $request)
    {
        $m = $this->getDoctrine()->getManager();
        $repo = $m->getRepository('AppBundle:Comentario');
        $Comentario = $repo->find($id);
        $form = $this->createForm(ComentarioType::class, $Comentario);
        $form->handleRequest($request);


        if($form->isValid()){
            $m->flush();
            return $this->redirectToRoute('app_index_index');
        }


        return $this->render(':comentarios:update.html.twig',
            [
                'form' => $form->createView(),
                'action' => $this->generateUrl('app_comentario_doUpdate', ['id' => $id]),
            ]);
    }


    /**
     * @Route(path="/removecomentario/{id}", name="app_comentario_remove")
     * @Security("has_role('ROLE_USER')")
     */

    public function removeeAction($id)
    {
        $m = $this->getDoctrine()->getManager();
        $repo = $m->getRepository('AppBundle:Comentario');

        $Comentario = $repo->find($id);
        $m->remove($Comentario);
        $m->flush();

        $this->addFlash('messages', 'Comentario borrado');

        return $this->redirectToRoute('app_index_index');

    }
}