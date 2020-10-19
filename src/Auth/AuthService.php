<?php


namespace App\Auth;

use App\Domain\Profile\ProfileNotFoundException;
use App\Domain\Profile\ProfileRepository;

final class AuthService
{
    /**
     * @var ProfileRepository
     */
    protected $profileRepository;

    /**
     * AuthService constructor.
     * @param ProfileRepository $profileRepository
     */
    public function __construct(ProfileRepository $profileRepository)
    {
        $this->profileRepository = $profileRepository;
    }

    /**
     * Verify credentials.
     *
     * @param string $deviceId
     * @param string $password
     * @return bool
     * @throws ProfileNotFoundException
     */
    public function verify(string $deviceId, string $password): bool
    {
        $profile = $this->profileRepository->getByDeviceId($deviceId);
        return password_verify($password, $profile->getPassword());
    }

    /**
     * Verify device id and profile id.
     *
     * @param string $deviceId
     * @param int $profileId
     * @return bool
     * @throws ProfileNotFoundException
     */
    public function verifyDeviceIdWithProfileId(string $deviceId, int $profileId): bool
    {
        $profile = $this->profileRepository->getByDeviceId($deviceId);
        return ($profile->getUid() == $profileId);
    }
}