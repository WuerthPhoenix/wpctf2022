<?php

namespace Wp\Sfb\Auth;

class AuthenticatedUser
{
    protected array $permissions;
    private const PERM_MAPPING = [
        'upload' => 0,
        'listFiles' => 1,
        'deleteFiles' => 2,
        'listUsers' => 3,
        'download' => 4,
    ];

    /**
     * @param array $permissions
     */
    public function __construct(array $permissions)
    {
        $this->permissions = $this->extractPermissions($permissions);
    }

    private function extractPermissions($permissionArray): array {
        return [
            'upload' => $permissionArray[AuthenticatedUser::PERM_MAPPING['upload']],
            'listFiles' => $permissionArray[AuthenticatedUser::PERM_MAPPING['listFiles']],
            'deleteFiles' => $permissionArray[AuthenticatedUser::PERM_MAPPING['deleteFiles']],
            'listUsers' => $permissionArray[AuthenticatedUser::PERM_MAPPING['listUsers']],
            'download' => $permissionArray[AuthenticatedUser::PERM_MAPPING['download']],
        ];
    }

    public static function packPermissions($permissions): array {
        $perms = [
            $permissions->upload ? 1 : 0,
            $permissions->listFiles ? 1 : 0,
            $permissions->deleteFiles ? 1 : 0,
            $permissions->listUsers ? 1 : 0,
            $permissions->download ? 1 : 0,
        ];
        return $perms;
    }

    /**
     * @return array
     */
    public function getPermissions(): array
    {
        return $this->permissions;
    }

    public function canUpload(): bool {
        return (bool)$this->permissions['upload'];
    }

    public function canDownload(): bool {
        return (bool)$this->permissions['download'];
    }

    public function canListFiles(): bool {
        return (bool)$this->permissions['listFiles'];
    }

    public function canDeleteFiles(): bool {
        return (bool)$this->permissions['deleteFiles'];
    }

    public function canListUsers(): bool {
        return (bool)$this->permissions['listUsers'];
    }
}