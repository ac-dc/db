<?php
/**
 * @link http://www.yiiframework.com/
 *
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace Yiisoft\Db\Conditions;

use Yiisoft\Db\ExpressionBuilderInterface;
use Yiisoft\Db\ExpressionBuilderTrait;
use Yiisoft\Db\ExpressionInterface;

/**
 * Class NotConditionBuilder builds objects of [[SimpleCondition]].
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 *
 * @since 2.0.14
 */
class SimpleConditionBuilder implements ExpressionBuilderInterface
{
    use ExpressionBuilderTrait;

    /**
     * Method builds the raw SQL from the $expression that will not be additionally
     * escaped or quoted.
     *
     * @param ExpressionInterface|SimpleCondition $expression the expression to be built.
     * @param array                               $params     the binding parameters.
     *
     * @return string the raw SQL that will not be additionally escaped or quoted.
     */
    public function build(ExpressionInterface $expression, array &$params = [])
    {
        $operator = $expression->getOperator();
        $column = $expression->getColumn();
        $value = $expression->getValue();

        if (strpos($column, '(') === false) {
            $column = $this->queryBuilder->db->quoteColumnName($column);
        }

        if ($value === null) {
            return "$column $operator NULL";
        }
        if ($value instanceof ExpressionInterface) {
            return "$column $operator {$this->queryBuilder->buildExpression($value, $params)}";
        }

        $phName = $this->queryBuilder->bindParam($value, $params);

        return "$column $operator $phName";
    }
}
