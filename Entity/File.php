<?php

namespace PQstudio\RestUploadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table("file")
 */
class File
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $filename;

    /**
     * @ORM\Column(type="string")
     */
    protected $mimeType;

    /**
     * @ORM\Column(type="string")
     */
    protected $uniqueId;

    /**
     * @ORM\Column(type="string")
     */
    protected $type;


    public function __construct()
    {
    }

     /**
      * Get id.
      *
      * @return id.
      */
     public function getId()
     {
         return $this->id;
     }

     /**
      * Get filename.
      *
      * @return filename.
      */
     public function getFilename()
     {
         return $this->filename;
     }

     /**
      * Set filename.
      *
      * @param filename the value to set.
      */
     public function setFilename($filename)
     {
         $this->filename = $filename;
     }

     /**
      * Get mimeType.
      *
      * @return mimeType.
      */
     public function getMimeType()
     {
         return $this->mimeType;
     }

     /**
      * Set mimeType.
      *
      * @param mimeType the value to set.
      */
     public function setMimeType($mimeType)
     {
         $this->mimeType = $mimeType;
     }

     /**
      * Get type.
      *
      * @return type.
      */
     public function getType()
     {
         return $this->type;
     }

     /**
      * Set type.
      *
      * @param type the value to set.
      */
     public function setType($type)
     {
         $this->type = $type;
     }

     /**
      * Get uniqueId.
      *
      * @return uniqueId.
      */
     public function getUniqueId()
     {
         return $this->uniqueId;
     }

     /**
      * Set uniqueId.
      *
      * @param uniqueId the value to set.
      */
     public function setUniqueId($uniqueId)
     {
         $this->uniqueId = $uniqueId;
     }
}
