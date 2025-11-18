<?php
namespace app\helpers;
use app\models\Ciclo;
class Converter{
    public static function arrayToJson(array $items): array {
        $jsonStrings = [];

        foreach ($items as $item) {
            if (is_object($item) && method_exists($item, 'toJson')) {
                $jsonStrings[] = $item->toJson();
            } 
        }

        // Concatenamos todos los JSON individuales en un JSON
        return $jsonStrings;
    }


    public static function postToCiclos($postData):array {
        $ciclos = [];
        
        if (isset($postData['ciclos']) && is_array($postData['ciclos'])) {
            foreach ($postData['ciclos'] as $datos) {
                $ciclos[] = new Ciclo(
                    id: isset($datos['id']) ? (int)$datos['id'] : null,
                    nombre: $datos['nombre'] ?? ''
                );
            }
        }
        
        return $ciclos;
    }

}