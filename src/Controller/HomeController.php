<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\LivreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(LivreRepository $livreRepository, CategoryRepository $categoryRepository): Response
    {
 
        $livres = $livreRepository->findAll();
        $categories = $categoryRepository->findAll();

        return $this->render('home/index.html.twig', [
            'livres' => $livres,
            'categories' => $categories, 
        ]);
    }
}
