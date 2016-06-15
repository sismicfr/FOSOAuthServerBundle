<?php

/*
 * This file is part of the FOSOAuthServerBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FOS\OAuthServerBundle\Pomm;

use Component\Model\Db\PublicSchema\Oauth2ClientsModel;
use FOS\OAuthServerBundle\Model\ClientInterface;
use FOS\OAuthServerBundle\Model\ClientManager as BaseClientManager;
use PommProject\Foundation\Pomm;

class ClientManager extends BaseClientManager
{
    /**
     * @var string
     */
    protected $class;
    /**
     * @var Pomm
     */
    private $pomm;
    /**
     * @var Oauth2ClientsModel
     */
    private $repository;

    public function __construct(Pomm $pomm, $class)
    {
        $this->pomm  = $pomm;
        $this->class = $class;

        /** @var Oauth2ClientsModel repository */
        $this->repository = $pomm
            ->getDefaultSession()
            ->getModel('Component\Model\Db\PublicSchema\Oauth2ClientsModel');
    }

    /**
     * {@inheritdoc}
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * {@inheritdoc}
     */
    public function findClientBy(array $criteria)
    {
        $result = $this->repository->findOneBy($criteria);
        if ($result->isEmpty()) {
            return false;
        }
        return new Client($result->get(0));
    }

    /**
     * {@inheritdoc}
     */
    public function updateClient(ClientInterface $client)
    {
        /** @todo laurent */
        return false;
        $this->em->persist($client);
        $this->em->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function deleteClient(ClientInterface $client)
    {
        $this->repository->deleteByPk($client->getId());
    }
}
