<?php

require __DIR__ . '/../vendor/autoload.php';

use Cloudinary\Cloudinary;

class CloudinaryService
{
    private $cloudinary;

    public function __construct()
    {
        $this->cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => 'dbfbj9gu3',
                'api_key' => '168729351952346',
                'api_secret' => 'kOCN445zwAsB8bAsI_dKFtdmJoM',
            ],
        ]);
    }

    public function uploadImage($file, $folder = "estudiantes")
    {
        if (!isset($file['tmp_name']) || $file['error'] !== UPLOAD_ERR_OK) {
            throw new \Exception("Archivo inválido");
        }

        $result = $this->cloudinary->uploadApi()->upload(
            $file['tmp_name'],
            [
                "folder" => $folder,
                "public_id" => pathinfo($file['name'], PATHINFO_FILENAME)
            ]
        );

        return $result['secure_url'];
    }
}
