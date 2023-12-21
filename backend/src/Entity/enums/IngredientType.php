<?php

// backend/src/Entity/enums/IngredientType.php

namespace App\Entity\enums;

enum IngredientType: string {

    case VEGETABLE = 'vegetable';
    case MEAT = 'meat';
    case FISH = 'fish';
    case GRAIN = 'grain';
    case DAIRY = 'dairy';
    case FRUIT = 'fruit';
    case SPICE = 'spice';
    case EGG = 'egg';
    
}

?>