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

use Component\Model\Db\PublicSchema\Oauth2AuthCodesModel;
use FOS\OAuthServerBundle\Model\AuthCodeInterface;
use FOS\OAuthServerBundle\Model\AuthCodeManager as BaseAuthCodeManager;
use PommProject\Foundation\Pomm;

class AuthCodeManager extends BaseAuthCodeManager
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
     * @var Oauth2AuthCodesModel
     */
    private $repository;

    /**
     * @param Pomm $pomm
     * @param string $class
     */
    public function __construct(Pomm $pomm, $class)
    {
        $this->pomm  = $pomm;
        $this->class = $class;

        /** @var Oauth2AuthCodesModel repository */
        $this->repository = $pomm
            ->getDefaultSession()
            ->getModel('Component\Model\Db\PublicSchema\Oauth2AuthCodesModel');
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
    public function findAuthCodeBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }

    /**
     * {@inheritdoc}
     */
    public function updateAuthCode(AuthCodeInterface $authCode)
    {
        /** @todo laurent  */
        return false;
        $this->em->persist($authCode);
        $this->em->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function deleteAuthCode(AuthCodeInterface $authCode)
    {
        $this->repository->deleteByPk($authCode->getId());
    }

    /**
     * {@inheritdoc}
     */
    public function deleteExpired()
    {
        return $this->repository->deleteExpired();
    }
}
