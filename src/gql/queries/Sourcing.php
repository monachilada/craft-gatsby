<?php
/**
 * @link https://craftcms.com/
 * @copyright Copyright (c) Pixel & Tonic, Inc.
 * @license https://craftcms.github.io/license/
 */

namespace craft\gatsby\gql\queries;

use Craft;
use craft\gatsby\Plugin as Gatsby;
use craft\gatsby\gql\resolvers\SourceNode as SourceNodeResolver;
use craft\gatsby\gql\resolvers\UpdatedNode as UpdatedNodeResolver;
use craft\gatsby\gql\resolvers\DeletedNode as DeletedNodeResolver;
use craft\gatsby\gql\types\ChangedNode;
use craft\gatsby\helpers\Gql as GqlHelper;
use craft\gatsby\gql\types\SourceNode;
use craft\gql\base\Query;
use craft\gql\types\DateTime;
use GraphQL\Type\Definition\Type;

/**
 * Class Sourcing
 *
 * @author Pixel & Tonic, Inc. <support@pixelandtonic.com>
 * @since 1.0.0
 */
class Sourcing extends Query
{
    /**
     * @inheritdoc
     */
    public static function getQueries($checkToken = true): array
    {
        if ($checkToken && !GqlHelper::canQueryGatsbyData()) {
            return [];
        }

        return [
            'sourceNodeInformation' => [
                'type' => Type::listOf(SourceNode::getType()),
                'resolve' => SourceNodeResolver::class . '::resolve',
                'description' => 'Return sourcing data for Gatsby source queries.'
            ],
            'configVersion' => [
                'type' => Type::string(),
                'resolve' => function() {
                    return Craft::$app->getInfo()->configVersion;
                },
                'description' => 'Return the current config version.'
            ],
            'lastUpdateTime' => [
                'type' => DateTime::getType(),
                'resolve' => function() {
                    return Gatsby::$plugin->getDeltas()->getLastContentUpdateTime();
                },
                'description' => 'Return the last time content was updated on this site.'
            ],
            'nodesUpdatedSince' => [
                'type' => Type::listOf(ChangedNode::getType()),
                'args' => [
                    'since' => [
                        'name' => 'since',
                        'type' => Type::nonNull(DateTime::getType())
                    ]
                ],
                'resolve' => UpdatedNodeResolver::class . '::resolve',
                'description' => 'Return the list of nodes updated since a point in time.'
            ],
            'nodesDeletedSince' => [
                'type' => Type::listOf(ChangedNode::getType()),
                'args' => [
                    'since' => [
                        'name' => 'since',
                        'type' => Type::nonNull(DateTime::getType())
                    ]
                ],
                'resolve' => DeletedNodeResolver::class . '::resolve',
                'description' => 'Return the list of nodes deleted since a point in time.'
            ],
        ];
    }
}
