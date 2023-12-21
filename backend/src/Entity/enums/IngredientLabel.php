<?php

// backend/src/Entity/enums/IngredientLabel.php

namespace App\Entity\enums;

enum IngredientLabel: string {

    case HEALTHY = 'healthy';
    case PROTEIN = 'protein';
    case CARB = 'carbohydrate';
    case BREAKFAST = 'breakfast';
    case LUNCH = 'lunch';
    case DINNER = 'dinner';
    case REDUCTION = 'reduction';
    case GLUTEN_FREE = 'gluten_free';
    case KETO = 'keto';
    case VEGE = 'vegetarian';
    case QUICK = 'quick';
    case FIBER = 'fiber';
    case FAT = 'fat';
    
}

?>