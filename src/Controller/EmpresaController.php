<?php

namespace App\Controller;

use App\Entity\Empresa;
use App\Entity\Sector;
use App\Form\EmpresaType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
/**
 * @Route("/empresa")
 */
class EmpresaController extends AbstractController
{
    /**
     * @Route("/", name="empresa_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $em, PaginatorInterface $paginator, Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $usuario = $this->getUser();
        $role = $usuario->getRole();
        $sector_user = $usuario->getIdSector();
        $sector_user = $sector_user[0]->getId();

        $sectors = $this->getDoctrine()
            ->getRepository(Sector::class)
            ->findAll();

        if($role == 'ROLE_ADMIN'){
           $dql   = "SELECT e FROM App:Empresa e"; 
        }else{
            $dql = "SELECT e FROM App:Empresa e WHERE e.sector = $sector_user";
        }

        $query = $em->createQuery($dql);
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('empresa/index.html.twig', [
            'pagination' => $pagination,
            'role' => $role,
            'sectors' => $sectors
        ]);
        
    }

    /**
     * @Route("/filter", name="empresa_filter", methods={"GET","POST"})
     */
    public function filter(EntityManagerInterface $em, PaginatorInterface $paginator,Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $usuario = $this->getUser();
        $role = $usuario->getRole();
        $sector_user = $usuario->getIdSector();
        $sector_user = $sector_user[0]->getId();

        $filtro = $request->get('filtro');

        $sectors = $this->getDoctrine()
            ->getRepository(Sector::class)
            ->findAll();
        

        if($role == 'ROLE_ADMIN'){
           $dql   = "SELECT e FROM App:Empresa e WHERE e.nombre LIKE '%".$filtro."%'"; 
        }else{
            $dql = "SELECT e FROM App:Empresa e WHERE e.sector = $sector_user and e.nombre LIKE '%".$filtro."%'";
        }

        $query = $em->createQuery($dql);
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('empresa/index.html.twig', [
            'pagination' => $pagination,
            'role' => $role,
            'sectors' => $sectors
        ]);
    }

    /**
     * @Route("/filterSector", name="empresa_filter_sector", methods={"GET","POST"})
     */
    public function filterSector(EntityManagerInterface $em, PaginatorInterface $paginator,Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $usuario = $this->getUser();
        $role = $usuario->getRole();
        $filtro = $request->get('filtro');
        $filtro = $this->getDoctrine()->getRepository(Sector::class)->findBy(['nombre' => $filtro]);
        $filtro = $filtro[0]->getId();

        $sectors = $this->getDoctrine()
            ->getRepository(Sector::class)
            ->findAll();
        
        $dql = "SELECT e FROM App:Empresa e WHERE e.sector = $filtro ";
        

        $query = $em->createQuery($dql);
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('empresa/index.html.twig', [
            'pagination' => $pagination,
            'role' => $role,
            'sectors' => $sectors,
            'filtro' => $filtro
        ]);
    }

    /**
     * @Route("/new", name="empresa_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $usuario = $this->getUser();
        $role = $usuario->getRole();
        if($role != 'ROLE_ADMIN'){
            return $this->redirectToRoute('empresa_index', [], Response::HTTP_SEE_OTHER);
        }

        $empresa = new Empresa();
        $form = $this->createForm(EmpresaType::class, $empresa);
        $form->handleRequest($request);

        $sectores = $this->getDoctrine()
            ->getRepository(Sector::class)
            ->findAll();

        if ($form->isSubmitted() && $form->isValid()) {
            $sector = $request->get('sector_empresa');
            $sector = $this->getDoctrine()->getRepository(Sector::class)->findBy(['nombre' => $sector]);
            $empresa->setSector($sector[0]);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($empresa);
            $entityManager->flush();

            return $this->redirectToRoute('empresa_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('empresa/new.html.twig', [
            'empresa' => $empresa,
            'form' => $form,
            'sectores' => $sectores
        ]);
    }

    /**
     * @Route("/{id}", name="empresa_show", methods={"GET"})
     */
    public function show(Empresa $empresa): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $usuario = $this->getUser();
        $role = $usuario->getRole();
        if($role != 'ROLE_ADMIN'){
            return $this->redirectToRoute('empresa_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('empresa/show.html.twig', [
            'empresa' => $empresa,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="empresa_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Empresa $empresa): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $usuario = $this->getUser();
        $role = $usuario->getRole();
        if($role != 'ROLE_ADMIN'){
            return $this->redirectToRoute('empresa_index', [], Response::HTTP_SEE_OTHER);
        }
        $form = $this->createForm(EmpresaType::class, $empresa);
        $form->handleRequest($request);

        $sectores = $this->getDoctrine()
            ->getRepository(Sector::class)
            ->findAll();

        if ($form->isSubmitted() && $form->isValid()) {
            $sector = $request->get('sector_empresa');
            $sector = $this->getDoctrine()->getRepository(Sector::class)->findBy(['nombre' => $sector]);
            $empresa->setSector($sector[0]);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('empresa_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('empresa/edit.html.twig', [
            'empresa' => $empresa,
            'form' => $form,
            'sectores' => $sectores
        ]);
    }

    /**
     * @Route("/{id}", name="empresa_delete", methods={"POST"})
     */
    public function delete(Request $request, Empresa $empresa): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $usuario = $this->getUser();
        $role = $usuario->getRole();
        if($role != 'ROLE_ADMIN'){
            return $this->redirectToRoute('empresa_index', [], Response::HTTP_SEE_OTHER);
        }
        if ($this->isCsrfTokenValid('delete'.$empresa->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($empresa);
            $entityManager->flush();
        }

        return $this->redirectToRoute('empresa_index', [], Response::HTTP_SEE_OTHER);
    }
}
