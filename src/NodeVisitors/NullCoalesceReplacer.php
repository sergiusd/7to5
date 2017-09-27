<?php

namespace Spatie\Php7to5\NodeVisitors;

use PhpParser\Node;
use PhpParser\Node\Expr\BinaryOp\Coalesce;
use PhpParser\NodeVisitorAbstract;

class NullCoalesceReplacer extends NodeVisitorAbstract
{
    /**
     * {@inheritdoc}
     */
    public function leaveNode(Node $node)
    {
        if (!$node instanceof Coalesce) {
            return;
        }
        switch(true)
        {
            case $node->left instanceof Node\Expr\ArrayDimFetch:
                $constName = $this->getArrayConstName($node->left);
                $definedCall = new Node\Expr\FuncCall(new Node\Name('defined'), [$constName]);
                $notEmptyCall = new Node\Expr\BooleanNot(new Node\Expr\FuncCall(new Node\Name('empty'), [$node->left]));
                $condition = new Node\Expr\BinaryOp\LogicalAnd($definedCall, $notEmptyCall);
                return new Node\Expr\Ternary($condition, $node->left, $node->right);
            case $node->left instanceof Node\Expr\ClassConstFetch:
                $constName = $this->getArrayConstName($node->left);
                $definedCall = new Node\Expr\FuncCall(new Node\Name('defined'), [$constName]);
                return new Node\Expr\Ternary($definedCall, $node->left, $node->right);
            case $node->left instanceof Node\Expr\FuncCall:
            case $node->left instanceof Node\Expr\MethodCall:
            case $node->left instanceof Node\Expr\StaticCall:
                $notEmptyCall = new Node\Expr\BooleanNot(new Node\Expr\FuncCall(new Node\Name('empty'), [$node->left]));
                return new Node\Expr\Ternary($notEmptyCall, $node->left, $node->right);
            case $node->left instanceof Node\Expr\BinaryOp:
                $issetCall = new Node\Expr\FuncCall(new Node\Name('isset'), [$node->left->right]);
                $node->left->right = new Node\Expr\Ternary($issetCall, $node->left->right, $node->right);
                return $node->left;
            default:
                $issetCall = new Node\Expr\FuncCall(new Node\Name('isset'), [$node->left]);
                return new Node\Expr\Ternary($issetCall, $node->left, $node->right);
        }
    }

    /**
     * @param Node\Expr\ClassConstFetch|Node\Expr\ArrayDimFetch $node
     * @return Node\Scalar\String_
     * @throws \Exception
     */
    private function getArrayConstName($node)
    {
        if ($node instanceof Node\Expr\ClassConstFetch) {
            return new Node\Scalar\String_(join('\\', $node->class->parts).'::'.$node->name);
        }
        elseif ($node instanceof Node\Expr\ArrayDimFetch) {
            return $this->getArrayConstName($node->var);
        }
        else {
            throw new \Exception('Unexpected class '.get_class($node));
        }
    }
}