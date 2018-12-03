<?php

namespace App;

class Product
{
    public static function stock(
        $productId,
        $quantityAvailable,
        $cache = false,
        $cacheDuration = 60,
        $securityStockConfig = null
    ) {
        if ($quantityAvailable < 0) return $quantityAvailable;

        $ordersQuantity       = self::getOrdersQuantity($productId, $cache, $cacheDuration);
        $blockedStockQuantity = self::getBlockedStockQuantity($productId, $cache, $cacheDuration);

        $quantityAvailable -= ($ordersQuantity + $blockedStockQuantity);

        if (!empty($securityStockConfig)) {
            $quantityAvailable = ShopChannel::applySecurityStockConfig(
                $quantityAvailable,
                $securityStockConfig->mode,
                $securityStockConfig->quantity
            );
        }

        return $quantityAvailable > 0 ? $quantityAvailable : 0;
    }

    private static function getOrdersQuantity($productId, $cache, $cacheDuration): int
    {
        $criteria = sprintf(
            "(order.status = '%s' OR order.status = '%s' OR order.status = '%s') AND order_line.product_id = %s",
            Order::STATUS_PENDING,
            Order::STATUS_PROCESSING,
            Order::STATUS_WAITING_ACCEPTANCE,
            $productId
        );

        $query = OrderLine::find()
                          ->select('SUM(quantity) as quantity')
                          ->joinWith('order')
                          ->where($criteria)
                          ->scalar() ?: 0;

        return !$cache
            ? $query
            : OrderLine::getDb()->cache(function () use ($query) { return $query; }, $cacheDuration);
    }

    private static function getBlockedStockQuantity($productId, $cache, $cacheDuration): int
    {
        $criteria = sprintf(
            "blocked_stock.product_id = %s AND blocked_stock_to_date > '%s' AND (shopping_cart_id IS NULL OR shopping_cart.status = '%s')",
            $productId,
            date('Y-m-d H:i:s'),
            ShoppingCart::STATUS_PENDING
        );

        $query = BlockedStock::find()
                             ->select('SUM(quantity) as quantity')
                             ->joinWith('shoppingCart')
                             ->where($criteria)
                             ->scalar() ?: 0;

        return !$cache
            ? $query
            : BlockedStock::getDb()->cache(function () use ($query) { return $query; }, $cacheDuration);
    }
}