<?php
/**
 * @link https://craftcms.com/
 * @copyright Copyright (c) Pixel & Tonic, Inc.
 * @license https://craftcms.github.io/license/
 */

namespace craft\gatsby\gql\resolvers;

use Craft;
use craft\gql\base\Resolver;
use craft\gatsby\Plugin as Gatsby;
use GraphQL\Type\Definition\ResolveInfo;

/**
 * Class DeletedNode
 *
 * @author Pixel & Tonic, Inc. <support@pixelandtonic.com>
 * @since 1.0.0
 */
class DeletedNode extends Resolver
{
    public static function resolve($source, array $arguments, $context, ResolveInfo $resolveInfo)
    {
        $deletedNodes = Gatsby::$plugin->getDeltas()->getDeletedNodesSinceTimeStamp($arguments['since']);
        $resolved = [];

        foreach ($deletedNodes as $elementId => $elementType) {
            $resolved[] = [
                'nodeId' => $elementId,
                'nodeType' => $elementType
            ];
        }

        return $resolved;
    }
}
