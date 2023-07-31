<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Utilisateur;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin/user/new", name="admin_user_new", methods={"GET", "POST"})
     */
    public function newUser(Request $request): Response
    {
        $user = new Utilisateur();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Utilisateur enregistré avec succès.');

            return $this->redirectToRoute('admin_user_list');
        }

        return $this->render('admin/user/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/user/edit/{id}", name="admin_user_edit", methods={"GET", "POST"})
     */
    public function editUser(Request $request, Utilisateur $user): Response
    {
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Utilisateur mis à jour avec succès.');

            return $this->redirectToRoute('admin_user_list');
        }

        return $this->render('admin/user/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/user/delete/{id}", name="admin_user_delete", methods={"DELETE"})
     */
    public function deleteUser(Request $request, Utilisateur $user): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();

            $this->addFlash('success', 'Utilisateur supprimé avec succès.');
        }

        return $this->redirectToRoute('admin_user_list');
    }

    /**
     * @Route("/admin/user/list", name="admin_user_list", methods={"GET"})
     */
    public function userList(): Response
    {
        $users = $this->getDoctrine()->getRepository(Utilisateur::class)->findAll();

        return $this->render('admin/user/list.html.twig', [
            'users' => $users,
        ]);
    }
}
