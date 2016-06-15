<?php

namespace FOS\OAuthServerBundle\Pomm\Model;

use FOS\OAuthServerBundle\Model\Token;
use PommProject\ModelManager\Model\ModelTrait\WriteQueries;

use PommProject\Foundation\Where;

use FOS\OAuthServerBundle\Pomm\Model\Oauth2AuthCodesModel as BaseModel;

/**
 * Oauth2AccessTokensModel
 *
 * Model class for table oauth2_access_tokens.
 *
 * @see Model
 */
class Oauth2AccessTokensModel extends BaseModel
{
    use WriteQueries;

    /**
     * Delete expired tokens
     */
    public function deleteExpired()
    {
        $res = $this->deleteWhere('expires_at <= $*', [time()]);
        return count($res);
    }

    /**
     * @param $params
     * @return \PommProject\ModelManager\Model\CollectionIterator
     */
    public function findOneBy($params)
    {
        $where = new Where();

        if (isset($params['id']) && !is_null($params['id'])) {
            $where = $where->andWhere('id = $*::int4', [(int)$params['id']]);
        }
        if (isset($params['client_id']) && !is_null($params['client_id'])) {
            $where = $where->andWhere('client_id = $*::int4', [(int)$params['client_id']]);
        }
        if (isset($params['user_id']) && !is_null($params['user_id'])) {
            $where = $where->andWhere('user_id = $*::int4', [(int)$params['user_id']]);
        }
        if (isset($params['token']) && !is_null($params['token'])) {
            $where = $where->andWhere('token = $*::text', [$params['token']]);
        }

        $sql = <<<SQL
select
    :projection
from
    :oauth_table o
where
    :condition
SQL;

        $projection = $this->createProjection();

        $sql    = strtr(
            $sql,
            [
                ':projection'  => $projection,
                ':oauth_table' => $this->structure->getRelation(),
                ':condition'   => $where,
            ]
        );
        $result = $this->query($sql, $where->getValues(), $projection);
        if ($result->isEmpty()) {
            return false;
        }
        $token = new Token();
        $token->setToken($result->get(0)['token']);
        $token->setExpiresAt($result->get(0)['expires_at']);
        $token->setScope($result->get(0)['scope']);

        return $token;
    }
}
