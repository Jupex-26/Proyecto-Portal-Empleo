<?php
namespace app\models;

use DateTime;
use Exception;

/**
 * AlumCursadoCiclo
 */
class AlumCursadoCiclo {
    private ?int $id;
    private int $alumnoId;
    private int $cicloId;
    private DateTime $fechaInicio;
    private ?DateTime $fechaFin;

    /**
     * __construct
     *
     * @param  mixed $id
     * @param  mixed $alumnoId
     * @param  mixed $cicloId
     * @param  mixed $fechaInicio
     * @param  mixed $fechaFin
     * @return void
     */
    public function __construct(
        ?int $id = null,
        int $alumnoId = 0,
        int $cicloId = 0,
        DateTime $fechaInicio = new DateTime(),
        ?DateTime $fechaFin = null
    ) {
        $this->id = $id;
        $this->alumnoId = $alumnoId;
        $this->cicloId = $cicloId;
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin = $fechaFin;
    }

    // 🔹 Getters
    public function getId(): ?int {
        return $this->id;
    }

    public function getAlumnoId(): int {
        return $this->alumnoId;
    }

    public function getCicloId(): int {
        return $this->cicloId;
    }

    public function getFechaInicio(): DateTime {
        return $this->fechaInicio;
    }

    public function getFechaFin(): ?DateTime {
        return $this->fechaFin;
    }

    // 🔹 Setters
    public function setId(int $id): void {
        $this->id = $id;
    }

    public function setAlumnoId(int $alumnoId): void {
        $this->alumnoId = $alumnoId;
    }

    public function setCicloId(int $cicloId): void {
        $this->cicloId = $cicloId;
    }

    public function setFechaInicio(DateTime $fechaInicio): void {
        $this->fechaInicio = $fechaInicio;
    }

    public function setFechaFin(?DateTime $fechaFin): void {
        $this->fechaFin = $fechaFin;
    }

    /**
     * Convierte el objeto a JSON
     *
     * @return string
     */
    public function toJson(): string {
        $data = [
            'id' => $this->getId(),
            'alumno_id' => $this->getAlumnoId(),
            'ciclo_id' => $this->getCicloId(),
            'fecha_inicio' => $this->getFechaInicio()->format('Y-m-d H:i:s'),
            'fecha_fin' => $this->getFechaFin()?->format('Y-m-d H:i:s')
        ];

        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Crea una instancia desde un array (por ejemplo, desde una fila de BD)
     *
     * @param array $row
     * @return AlumCursadoCiclo
     * @throws Exception
     */
    public static function fromArray(array $row): AlumCursadoCiclo {
        return new AlumCursadoCiclo(
            $row['id'] ?? null,
            $row['alumno_id'] ?? 0,
            $row['ciclo_id'] ?? 0,
            new DateTime($row['fecha_inicio']),
            isset($row['fecha_fin']) ? new DateTime($row['fecha_fin']) : null
        );
    }
}
?>
