<?php

namespace App\Services\Psykhe\Contracts;

use App\Models\User;

interface PsykheDirectoryService
{
    /**
     * @param User $user
     *
     * @return mixed
     */
    public function createUser(User $user);

    /**
     * @param User $user
     *
     * @return mixed
     */
    public function deleteUser(User $user);

    /**
     * @param User   $user
     * @param string $name
     *
     * @return mixed
     */
    public function createSavelist(User $user, string $name);

    /**
     * @param User $user
     *
     * @return mixed
     */
    public function listSavelists(User $user);

    /**
     * @param User   $user
     * @param string $savelistSlug
     *
     * @return mixed
     */
    public function getSavelistBySlug(User $user, string $savelistSlug);

    /**
     * @param User   $user
     * @param string $slug
     * @param string $name
     *
     * @return mixed
     */
    public function renameSavelist(User $user, string $slug, string $name);

    /**
     * @param User   $user
     * @param string $slug
     *
     * @return mixed
     */
    public function deleteSavelist(User $user, string $slug);

    /**
     * @param User   $user
     * @param string $savelistSlug
     * @param string $productIdentifier
     *
     * @return mixed
     */
    public function addToSavelist(User $user, string $savelistSlug, string $productIdentifier);

    /**
     * @param User   $user
     * @param string $savelistSlug
     * @param string $productIdentifier
     *
     * @return mixed
     */
    public function removeFromSavelist(User $user, string $savelistSlug, string $productIdentifier);
}
