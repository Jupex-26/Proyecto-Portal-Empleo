<?php
namespace app\repositories;
interface RepoMethods {
    /**
     * Devuelve todos los objetos del tipo gestionado.
     * @return array<object> Array de entidades
     */
    public function findAll(): array;

    /**
     * Busca una entidad por su ID.
     * @param int $id
     * @return object|null Devuelve la entidad o null si no existe
     */
    public function findById(int $id): ?object;

    /**
     * Guarda una nueva entidad en la base de datos.
     * @param object $entity
     * @return int|null Devuelve el ID de la entidad guardada o null si falla
     */
    public function save(object $entity): ?int;

    /**
     * Actualiza una entidad existente.
     * @param object $entity
     * @return bool true si se actualizó correctamente
     */
    public function update(object $entity): bool;

    /**
     * Elimina una entidad por su ID.
     * @param int $id
     * @return bool true si se eliminó correctamente
     */
    public function delete(int $id): bool;
}

?>