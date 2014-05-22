<?php
namespace PQstudio\RestUploadBundle\Uploader;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Gaufrette\Filesystem;


class Uploader
{

    private $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function upload(UploadedFile $file, $uniqueId, $allowedMimeTypes)
    {
        // Check if the file's mime type is in the list of allowed mime types.
        if($allowedMimeTypes != null && !empty($allowedMimeTypes)) {
            if (!in_array($file->getClientMimeType(), $allowedMimeTypes)) {
                return false;
            }
        }

        // Generate a unique filename based on the date and add file extension of the uploaded file
        $filename = sprintf('%s%s%s%s_%s.%s', date('Y'), date('m'), date('d'), uniqid(), $uniqueId, $file->getClientOriginalExtension());

        $adapter = $this->filesystem->getAdapter();
        //$adapter->setMetadata($filename, array('contentType' => $file->getClientMimeType()));
        $adapter->write($filename, file_get_contents($file->getPathname()));

        return ['filename' => $filename, 'mimeType' => $file->getClientMimeType()];
    }
}
