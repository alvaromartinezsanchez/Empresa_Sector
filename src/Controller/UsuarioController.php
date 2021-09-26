<?php

namespace App\Controller;

use App\Entity\Sector;
use App\Entity\Usuario;
use App\Form\UsuarioType;
use App\Form\UsuarioEditType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/usuario")
 */
class UsuarioController extends AbstractController
{

    public function login(AuthenticationUtils $authenticationUtils){

        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();
        
        return $this->render('usuario/login.html.twig', array(
            'error' => $error,
            'last_username' => $lastUsername
        ));
    }

    /**
     * @Route("/", name="usuario_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $em, PaginatorInterface $paginator, Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $usuario = $this->getUser();
        $role = $usuario->getRole();
        if($role != 'ROLE_ADMIN'){
            return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
        }

        $dql   = "SELECT u FROM App:Usuario u"; 
         
        $query = $em->createQuery($dql);
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10/*limit per page*/
        );

        
        return $this->render('usuario/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/new", name="usuario_new", methods={"GET","POST"})
     */
    public function new(Request $request, UserPasswordHasherInterface $encoder): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $usuario = $this->getUser();
        $role = $usuario->getRole();
        $create = true;
        if($role != 'ROLE_ADMIN'){
            return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
        }

        $usuario = new Usuario();

        $sectores = $this->getDoctrine()
            ->getRepository(Sector::class)
            ->findAll();

        $form = $this->createForm(UsuarioType::class, $usuario);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //sector
            $sector = $request->get('sectores_usuario');
            $sector = $this->getDoctrine()->getRepository(Sector::class)->findBy(['nombre' => $sector]);
            $usuario->addIdSector($sector[0]);
            //Password
            $encoded = $encoder->hashPassword($usuario, $usuario->getPassword());
            $usuario->setPassword($encoded);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($usuario);
            $entityManager->flush();

            return $this->redirectToRoute('usuario_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('usuario/new.html.twig', [
            'usuario' => $usuario,
            'form' => $form,
            'sectores' => $sectores,
            'create' => $create
        ]);
    }

    /**
     * @Route("/{id}", name="usuario_show", methods={"GET"})
     */
    public function show(Usuario $usuario): Response
    {
        return $this->render('usuario/show.html.twig', [
            'usuario' => $usuario,
        ]);
    }

    
    public function edit(Request $request, Usuario $usuario, $changePassword, UserPasswordHasherInterface $encoder): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $role = $user->getRole();
        if($role != 'ROLE_ADMIN'){
            return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
        }

        if($changePassword){
            $form = $this->createForm(UsuarioType::class, $usuario);  
        }else{
            $form = $this->createForm(UsuarioEditType::class, $usuario);  
        }
        
        $form->handleRequest($request);

        $sector_user = $usuario->getIdSector();
        $sector_user = $sector_user[0];
        
        $sectores = $this->getDoctrine()
            ->getRepository(Sector::class)
            ->findAll();


        if ($form->isSubmitted() && $form->isValid()) {
            //Sector
            if($sector_user != null){
              $usuario->removeIdSector($sector_user);  
            }

            $sector = $request->get('sectores_usuario');
            $sector = $this->getDoctrine()->getRepository(Sector::class)->findBy(['nombre' => $sector]);
            $usuario->addIdSector($sector[0]);

            //Password
            $encoded = $encoder->hashPassword($usuario, $usuario->getPassword());
            $usuario->setPassword($encoded);

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('usuario_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('usuario/edit.html.twig', [
            'usuario' => $usuario,
            'form' => $form,
            'sectores' => $sectores,
            'sector_user' => $sector_user,
            'changePassword' => $changePassword,
        ]);
    }

    /**
     * @Route("/{id}", name="usuario_delete", methods={"POST"})
     */
    public function delete(Request $request, Usuario $usuario): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $role = $user->getRole();
        if($role != 'ROLE_ADMIN'){
            return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
        }

        if ($this->isCsrfTokenValid('delete'.$usuario->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($usuario);
            $entityManager->flush();
        }

        return $this->redirectToRoute('usuario_index', [], Response::HTTP_SEE_OTHER);
    }
}
