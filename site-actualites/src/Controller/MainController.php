<?php

namespace App\Controller;

use App\Entity\Contacts;
use App\Form\ContactsType;
use App\Repository\ArticlesRepository;
use App\Repository\ContactsRepository;
use App\Repository\CategoriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    #[Route('/', name: 'home', methods: ['GET', 'POST'])]
    public function index(Request $request, EntityManagerInterface $entityManager, ArticlesRepository $articlesRepository, CategoriesRepository $categoriesRepository): Response
    {
        $contact = new Contacts();
        $form = $this->createForm(ContactsType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($contact);
            $entityManager->flush();

            return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('main/index.html.twig', [
            'contact' => $contact,
            'form' => $form,
            'articles' => $articlesRepository->findAll(),
            'categories' => $categoriesRepository->findAll(),

        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin', name: 'admin')]
    public function admin(ArticlesRepository $articlesRepository, CategoriesRepository $categoriesRepository, ContactsRepository $contactsRepository): Response
    {
        return $this->render('main/admin.html.twig', [
            'articles' => $articlesRepository->findAll(),
            'categories' => $categoriesRepository->findAll(),
            'contacts' => $contactsRepository->findAll(),
        ]);
    }

    #[IsGranted('ROLE_EDITOR')]
    #[Route('/admin-rédaction', name: 'redaction')]
    public function adminRedaction(): Response
    {
        return $this->render('main/admin-rédaction.html.twig', [
            'controller_name' => 'MainController',
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
