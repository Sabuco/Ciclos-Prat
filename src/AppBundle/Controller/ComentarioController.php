<?php
/**
 * Created by PhpStorm.
 * User: Alvaro
 * Date: 18/04/2017
 * Time: 19:20
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Comentario;
use AppBundle\Form\ComentarioType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ComentarioController extends Controller
{
    /**
     * @Route(path="/", name="app_productos_indice")
     *
     */
    public function indiceAction()
    {

        $m = $this->getDoctrine()->getManager();
        $repo = $m->getRepository('AppBundle:Comentario');

        $m->flush();
        $comentarios = $repo->findAll();
        return $this->render(':productosTemplates:indice.html.twig', [
            'comentarios' => $comentarios
        ]);
    }

    /**
     * @Route(path="/add", name="app_comentario_add")
     * @return \Symfony\Component\HttpFoundation\Response
     *
     */
    public function addAction()
    {
        $Comentario = new Comentario();
        $form = $this->createForm(ComentarioType::class, $Comentario);
        return $this->render(':comentarios:update.html.twig',
            [
                'form'      => $form->createView(),
                'action'    => $this->generateUrl('app_producto_doAdd')
            ]
        );


    }


    /**
     * @Route (path="/doAdd", name="app_comentario_doAdd")
     *
     */
    public function doAddAction(Request $request)
    {
        $Comentario = new Comentario();
        $form = $this->createForm(ComentarioType::class, $Comentario);

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

        return $this->render(':productosTemplates:indice.html.twig',
            [
                'form' => $form->createView(),
                'action' => $this->generateUrl('app_comentario_doAdd')
            ]);
    }

    /**
     * @Route(path="/updatecomentario/{id}", name="app_comentario_update")
     * @Security("has_role('ROLE_USER')")
     */
    public function updateAction($id)
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

    public function doUpdate($id, Request $request)
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

    public function removeAction($id)
    {
        $m = $this->getDoctrine()->getManager();
        $repo = $m->getRepository('AppBundle:Comentario');

        $Comentario = $repo->find($id);
        $m->remove($Comentario);
        $m->flush();

        $this->addFlash('messages', 'Producto borrado');

        return $this->redirectToRoute('app_index_index');

    }
}