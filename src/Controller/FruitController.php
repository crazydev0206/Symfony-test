<?php

namespace App\Controller;

use App\Entity\Fruit;
use App\Form\FilterFruitType;
use App\Repository\FruitRepository;
use App\Repository\NutritionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

class FruitController extends AbstractController
{
    private FruitRepository $fr;
    private EntityManagerInterface $em;
    private NutritionRepository $nr;

    public function __construct(FruitRepository $fruitRepository, EntityManagerInterface $entityManagerInterface, NutritionRepository $nutritionRepository)
    {
        $this->fr = $fruitRepository;
        $this->em = $entityManagerInterface;
        $this->nr = $nutritionRepository;
        
    }
    #[Route('/', name: 'app_fruit')]
    /**
     * @param PaginatorInterface $paginator
     * @param Request $request
     */
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $name = null;
        $family = null;
        $filterForm = $this->createForm(FilterFruitType::class);
        $filterForm->handleRequest($request);
        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            $data = $filterForm->getData();
            $name = $data->getName() ?? null;
            $family = $data->getFamily() ?? null;

            $fruits= $paginator->paginate(
                $this->fr->findAllPaginatedAndFiltered($name, $family),
                $request->query->getInt('page', 1),
                10
            );
        } else {
            $fruits = $paginator->paginate(
                $this->fr->findAll(),
                $request->query->getInt('page', 1),
                10
            );
        }
        
        return $this->render('fruit/index.html.twig', [
            'fruits' => $fruits,
            'name' => $name,
            'family' => $family,
            'filterForm' => $filterForm->createView(),
        ]);
    }

    #[Route('/fruit/{id}', name: 'app_fruit_add_favorite', options: ['json'=> true])]
    public function addToFavoris($id){

        $fruit = $this->fr->find($id);

        if (!$fruit) {
            throw $this->createNotFoundException('Fruit not found');
        }

        if($fruit->getIsFavorite() === false && $this->fr->countFavorites() < 10){
            $fruit->setIsFavorite(true);
            $this->em->persist($fruit);
            $this->em->flush();
            return $this->json(['success' => true]);
        } else {
            return $this->json(['success' => true]);
        }
        
    }

    #[Route('/fruits/favoris', name: 'app_fruit_list_favoris')]
    /**
     * @param PaginatorInterface $paginator
     * @param Request $request
     */
    public function indexFavorites(Request $request, PaginatorInterface $paginator): Response
    {
        $name = null;
        $family = null;
        $sumCarbs = 0;
        $sumProtein = 0;
        $sumFat = 0;
        $sumCalories = 0;
        $sumSugar = 0;
        $nutritions = $this->nr->findNutritionFactsByFavorites();
        //dd($nutritions);
        $sumCarbs = $nutritions['totalCarbs'];
        $sumProtein = $nutritions['totalProtein'];
        $sumFat = $nutritions['totalFat'];
        $sumCalories = $nutritions['totalCalories'];
        $sumSugar = $nutritions['totalSugar'];

        $filterForm = $this->createForm(FilterFruitType::class);

        $filterForm->handleRequest($request);
        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            $data = $filterForm->getData();
            $name = $data->getName() ?? null;
            $family = $data->getFamily() ?? null;

            $fruitsFavoris = $paginator->paginate(
                $this->fr->findAllPaginatedAndFiltered($name, $family),
                $request->query->getInt('page', 1),
                10
            );
        } else {

            $fruitsFavoris = $paginator->paginate(
                $this->fr->findAllFruitsFavoris(true),
                $request->query->getInt('page', 1),
                10
            );
        }
         
        return $this->render('fruit/list_fruits_favoris.html.twig', [
            'fruitsFavoris' => $fruitsFavoris,
            'name' => $name,
            'family' => $family,
            'filterForm' => $filterForm->createView(),
            'sumCarbs' => $sumCarbs,
            'sumProtein' => $sumProtein,
            'sumFat' => $sumFat,
            'sumCalories' => $sumCalories,
            'sumSugar' => $sumSugar,
        ]);
    }

}






    

