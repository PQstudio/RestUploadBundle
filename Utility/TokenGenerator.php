<?php
namespace PQstudio\RestUploadBundle\Utility;

class TokenGenerator
{
    private $useOpenSsl;

    public function __construct()
    {
        // determine whether to use OpenSSL
        if (defined('PHP_WINDOWS_VERSION_BUILD') && version_compare(PHP_VERSION, '5.3.4', '<')) {
            $this->useOpenSsl = false;
        } elseif (!function_exists('openssl_random_pseudo_bytes')) {
           $this->useOpenSsl = false;
        } else {
            $this->useOpenSsl = true;
        }
    }

    public function generateToken($nbBytes = 32)
    {
        return base_convert(bin2hex($this->getRandomNumber($nbBytes)), 16, 36);
    }

    private function getRandomNumber($nbBytes = 32)
    {
        // try OpenSSL
        if ($this->useOpenSsl) {
            $bytes = openssl_random_pseudo_bytes($nbBytes, $strong);

            if (false !== $bytes && true === $strong) {
                return $bytes;
            }

            if (null !== $this->logger) {
                $this->logger->info('OpenSSL did not produce a secure random number.');
            }
        }

        return hash('sha256', uniqid(mt_rand(), true), true);
    }
}
