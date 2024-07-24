<?php

namespace App\Controller;

use App\Entity\Contacts;
use App\Form\ContactsType;
use App\Repository\UserRepository;
use App\Repository\ArticlesRepository;
use App\Repository\ContactsRepository;
use App\Repository\CategoriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    #[Route('/', name: 'home', methods: ['GET', 'POST'])]
    public function index(Request $request, EntityManagerInterface $entityManager, ArticlesRepository $articlesRepository, CategoriesRepository $categoriesRepository): Response
    {
        $contacts = new Contacts();
        $form = $this->createForm(ContactsType::class, $contacts);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($contacts);
            $entityManager->flush();

            return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
        }

        // Getting all articles from database
        $allArticles = $articlesRepository->findAll();

        // Random shuffle of main article
        $featuredArticles = $allArticles;
        shuffle($featuredArticles);
        $selectedArticle = reset($featuredArticles);

        return $this->renderForm('main/index.html.twig', [
            'contacts' => $contacts,
            'form' => $form,
            'selectedArticle' => $selectedArticle,
            'categories' => $categoriesRepository->findAll(),
            'articles' => $allArticles,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin', name: 'admin')]
    public function admin(UserRepository $userRepository, ArticlesRepository $articlesRepository, CategoriesRepository $categoriesRepository, ContactsRepository $contactsRepository): Response
    {
        return $this->render('main/admin.html.twig', [
            'users' => $userRepository->findAll(),
            'articles' => $articlesRepository->findAll(),
            'categories' => $categoriesRepository->findAll(),
            'contacts' => $contactsRepository->findAll(),
        ]);
    }

    #[IsGranted('ROLE_EDITOR')]
    #[Route('/espace-redacteurs', name: 'redaction')]
    public function redaction(UserInterface $user, ArticlesRepository $articlesRepository, CategoriesRepository $categoriesRepository): Response
    {
        // Getting articles only from the connected user
        $userArticles = $articlesRepository->findBy(['user' => $user]);

        return $this->render('main/espace-redacteurs.html.twig', [
            'articles' => $userArticles,
            'categories' => $categoriesRepository->findAll(),
        ]);
    }

    #[Route('/politique-de-confidentialite', name: 'confid')]
    public function confid(): Response
    {
        return $this->render('main/confid.html.twig');
    }

    #[Route('/mentions-legales', name: 'legals')]
    public function legals(): Response
    {
        return $this->render('main/legals.html.twig');
    }
}
