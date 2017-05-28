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
    public function indiceAction(Request $request)
    {
        $m = $this->getDoctrine()->getManager();
        $repo = $m->getRepository('AppBundle:Producto');


        $m->flush();
        $queryProductos = $repo->findAll();



        $paginator = $this->get('knp_paginator');
        $productos = $paginator->paginate(
            $queryProductos,
            $request->query->getInt('page', 1),
            Producto::PAGINATION_ITEMS,
            [
                'wrap-queries' => true, // https://github.com/KnpLabs/knp-components/blob/master/doc/pager/config.md
            ]
        );
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

            return $this->redirectToRoute('app_producto_indice');
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
            return $this->redirectToRoute('app_producto_indice');
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

        return $this->redirectToRoute('app_producto_indice');

    }



    //---------------------------------Buscador---------------------------------

    /**
     * @Route("/buscar", name="app_producto_buscar")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function searchAction(Request $request)
    {
        $busqueda = $_POST['busqueda'];

        return $this->redirectToRoute('app_textoTitulo_show', ['palabra' => $busqueda]);

    }


    /**
     * @Route("/textoPorTitulo/{palabra}", name="app_textoTitulo_show")
     * @return \Symfony\Component\HttpFoundation\Response
     * @Security("has_role('ROLE_USER')")
     */
    public function textoPalabraAction($palabra, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $productos =$em->getRepository('AppBundle:Producto')->buscarTitulo($palabra);
        return $this->render(':busqueda:busqueda.html.twig',
            [
                'productos' => $productos,
            ]
        );
    }


}