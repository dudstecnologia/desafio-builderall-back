<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Throwable;

abstract class BaseRepository
{
    protected $model;

    /**
     * solving model
     *
     * @return Model
     */
    public function __construct()
    {
        $this->model = app($this->model);
    }

    /**
     * Returns all records.
     *
     * @return Collection
     */
    public function all(): ?Collection
    {
        try {
            return $this->model->all();
        } catch (Throwable $th) {
            return $this->writeLog($th);
        }
    }

    /**
     * Store and return a model newly created
     *
     * @param  array $data
     * @return Model|null
     */
    public function create($data): ?Model
    {
        try {
            return $this->model->create($data);
        } catch (Throwable $th) {
            return $this->writeLog($th);
        }
    }

    /**
     * Return the specified model
     *
     * @param  array $data
     * @return Model|null
     */
    public function show($id): ?Model
    {
        try {
            return $this->model->findOrFail($id);
        } catch (Throwable $th) {
            return $this->writeLog($th);
        }
    }

    /**
     * Update the specified model
     *
     * @param  array $data
     * @param  int $id
     * @return bool|null
     */
    public function update($data, $id): ?bool
    {
        try {
            return $this->model->find($id)->update($data);
        } catch (Throwable $th) {
            return $this->writeLog($th);
        }
    }

    /**
     * Remove the specified model
     *
     * @param  array $data
     * @param  int $id
     * @return bool|null
     */
    public function destroy($id): ?bool
    {
        try {
            return $this->model->find($id)->delete();
        } catch (Throwable $th) {
            return $this->writeLog($th);
        }
    }

    /**
     * Write the error log
     *
     * @param Throwable $throwable
     * @return null
     */
    protected function writeLog($throwable)
    {
        Log::error($throwable->getMessage());
        return null;
    }
}