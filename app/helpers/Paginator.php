<?php
namespace app\helpers;
class Paginator {

    public static function getPages($total, $size) {
        $pages_count = $total / $size;
        if (is_float($pages_count)) {
            $pages_count = (int)$pages_count + 1;
        }
        return $pages_count;
    }

    public static function getIndex($size, $page) {
        return $size * ($page - 1);
    }

    public static function renderPagination($page, $size, $pages, $accion) {
            
        $ref_anterior = "?page=empresas&accion=$accion&pagina=" . max(1, $page - 1) . "&size=$size";
        $ref_posterior = "?page=empresas&accion=$accion&pagina=" . min($pages, $page + 1) . "&size=$size";

        
        $prevClass = $page <= 1 ? ' disabled' : '';
        $nextClass = $page >= $pages ? ' disabled' : '';

        $html = '<a class="btn' . $prevClass . '" href="' . $ref_anterior . '">Anterior</a>';
        $html .= "<p>Página $page de $pages</p>";
        $html .= '<a class="btn' . $nextClass . '" href="' . $ref_posterior . '">Siguiente</a>';
        return $html;
    }

    public static function pageBefore($page, $size) {
        $ref_anterior = "?page=" . $page . "&size=$size";
        echo '<a style="margin-right:2em;" href="' . $ref_anterior . '"><button>Anterior</button></a>';
    }
}
?>