<?php
declare(strict_types=1);

namespace InitWebkernel;

use GuzzleHttp\Client;
use ZipArchive;

class Initializer
{
    private string $apiUrl = 'https://webkernel.numerimondes.com/api/create-new-instance';
    private string $destinationPath = __DIR__ . '/../webkernel.zip';
    private string $extractPath = __DIR__ . '/../webkernel-instance';

    public function run(): void
    {
        $this->downloadZip();
        $this->extractZip();
        echo "Webkernel instance initialized.\n";
    }

    private function downloadZip(): void
    {
        $client = new Client();
        $response = $client->get($this->apiUrl, ['sink' => $this->destinationPath]);

        if ($response->getStatusCode() !== 200) {
            throw new \RuntimeException("Failed to download ZIP from Webkernel API");
        }
    }

    private function extractZip(): void
    {
        $zip = new ZipArchive();

        if ($zip->open($this->destinationPath) !== true) {
            throw new \RuntimeException("Unable to open ZIP archive");
        }

        $zip->extractTo($this->extractPath);
        $zip->close();
    }
}
