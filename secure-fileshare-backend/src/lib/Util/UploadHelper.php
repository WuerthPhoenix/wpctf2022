<?php

namespace Wp\Sfb\Util;

use Wp\Sfb\Model\File;
use Wp\Sfb\Model\UploadedFile;
use Wp\Sfb\Model\User;

const FILE_CONTENT_NAME = 'file';
const GROUP_CONTENT_NAME = 'group_id';

class UploadHelper
{
    protected FileRepository $frepo;

    public function __construct()
    {
        $this->frepo = new FileRepository();
    }


    private static function fileWasUploaded(): bool {
        return isset($_FILES[FILE_CONTENT_NAME]);
    }

    public static function getUploadedFile(): ?UploadedFile {
        if (self::fileWasUploaded()) {
            return new UploadedFile(
                $_FILES[FILE_CONTENT_NAME]["name"],
                file_get_contents($_FILES[FILE_CONTENT_NAME]["tmp_name"])
            );
        }

        return null;
    }

    public static function getNotOverlappingPath($path): string {
        $not_overlapping_path = $path;
        $count = 1;
        while (file_exists(UPLOAD_PATH . "/" . $not_overlapping_path)) {
            $not_overlapping_path = $path . "_" . $count++;
        }
        return $not_overlapping_path;
    }

    public function storeUploadedFile(UploadedFile  $uploaded_file, User $user, $group_id) : ?File {
        $relativePath = self::getNotOverlappingPath($group_id . "/" . $uploaded_file->getName());
        $path = UPLOAD_PATH . "/" . $relativePath;

        mkdir(dirname($path), 0775, true);

        if (file_put_contents($path, $uploaded_file->getContent())) {
            $file = new File(
                null,
                $uploaded_file->getName(),
                filesize($path),
                $relativePath,
                $user->getId(),
                $user->getGroupId());

            if ($this->frepo->put($file)) {
                return $file;
            }
        }
        return null;
    }
}