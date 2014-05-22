<?php
namespace PQstudio\RestUploadBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\JsonResponse;

use PQstudio\RestUtilityBundle\Exception\PQHttpException;
use PQstudio\RestUtilityBundle\Controller\PQRestController;

use PQstudio\RestUploadBundle\Entity\File;

class FileController extends PQRestController
{
    /**
     * Adds new temporary File.
     *
     */
    public function postFilesAction(Request $request)
    {
        $type = $request->query->get('type');

        $config = $this->container->getParameter('pq.rest_upload.config');
        $types = $config['types'];

        $allowedTypes = array_keys($types);

        // Check if the file's mime type is in the list of allowed mime types.
        if (!in_array($type, $allowedTypes)) {
            $this->meta->setError('type_not_allowed')
                ->setErrorMessage('Provided file type is not allowed')
            ;

            $view = $this->makeView(
                400,
                ['meta' => $this->meta->build()],
                [],
                false
            );

            return $this->handleView($view);
        }

        $file = $request->files->get('file');

        if($file === null) {
            $this->meta->setError('no_file')
                ->setErrorMessage('No file provided under `file` key')
            ;

            $view = $this->makeView(
                400,
                ['meta' => $this->meta->build()],
                [],
                false
            );

            return $this->handleView($view);
        }

        if(filesize($file->getPathName()) > $types[$type]['maxSize']) {
            $this->meta->setError('too_large')
                ->setErrorMessage('Uploaded file is too large')
            ;

            $view = $this->makeView(
                400,
                ['meta' => $this->meta->build()],
                [],
                false
            );

            return $this->handleView($view);
        }

        $uploader = $this->get('pq.rest_upload.uploader');

        $uniqueId = $this->get('pq.rest_upload.utility.token_generator')->generateToken();

        $result = $uploader->upload($file, $uniqueId, $types[$type]['mimeTypes']);

        if($result === false) {
            $this->meta->setError('mimetype_not_allowed')
                       ->setErrorMessage('File mime type is not allowed.')
            ;

            $view = $this->makeView(
                400,
                ['meta' => $this->meta->build()],
                [],
                false
            );

            return $this->handleView($view);
        }

        $fileRepository = $this->get('file_repository');
        $filesystem = $this->get('tmpfiles_filesystem');

        $oldFiles = $fileRepository->findOlderThan(5);
        foreach($oldFiles as $oldFile) {
            $filesystem->delete($oldFile->getFilename());
        }

        $fileRepository->deleteOlderThan(5);

        $file = new File();
        $file->setUniqueId($uniqueId);
        $file->setFilename($result['filename']);
        $file->setMimeType($result['mimeType']);
        $file->setType($type);

        $fileRepository->add($file);

        $view = $this->makeView(
            201,
            ['uniqueId' => $uniqueId],
            [],
            false
        );

        return $this->handleView($view);
    }
}
