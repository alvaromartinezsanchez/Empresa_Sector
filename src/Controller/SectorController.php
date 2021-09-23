<?php

namespace App\Controller;

use App\Entity\Sector;
use App\Form\SectorType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/sector")
 */
class SectorController extends AbstractController
{
    /**
     * @Route("/{error}", name="sector_index", methods={"GET"}, requirements={"error"="true|false|1|0"})
     */
    public function index($error = false,EntityManagerInterface $em, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $em->getRepository(Sector::class)->findAllSectores();
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10/*limit per page*/
        );

        return $this->render('sector/index.html.twig', [
            'pagination' => $pagination,
            'error' => $error
        ]);
    }

    /**
     * @Route("/new", name="sector_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $sector = new Sector();
        $form = $this->createForm(SectorType::class, $sector);
        $form->handleRequest($request);
        $message = false;
        if ($form->isSubmitted() && $form->isValid()) {

            if($this->exist($form->get('nombre')->getData())){

                $message = true;

                return $this->renderForm('sector/new.html.twig', [
                    'sector' => $sector,
                    'form' => $form,
                    'message' => $message,
                ]);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($sector);
            $entityManager->flush();

            return $this->redirectToRoute('sector_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sector/new.html.twig', [
            'sector' => $sector,
            'form' => $form,
            'message' => $message,
        ]);
    }

    /**
     * @Route("/{id}", name="sector_show", methods={"GET"})
     */
    
    public function show(Sector $sector): Response
    {
        return $this->render('sector/show.html.twig', [
            'sector' => $sector,
        ]);
    }
    
    /**
     * @Route("/{id}/edit", name="sector_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Sector $sector): Response
    {
        $form = $this->createForm(SectorType::class, $sector);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('sector_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sector/edit.html.twig', [
            'sector' => $sector,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="sector_delete", methods={"POST"})
     */
    public function delete(Request $request, Sector $sector): Response
    {
        $error = false;
        try {
            if ($this->isCsrfTokenValid('delete'.$sector->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($sector);
            $entityManager->flush();
        }

        return $this->redirectToRoute('sector_index', ['error' => $error], Response::HTTP_SEE_OTHER);

        } catch (\Throwable $th) {
            $error = true;
            return $this->redirectToRoute('sector_index', ['error' => $error], Response::HTTP_SEE_OTHER);
        }
        
    }

    public function getSectors(){
        $sectors = $this->getDoctrine()
            ->getRepository(Sector::class)
            ->findAll();
        
        return $sectors;
    }

    public function exist(string $sector_name){
        $sectors = $this->getSectors();
        foreach ($sectors as $key => $sector) {
            if($sector->getNombre() == $sector_name){
                return true;
            }
        }

        return false;
    }
}
