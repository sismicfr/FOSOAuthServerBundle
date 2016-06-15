<?php
namespace FOS\OAuthServerBundle\Pomm;

use FOS\OAuthServerBundle\Entity\Client as BaseClient;
use FOS\OAuthServerBundle\Model\ClientInterface;

/**
 * Class Client
 * @package FOS\OAuthServerBundle\Pomm
 */
class Client extends BaseClient implements ClientInterface
{

    /**
     * @var
     */
    protected $id;

    /**
     * Client constructor.
     * @param $data
     */
    public function __construct($data)
    {
        $this->id = $data['id'];
        parent::__construct();

        $this->setAllowedGrantTypes(unserialize($data['allowed_grant_types']));
        $this->setRandomId($data['random_id']);
        $this->setSecret($data['secret']);
    }

    /**
     * {@inheritdoc}
     */
    public function checkSecret($secret)
    {
        return null === $this->secret || $secret === $this->secret;
    }

    /**
     * {@inheritdoc}
     */
    public function getPublicId()
    {
        return sprintf('%s_%s', $this->getId(), $this->getRandomId());
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }
}