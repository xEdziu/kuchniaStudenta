<?php

namespace App\Controller;

use App\Entity\enums\IngredientType;
use App\Entity\enums\IngredientLabel;
use App\Entity\Ingredient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class IngredientController extends AbstractController
{

    private EntityManagerInterface $entityManagerInterface;
    private Response $res;

    private array $hosts = [
        "http://localhost:3000",
        "https://localhost:3000",
        "https://kuchnia-studenta.webace-group.dev"
    ];

    public function __construct(EntityManagerInterface $entityManagerInterface)
    {
        $this->entityManagerInterface = $entityManagerInterface;
        $this->res = new Response();
        foreach ($this->hosts as $host) {
            $this->res->headers->set('Access-Control-Allow-Origin', $host);
        }
        $this->res->headers->set('Access-Control-Allow-Methods', 'POST');
    }

    #[Route('/api/ingredient/{id}', name: 'app_ingredient_get_one', methods: ['GET'])]
    public function getIngredientInfo(int $id): Response
    {
        $ingredient = $this->entityManagerInterface->getRepository("App:Ingredient")->findOneBy(['id' => $id]);

        if (!$ingredient) {
            $this->res->setContent(json_encode([
                'error' => 'Ingredient not found',
                'ingredient' => null,
            ]));
            return $this->res;
        }

        $this->res->setContent(json_encode([
            'error' => null,
            'ingredient' => $ingredient->toArray(),
        ]));
        return $this->res;
    }

    #[Route('/api/ingredient', name: 'app_ingredient_get_all', methods: ['GET'])]
    public function getAllIngredients(): Response
    {
        $ingredients = $this->entityManagerInterface->getRepository("App:Ingredient")->findAll();

        $ingredientsArray = [];
        foreach ($ingredients as $ingredient) {
            $ingredientsArray[] = $ingredient->toArray();
        }

        $this->res->setContent(json_encode([
            'error' => null,
            'ingredients' => $ingredientsArray,
        ]));
        return $this->res;
    }

    #[Route('/api/ingredient/add', name: 'app_ingredient_create', methods: ['POST'])]
    public function createIngredient(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        foreach ($data as $key => $value) {
            if (empty($value)) {
                $this->res->setContent(json_encode([
                    'error' => 'All fields are required'
                ]));
                return $this->res;
            }
        }

        $origin = $request->headers->get('origin');
        if (!in_array($origin, $this->hosts)) {
            $this->res->setContent(json_encode([
                'error' => 'Incorrect origin',
                'code' => 403
            ]));
            return $this->res;
        }

        $name = $data['name'];
        $type = $data['type'];

        if (!enum_exists(IngredientType::class, $type)) {
            $this->res->setContent(json_encode([
                'error' => 'Invalid ingredient type'
            ]));
            return $this->res;
        }

        $calories_per_100gram = $data['calories_per_100gram'];
        $protein_per_100gram = $data['protein_per_100gram'];
        $carbohydrates_per_100gram = $data['carbohydrates_per_100gram'];
        $fat_per_100gram = $data['fat_per_100gram'];
        $price = $data['price'];
        $measure_type = $data['measure_type'];
        $quantity = $data['quantity'];
        $label = $data['label'];

        if (!enum_exists(IngredientLabel::class, $label)) {
            $this->res->setContent(json_encode([
                'error' => 'Invalid ingredient label'
            ]));
            return $this->res;
        }

        $stores_ids = $data['stores_ids'];

        $ingredient = new Ingredient();
        $ingredient->setName($name);
        $ingredient->setType($type);
        $ingredient->setCaloriesPer100gram($calories_per_100gram);
        $ingredient->setProteinPer100gram($protein_per_100gram);
        $ingredient->setCarbohydratesPer100gram($carbohydrates_per_100gram);
        $ingredient->setFatPer100gram($fat_per_100gram);
        $ingredient->setPrice($price);
        $ingredient->setMeasureType($measure_type);
        $ingredient->setQuantity($quantity);
        $ingredient->setLabel($label);
        foreach ($stores_ids as $store_id) {
            $store = $this->entityManagerInterface->getRepository("App:Store")->findOneBy(['id' => $store_id]);
            if ($store) {
                $ingredient->addStoreId($store);
            }
        }
        $this->entityManagerInterface->persist($ingredient);
        $this->entityManagerInterface->flush();

        $this->res->setContent(json_encode([
            'error' => null,
            'ingredient' => $ingredient->toArray(),
        ]));
        return $this->res;
    }

    #[Route('/api/ingredient/{id}/edit', name: 'app_ingredient_edit', methods: ['POST'])]
    public function updateIngredient(int $id, Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $ingredient = $this->entityManagerInterface->getRepository("App:Ingredient")->findOneBy(['id' => $id]);

        if (!$ingredient) {
            $this->res->setContent(json_encode([
                'error' => 'Ingredient not found'
            ]));
            return $this->res;
        }

        $origin = $request->headers->get('origin');
        if (!in_array($origin, $this->hosts)) {
            $this->res->setContent(json_encode([
                'error' => 'Incorrect origin',
                'code' => 403
            ]));
            return $this->res;
        }

        foreach ($data as $key => $value) {
            if (empty($value)) {
                $this->res->setContent(json_encode([
                    'error' => 'All fields are required'
                ]));
                return $this->res;
            }
        }

        $name = $data['name'];
        $type = $data['type'];

        if (!enum_exists(IngredientType::class, $type)) {
            $this->res->setContent(json_encode([
                'error' => 'Invalid ingredient type'
            ]));
            return $this->res;
        }

        $calories_per_100gram = $data['calories_per_100gram'];
        $protein_per_100gram = $data['protein_per_100gram'];
        $carbohydrates_per_100gram = $data['carbohydrates_per_100gram'];
        $fat_per_100gram = $data['fat_per_100gram'];
        $price = $data['price'];
        $measure_type = $data['measure_type'];
        $quantity = $data['quantity'];
        $label = $data['label'];

        if (!enum_exists(IngredientLabel::class, $label)) {
            $this->res->setContent(json_encode([
                'error' => 'Invalid ingredient label'
            ]));
            return $this->res;
        }

        $stores_ids = $data['stores_ids'];

        $ingredient->setName($name);
        $ingredient->setType($type);
        $ingredient->setCaloriesPer100gram($calories_per_100gram);
        $ingredient->setProteinPer100gram($protein_per_100gram);
        $ingredient->setCarbohydratesPer100gram($carbohydrates_per_100gram);
        $ingredient->setFatPer100gram($fat_per_100gram);
        $ingredient->setPrice($price);
        $ingredient->setMeasureType($measure_type);
        $ingredient->setQuantity($quantity);
        $ingredient->setLabel($label);
        $ingredient->clearStoresIds();
        foreach ($stores_ids as $store_id) {
            $store = $this->entityManagerInterface->getRepository("App:Store")->findOneBy(['id' => $store_id]);
            if ($store) {
                $ingredient->addStoreId($store);
            }
        }
        $this->entityManagerInterface->persist($ingredient);
        $this->entityManagerInterface->flush();

        $this->res->setContent(json_encode([
            'error' => null,
            'ingredient' => $ingredient->toArray(),
        ]));
        return $this->res;
    }

    #[Route('/api/ingredient/{id}/delete', name: 'app_ingredient_delete', methods: ['POST'])]
    public function deleteIngredient(int $id, Request $request): Response
    {
        $ingredient = $this->entityManagerInterface->getRepository("App:Ingredient")->findOneBy(['id' => $id]);

        if (!$ingredient) {
            $this->res->setContent(json_encode([
                'error' => 'Ingredient not found'
            ]));
            return $this->res;
        }

        $origin = $request->headers->get('origin');
        if (!in_array($origin, $this->hosts)) {
            $this->res->setContent(json_encode([
                'error' => 'Incorrect origin',
                'code' => 403
            ]));
            return $this->res;
        }

        $this->entityManagerInterface->remove($ingredient);
        $this->entityManagerInterface->flush();

        $this->res->setContent(json_encode([
            'error' => null,
        ]));
        return $this->res;
    }
}
