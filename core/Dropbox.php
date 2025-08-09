<?php

namespace Core;

use Exception;
use GuzzleHttp\Client;
use Kunnu\Dropbox\Dropbox as DBox;
use Kunnu\Dropbox\DropboxApp;
use Kunnu\Dropbox\DropboxFile;

class Dropbox
{
    protected DBox $dropbox;

    public function __construct()
    {
        if (!$accessToken = $this->getAccessToken()) {
            throw new Exception('Unable to retrieve access token.');
        }

        $app = new DropboxApp(
            config('services.dropbox.key'),
            config('services.dropbox.secret'),
            $accessToken
        );

        $this->dropbox = new DBox($app);
    }

    public function upload(object $file): object
    {
        $dropboxFile = new DropboxFile($file->tmp_name);
        $uploadedFile = $this->dropbox->upload($dropboxFile, "/" . $this->filename($file->name), ['autorename' => true]);

        return (object) [
            'id' => $uploadedFile->getId(),
            'name' => $file->name,
            'path' => $uploadedFile->getPathDisplay(),
        ];
    }

    public function delete(string $path): void
    {
        $this->dropbox->delete($path);
    }

    protected function filename(string $name)
    {
        $extension = pathinfo($name, PATHINFO_EXTENSION);
        return uniqid() . '.' . $extension;
    }

    public function download(string $path, string $originalName)
    {
        if (!$accessToken = $this->getAccessToken()) {
            throw new Exception('Unable to retrieve access token.');
        }

        $response = (new Client)->post('https://content.dropboxapi.com/2/files/download', [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
                'Dropbox-API-Arg' => json_encode(['path' => $path]),
            ],
        ]);

        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($originalName) . '"');
        return $response->getBody();
        exit(1);
    }

    protected function getAccessToken(): string
    {
        $cache = new Redis('dropbox_access_token');
        if ($token = $cache->get()) return $token;

        $response = (new Client)->post('https://api.dropboxapi.com/oauth2/token', [
            'form_params' => [
                'grant_type' => 'refresh_token',
                'refresh_token' => config('services.dropbox.refreshToken'),
                'client_id' => config('services.dropbox.key'),
                'client_secret' => config('services.dropbox.secret'),
            ],
        ]);

        $data = json_decode($response->getBody(), true);
        $token = $data['access_token'];

        $cache->set($token, 14400);
        return $token;
    }
}
