<?php

namespace App\Controller;

use App\Entity\Livre;
use App\Form\LivreType;
use App\Repository\CategoryRepository;
use App\Repository\LivreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use \Gumlet\ImageResize;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/livre')]
final class LivreController extends AbstractController
{

    // route SEARCH 
    #[Route('/searchlivre', name: 'search_livre', methods: ['GET'])]
    public function searchLivre(Request $request, LivreRepository $livreRepository): JsonResponse
    {
        $champ = $request->query->get('champ');
        $valeur = $request->query->get('valeur');

        if ($champ && $valeur) {
            $result = $livreRepository->searchBy($champ, $valeur);

            $resultArray = [];
            foreach ($result as $livre) {
                $resultArray[] = [
                    'title' => $livre->getTitle(),
                    'auteur' => $livre->getAuteur(),
                ];
            }

            return new JsonResponse($resultArray);
        }
    }


    // Filter by category
    #[Route('/livres', name: 'app_livre_index', methods: ['GET'])]
    public function index(Request $request, LivreRepository $livreRepository, CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();
        $categoryId = $request->query->get('category');

        // findByCategory 
        $livres = $livreRepository->findByCategory($categoryId);

        return $this->render('livre/index.html.twig', [
            'livres' => $livres,
            'categories' => $categories,
        ]);
    }


    #[Route('/new', name: 'app_livre_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UserInterface $user): Response
    {
        $livre = new Livre();
        $form = $this->createForm(LivreType::class, $livre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $livre->setUser($user);

            $entityManager->persist($livre);

            // Image upload
            $file = $form['cover']->getData();
            if ($file) {
                $entityManager->flush();

                $entityId = $livre->getId();
                $newFilename = 'livre_' . $entityId . '.' . $file->guessExtension(); // add entity's id to file name

                // Move uploaded file to 'upload_directory'
                try {
                    $file->move(
                        $this->getParameter('upload_directory'),
                        $newFilename
                    );
                    $livre->setCover($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Le téléchargement a échoué, veuillez réessayer.');
                }
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('livre/new.html.twig', [
            'livre' => $livre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_livre_show', methods: ['GET'])]
    public function show(Livre $livre): Response
    {
        return $this->render('livre/show.html.twig', [
            'livre' => $livre,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_livre_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Livre $livre, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LivreType::class, $livre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Check if a new cover file was uploaded
            $newImage = $form['cover']->getData();

            if ($newImage instanceof UploadedFile) {
                $originalFilename = pathinfo($newImage->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename . '-' . uniqid() . '.' . $newImage->guessExtension();

                try {
                    $newImage->move(
                        $this->getParameter('upload_directory'),
                        $newFilename
                    );

                    $livre->setCover($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Le téléchargement a échoué, veuillez réessayer.');
                }
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_livre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('livre/edit.html.twig', [
            'livre' => $livre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_livre_delete', methods: ['POST'])]
    public function delete(Request $request, Livre $livre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $livre->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($livre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_livre_index', [], Response::HTTP_SEE_OTHER);
    }
}
