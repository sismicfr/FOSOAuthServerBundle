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

use Component\Model\Db\PublicSchema\Oauth2AccessTokensModel;
use FOS\OAuthServerBundle\Model\TokenInterface;
use FOS\OAuthServerBundle\Model\TokenManager as BaseTokenManager;
use PommProject\Foundation\Pomm;

class TokenManager extends BaseTokenManager
{
    /**
     * @var Oauth2AccessTokensModel
     */
    protected $repository;

    /**
     * @var string
     */
    protected $class;
    /**
     * @var Pomm
     */
    private $pomm;

    public function __construct(Pomm $pomm, $class)
    {
        $this->pomm  = $pomm;
        $this->class = $class;


        /** @var Oauth2AccessTokensModel repository */
        $this->repository = $pomm
            ->getDefaultSession()
            ->getModel('Component\Model\Db\PublicSchema\Oauth2AccessTokensModel');
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
    public function findTokenBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }

    /**
     * {@inheritdoc}
     */
    public function updateToken(TokenInterface $token)
    {
        $newToken = $this->repository->createAndSave([
            'client_id'  => $token->getClientId(),
            'user_id'    => $token->getUser()->getId(),
            'token'      => $token->getToken(),
            'expires_at' => $token->getExpiresAt(),
            'scope'      => $token->getScope(),
        ]);
        $token->setId($newToken->getId());
        return $token;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteToken(TokenInterface $token)
    {
        $this->repository->deleteByPk($token->getId());
    }

    /**
     * {@inheritdoc}
     */
    public function deleteExpired()
    {
        return $this->repository->deleteExpired();

    }
}
