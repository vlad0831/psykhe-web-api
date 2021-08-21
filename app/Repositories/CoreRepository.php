<?php

namespace App\Repositories;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class CoreRepository
{
    use ManagesCache;

    /**
     * @var Model
     */
    protected $model;

    /**
     * @param Model $model
     *
     * @return void
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @param array $values
     *
     * @return Model
     */
    public function findOneBy(array $values)
    {
        $query = $this->getQuery();

        foreach ($values as $column => $value) {
            $query->where($column, '=', $value);
        }

        return $query->first();
    }

    /**
     * @param array $values
     *
     * @return Model
     */
    public function findOneLike(array $values)
    {
        $query = $this->getQuery();

        foreach ($values as $column => $value) {
            $query->where($column, 'like', '%'.$value.'%');
        }

        return $query->first();
    }

    /**
     * @param array $values
     *
     * @return Model
     */
    public function findOneByOrFail(array $values)
    {
        $query = $this->getQuery();

        foreach ($values as $column => $value) {
            $query->where($column, '=', $value);
        }

        return $query->firstOrFail();
    }

    /**
     * @return Collection
     */
    public function findAll()
    {
        $query = $this->getQuery();

        return $query->get();
    }

    /**
     * @param array $values
     *
     * @return Collection
     */
    public function findAllBy(array $values)
    {
        $query = $this->getQuery();

        foreach ($values as $column => $value) {
            $query->where($column, '=', $value);
        }

        return $query->get();
    }

    /**
     * @param array $values
     *
     * @return Collection
     */
    public function findAllWhereIn(array $values)
    {
        $query = $this->getQuery();

        foreach ($values as $column => $value) {
            $query->whereIn($column, $value);
        }

        return $query->get();
    }

    /**
     * @param string $id
     *
     * @return Model
     */
    public function findOneById(string $id)
    {
        return $this->findOneBy(['id' => $id]);
    }

    /**
     * @param string $id
     *
     * @return Model
     */
    public function findOneByIdOrFail(string $id)
    {
        return $this->findOneByOrFail(['id' => $id]);
    }

    /**
     * @param string $field
     *
     * @return int
     */
    public function countBy($field = 'id')
    {
        return $this->getQuery()
            ->whereNotNull($field)
            ->count()
        ;
    }

    /**
     * @param array  $values
     * @param string $field
     *
     * @return int
     */
    public function countByWhere(array $values, $field = 'id')
    {
        $query = $this->getQuery();

        $query->whereNotNull($field);

        foreach ($values as $column => $value) {
            $query->where($column, $value);
        }

        return $query->count();
    }

    /**
     * @param int $year
     *
     * @return int
     */
    public function countByCreatedYear(int $year)
    {
        return $this->getQuery()
            ->whereYear('created_at', $year)
            ->count()
        ;
    }

    /**
     * @param array $values
     *
     * @return int
     */
    public function countByValue(array $values)
    {
        $query = $this->getQuery();

        foreach ($values as $column => $value) {
            $query->where($column, $value);
        }

        return $query->count();
    }

    /**
     * @param array $attributes
     *
     * @return mixed
     */
    public function create(array $attributes)
    {
        $entity = $this->getModel($attributes);
        $entity->save();

        return $entity;
    }

    /**
     * @param Model $entity
     * @param array $attributes
     *
     * @return Model
     */
    public function update(Model $entity, array $attributes)
    {
        $entity->fill($attributes);

        $entity->save();

        return $entity->fresh();
    }

    /**
     * @param Model $entity
     *
     * @throws Exception
     *
     * @return Model
     */
    public function delete(Model $entity)
    {
        $entity->delete();

        return $entity;
    }

    /**
     * @param array $attributes
     *
     * @return Model
     */
    public function getModel(array $attributes = [])
    {
        return $this->model->newInstance($attributes);
    }

    /**
     * @return Builder
     */
    public function getQuery()
    {
        return $this->getModel()->query();
    }
}
