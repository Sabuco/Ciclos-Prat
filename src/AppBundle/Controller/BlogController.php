<?php
/**
 * Created by PhpStorm.
 * User: Alvaro
 * Date: 26/05/2017
 * Time: 18:37
 */

namespace AppBundle\Controller;


use AppBundle\Form\BlogType;
use AppBundle\Form\ComentarioType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Comentario;
use AppBundle\Entity\Blog;
use AppBundle\Form\ProductoType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class BlogController extends Controller
{

    /**
     * @Route(path="/", name="app_blog_indice")
     *
     */
    public function indiceAction(Request $request)
    {
        $m = $this->getDoctrine()->getManager();
        $repo = $m->getRepository('AppBundle:Blog');


        $m->flush();
        $queryBlog = $repo->findAll();



        $paginator = $this->get('knp_paginator');
        $blogs = $paginator->paginate(
            $queryBlog,
            $request->query->getInt('page', 1),
            Blog::PAGINATION_ITEMS,
            [
                'wrap-queries' => true, // https://github.com/KnpLabs/knp-components/blob/master/doc/pager/config.md
            ]
        );
        return $this->render(':blog:lista.html.twig',

            [
                'blogs' => $blogs
            ]);


    }


    /**
     * @Route(path="/insertPost", name="app_blog_insert")
     * @return \Symfony\Component\HttpFoundation\Response
     * @Security("has_role('ROLE_USER')")
     */
    public function insertAction()
    {
        $Blog = new Blog();
        $form = $this->createForm(BlogType::class, $Blog);
        return $this->render(':blog:formulario.html.twig',
            [
                'form'      => $form->createView(),
                'action'    => $this->generateUrl('app_blog_doInsert')
            ]
        );


    }

    /**
     * @Route (path="/doInsertPost", name="app_blog_doInsert")
     * @Security("has_role('ROLE_USER')")
     */
    public function doInsertAction(Request $request)
    {
        $Blog = new Blog();
        $form = $this->createForm(BlogType::class, $Blog);

        $form->handleRequest($request);

        if($form->isValid())
        {
            $user = $this->getUser();
            $Blog->setClients($user);
            $m = $this->getDoctrine()->getManager();
            $m->persist($Blog);
            $m->flush();

            return $this->redirectToRoute('app_blog_indice');
        }

        return $this->render(':blog:formulario.html.twig',
            [
                'form' => $form->createView(),
                'action' => $this->generateUrl('app_blog_doInsert')
            ]);
    }

    /**
     * @Route(path="/updateBlog/{id}", name="app_blog_update")
     * @Security("has_role('ROLE_USER')")
     */
    public function updateAction($id)
    {
        $m = $this->getDoctrine()->getManager();
        $repositorio = $m->getRepository('AppBundle:Blog');
        $Blog = $repositorio->find($id);
        $form = $this->createForm(BlogType::class, $Blog);
        return $this->render(':blog:formulario.html.twig',
            [
                'form' => $form->CreateView(),
                'action' => $this->generateUrl('app_blog_doUpdate', ['id' => $id]),
            ]);
    }

    /**
     * @Route(path="/doUpdateBlog/{id}", name="app_blog_doUpdate")
     * @Security("has_role('ROLE_USER')")
     */

    public function doUpdate($id, Request $request)
    {
        $m = $this->getDoctrine()->getManager();
        $repo = $m->getRepository('AppBundle:Blog');
        $Blog = $repo->find($id);
        $form = $this->createForm(BlogType::class, $Blog);
        $form->handleRequest($request);


        if($form->isValid()){
            $m->flush();
            return $this->redirectToRoute('app_blog_indice');
        }


        return $this->render(':blog:formulario.html.twig',
            [
                'form' => $form->createView(),
                'action' => $this->generateUrl('app_blog_doUpdate', ['id' => $id]),
            ]);
    }


    /**
     * @Route(path="/removeBlog/{id}", name="app_blog_remove")
     * @Security("has_role('ROLE_USER')")
     */

    public function removeAction($id)
    {
        $m = $this->getDoctrine()->getManager();
        $repo = $m->getRepository('AppBundle:Blog');

        $Blog = $repo->find($id);
        $m->remove($Blog);
        $m->flush();

        $this->addFlash('messages', 'Blog borrado');

        return $this->redirectToRoute('app_blog_indice');

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
    public function doAddAction(Request $request, Blog $id)
    {
        $m = $this->getDoctrine()->getManager();
        $r = $m->getRepository('AppBundle:Blog');
        $blog = $r->find($id);


        $Comentario = new Comentario();
        $form = $this->createForm(ComentarioType::class, $Comentario);


        $Comentario->setBlog($blog);

        $form->handleRequest($request);

        if($form->isValid())
        {

            $user = $this->getUser();
            $Comentario->setUsuario($user);
            $m = $this->getDoctrine()->getManager();
            $m->persist($Comentario);
            $m->flush();

            return $this->redirectToRoute('app_blog_indice');
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
            return $this->redirectToRoute('app_blog_indice');
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

        return $this->redirectToRoute('app_blog_indice');

    }
}