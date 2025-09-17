<?php

class Storage
{
  private string $basePath;
  private string $publicBaseUrl;
  // private array $allowedExtensions;
  private int $maxSize;

  public function __construct(
    string $basePath = __DIR__ . '/../storage/',
    string $publicBaseUrl = '/healthsync/storage/',
    // array $allowedExtensions = ['pdf', 'png', 'jpg', 'jpeg', 'docx'],
    int $maxSize = 10_000_000
  ) {
    $this->basePath = rtrim($basePath, '/') . '/';
    $this->publicBaseUrl = rtrim($publicBaseUrl, '/') . '/';
    // $this->allowedExtensions = $allowedExtensions;
    $this->maxSize = $maxSize;
  }

  /**
   * @param array
   * @return string|null
   * @throws Exception
   */

  public function save(array $file): ?string
  {
    if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
      throw new Exception('Upload error.');
    }

    if ($file['size'] > $this->maxSize) {
      throw new Exception('File too large.');
    }

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    // if (!in_array($ext, $this->allowedExtensions)) {
    //   throw new Exception('Invalid file type.');
    // }
    $filename = uniqid('file_', true) . '.' . $ext;
    $target = $this->basePath . $filename;

    if (!move_uploaded_file($file['tmp_name'], $target)) {
      throw new Exception('Failed to move file.');
    }

    return $this->publicBaseUrl . $filename;
  }
}
