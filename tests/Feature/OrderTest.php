<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Shop;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_stock_decrements_when_order_placed()
    {
        $shop = Shop::factory()->create();
        
        // Corrected type for user
        /**
         * @var \App\Models\User $user
         */
        $user = User::factory()->create(['shop_id' => $shop->id]);
        
        $product = Product::factory()->create(['shop_id' => $shop->id, 'stock' => 10, 'price' => 100]);

        $this->actingAs($user)
            ->post(route('orders.store'), [
                'products' => [
                    ['id' => $product->id, 'quantity' => 3]
                ]
            ])->assertRedirect(route('orders.index'));

        $this->assertDatabaseHas('orders', ['shop_id' => $shop->id]);
        $this->assertDatabaseHas('order_items', ['product_id' => $product->id, 'quantity' => 3]);
        $this->assertEquals(7, $product->fresh()->stock);
    }

    public function test_insufficient_stock_returns_error()
    {
        $shop = Shop::factory()->create();
        
        // Corrected type for user
        /**
         * @var \App\Models\User $user
         */
        $user = User::factory()->create(['shop_id' => $shop->id]);
        
        $product = Product::factory()->create(['shop_id' => $shop->id, 'stock' => 1]);

        $this->actingAs($user)
            ->post(route('orders.store'), [
                'products' => [
                    ['id' => $product->id, 'quantity' => 3]
                ]
            ])->assertSessionHasErrors();
    }

    public function test_tenant_isolation_prevents_access_to_other_shops_products()
    {
        $shopA = Shop::factory()->create();
        $shopB = Shop::factory()->create();
        
        // Corrected type for user
        /**
         * @var \App\Models\User $userA
         */
        $userA = User::factory()->create(['shop_id' => $shopA->id]);
        $productB = Product::factory()->create(['shop_id' => $shopB->id, 'stock' => 10]);

        // user from shopA trying to order product of shopB should fail
        $this->actingAs($userA)
            ->post(route('orders.store'), [
                'products' => [
                    ['id' => $productB->id, 'quantity' => 1]
                ]
            ])->assertSessionHasErrors();
    }

    public function test_order_can_contain_multiple_products()
    {
        $shop = Shop::factory()->create();
        
        // Corrected type for user
        /**
         * @var \App\Models\User $user
         */
        $user = User::factory()->create(['shop_id' => $shop->id]);
        
        $p1 = Product::factory()->create(['shop_id' => $shop->id, 'stock' => 5, 'price' => 10]);
        $p2 = Product::factory()->create(['shop_id' => $shop->id, 'stock' => 5, 'price' => 20]);

        $this->actingAs($user)
            ->post(route('orders.store'), [
                'products' => [
                    ['id' => $p1->id, 'quantity' => 2],
                    ['id' => $p2->id, 'quantity' => 1],
                ]
            ])->assertRedirect(route('orders.index'));

        $this->assertDatabaseHas('order_items', ['product_id' => $p1->id, 'quantity' => 2]);
        $this->assertDatabaseHas('order_items', ['product_id' => $p2->id, 'quantity' => 1]);
        $this->assertEquals(3, $p1->fresh()->stock);
        $this->assertEquals(4, $p2->fresh()->stock);
    }
}
