<?php
/**
 * Created by PhpStorm.
 * User: Alvaro
 * Date: 23/05/2017
 * Time: 19:47
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Bicicleta;
use AppBundle\Form\BicicletaType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class BicicletaController extends Controller
{
    /**
     * @Route(path="/", name="app_bicicleta_listado")
     *
     */
    public function indiceAction(Request $request)
    {
        $m = $this->getDoctrine()->getManager();
        $repo = $m->getRepository('AppBundle:Bicicleta');


        $m->flush();
        $queryBicicletas = $repo->findAll();



        $paginator = $this->get('knp_paginator');
        $bicicletas = $paginator->paginate(
            $queryBicicletas,
            $request->query->getInt('page', 1),
            Bicicleta::PAGINATION_ITEMS,
            [
                'wrap-queries' => true, // https://github.com/KnpLabs/knp-components/blob/master/doc/pager/config.md
            ]
        );
        return $this->render(':bicicletas:listado.html.twig',

            [
                'bicicletas' => $bicicletas
            ]);


    }


    /**
     * @Route(path="/insert", name="app_bicicleta_insert")
     * @return \Symfony\Component\HttpFoundation\Response
     * @Security("has_role('ROLE_USER')")
     */
    public function insertAction()
    {
        $Bicicleta = new Bicicleta();
        $form = $this->createForm(BicicletaType::class, $Bicicleta);
        return $this->render(':bicicletas:formulario.html.twig',
            [
                'form'      => $form->createView(),
                'action'    => $this->generateUrl('app_bicicleta_doInsert')
            ]
        );


    }

    /**
     * @Route (path="/doInsert", name="app_bicicleta_doInsert")
     * @Security("has_role('ROLE_USER')")
     */
    public function doInsertAction(Request $request)
    {
        $Bicicleta = new Bicicleta();
        $form = $this->createForm(BicicletaType::class, $Bicicleta);

        $form->handleRequest($request);

        if($form->isValid())
        {

            $m = $this->getDoctrine()->getManager();
            $m->persist($Bicicleta);
            $m->flush();

            return $this->redirectToRoute('app_bicicleta_listado');
        }

        return $this->render(':bicicletas:formulario.html.twig',
            [
                'form' => $form->createView(),
                'action' => $this->generateUrl('app_bicicleta_doInsert')
            ]);
    }

    /**
     * @Route(path="/update/{id}", name="app_bicicleta_update")
     * @Security("has_role('ROLE_USER')")
     */
    public function updateAction($id)
    {
        $m = $this->getDoctrine()->getManager();
        $repositorio = $m->getRepository('AppBundle:Bicicleta');
        $Bicicleta = $repositorio->find($id);
        $form = $this->createForm(BicicletaType::class, $Bicicleta);
        return $this->render(':bicicletas:formulario.html.twig',
            [
                'form' => $form->CreateView(),
                'action' => $this->generateUrl('app_bicicleta_doUpdate', ['id' => $id]),
            ]);
    }

    /**
     * @Route(path="/doUpdate/{id}", name="app_bicicleta_doUpdate")
     * @Security("has_role('ROLE_USER')")
     */

    public function doUpdate($id, Request $request)
    {
        $m = $this->getDoctrine()->getManager();
        $repo = $m->getRepository('AppBundle:Bicicleta');
        $Bicicleta = $repo->find($id);
        $form = $this->createForm(BicicletaType::class, $Bicicleta);
        $form->handleRequest($request);


        if($form->isValid()){
            $m->flush();
            return $this->redirectToRoute('app_bicicleta_listado');
        }


        return $this->render(':bicicletas:formulario.html.twig',
            [
                'form' => $form->createView(),
                'action' => $this->generateUrl('app_bicicleta_doUpdate', ['id' => $id]),
            ]);
    }


    /**
     * @Route(path="/remove/{id}", name="app_bicicleta_remove")
     * @Security("has_role('ROLE_USER')")
     */

    public function removeAction($id)
    {
        $m = $this->getDoctrine()->getManager();
        $repo = $m->getRepository('AppBundle:Bicicleta');

        $Bicicleta = $repo->find($id);
        $m->remove($Bicicleta);
        $m->flush();

        $this->addFlash('messages', 'Bicicleta borrada');

        return $this->redirectToRoute('app_bicicleta_listado');

    }
}