<?php

namespace FOS\OAuthServerBundle\Pomm\Model;

use PommProject\ModelManager\Model\Model;
use PommProject\ModelManager\Model\ModelTrait\WriteQueries;

use PommProject\Foundation\Where;

/**
 * Oauth2ClientsModel
 *
 * Model class for table oauth2_clients.
 *
 * @see Model
 */
class Oauth2ClientsModel extends Model
{
    use WriteQueries;

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
        if (isset($params['randomId']) && !is_null($params['randomId'])) {
            $where = $where->andWhere('random_id = $*::text', [$params['randomId']]);
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

        $sql = strtr(
            $sql,
            [
                ':projection'  => $projection,
                ':oauth_table' => $this->structure->getRelation(),
                ':condition'   => $where,
            ]
        );

        return $this->query($sql, $where->getValues(), $projection);
    }
}
