<?php

namespace SmartProject\SyncBundle\Entity\Provider;

use Doctrine\ORM\Mapping as ORM;
use SmartProject\SyncBundle\Entity\Provider;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Redmine Provider
 *
 * @Gedmo\Loggable
 * @ORM\Table(name="provider_redmine")
 * @ORM\Entity(repositoryClass="SmartProject\SyncBundle\Entity\Provider\ProviderRedmineRepository")
 */
class ProviderRedmine extends Provider
{
    /**
     * @var string
     *
     * @ORM\Column(name="api_user", type="string", length=255)
     */
    private $apiUser;

    /**
     * @var string
     *
     * @ORM\Column(name="api_key", type="string", length=255)
     */
    private $apiKey;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="login", type="string", length=255)
     */
    private $login;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;


    /**
     * Set apiUser
     *
     * @param string $apiUser
     * @return Redmine
     */
    public function setApiUser($apiUser)
    {
        $this->apiUser = $apiUser;
    
        return $this;
    }

    /**
     * Get apiUser
     *
     * @return string 
     */
    public function getApiUser()
    {
        return $this->apiUser;
    }

    /**
     * Set apiKey
     *
     * @param string $apiKey
     * @return Redmine
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    
        return $this;
    }

    /**
     * Get apiKey
     *
     * @return string 
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Redmine
     */
    public function setUrl($url)
    {
        $this->url = $url;
    
        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set login
     *
     * @param string $login
     * @return Redmine
     */
    public function setLogin($login)
    {
        $this->login = $login;
    
        return $this;
    }

    /**
     * Get login
     *
     * @return string 
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return Redmine
     */
    public function setPassword($password)
    {
        $this->password = $password;
    
        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }
}