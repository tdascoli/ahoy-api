<?php
declare(strict_types=1);

namespace App\Domain\Profile;

interface ProfileRepository
{
    /**
     * @return Profile[]
     */
    public function list(): array;

    /**
     * @param int $id
     * @return Profile
     * @throws ProfileNotFoundException
     */
    public function get(int $id): Profile;

    /**
     * @param string $device_id
     * @return Profile
     * @throws ProfileNotFoundException
     */
    public function getByDeviceId(string $device_id): Profile;

    /**
     * @param object $profile
     * @param int|null $profile_id
     * @return string
     * @throws ProfileNotFoundException
     */
    public function post(object $profile, ?int $profile_id): Profile;

    /**
     * @param int $profile_id
     * @return bool
     * @throws ProfileNotFoundException
     */
    public function remove(int $profile_id): bool;
}
