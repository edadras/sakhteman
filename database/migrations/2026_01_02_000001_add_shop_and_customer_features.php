<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->nullable()->change();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_admin')->default(false)->after('password');
            $table->string('mobile', 20)->nullable()->unique()->after('email');
            $table->text('address')->nullable()->after('mobile');
        });

        Schema::table('slides', function (Blueprint $table) {
            $table->string('tags')->nullable()->after('subtitle');
            $table->string('designer_name')->nullable()->after('button_link');
            $table->string('designer_role')->nullable()->after('designer_name');
            $table->string('designer_avatar')->nullable()->after('designer_role');
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->string('units')->nullable()->after('floors');
        });

        Schema::table('steps', function (Blueprint $table) {
            $table->string('image')->nullable()->after('icon');
        });

        Schema::create('product_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->unsignedInteger('sort')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('product_categories')->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('sku')->nullable();
            $table->unsignedBigInteger('price')->nullable();
            $table->unsignedBigInteger('sale_price')->nullable();
            $table->boolean('price_from')->default(false);
            $table->boolean('in_stock')->default(true);
            $table->text('summary')->nullable();
            $table->longText('body')->nullable();
            $table->text('specs')->nullable();
            $table->string('image')->nullable();
            $table->json('gallery')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('sort')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('mobile', 20);
            $table->string('city')->nullable();
            $table->text('address');
            $table->string('postal_code', 20)->nullable();
            $table->text('note')->nullable();
            $table->unsignedBigInteger('total')->default(0);
            $table->string('status')->default('pending');
            $table->text('admin_note')->nullable();
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->unsignedBigInteger('price');
            $table->unsignedInteger('quantity');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('products');
        Schema::dropIfExists('product_categories');
        Schema::table('steps', fn (Blueprint $t) => $t->dropColumn('image'));
        Schema::table('projects', fn (Blueprint $t) => $t->dropColumn('units'));
        Schema::table('slides', fn (Blueprint $t) => $t->dropColumn(['tags', 'designer_name', 'designer_role', 'designer_avatar']));
        Schema::table('users', function (Blueprint $t) {
            $t->dropUnique(['mobile']);
            $t->dropColumn(['is_admin', 'mobile', 'address']);
        });
    }
};
