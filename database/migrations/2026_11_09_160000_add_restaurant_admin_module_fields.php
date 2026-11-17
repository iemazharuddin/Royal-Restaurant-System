<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // CUSTOMERS
        Schema::table('customers', function (Blueprint $table) {
            if (!Schema::hasColumn('customers', 'name'))
                $table->string('name')->nullable();

            if (!Schema::hasColumn('customers', 'email'))
                $table->string('email')->nullable();

            if (!Schema::hasColumn('customers', 'phone'))
                $table->string('phone', 30)->nullable();

            if (!Schema::hasColumn('customers', 'address'))
                $table->text('address')->nullable();

            if (!Schema::hasColumn('customers', 'notes'))
                $table->text('notes')->nullable();
        });

        // RESTAURANT TABLES
        Schema::table('restaurant_tables', function (Blueprint $table) {
            if (!Schema::hasColumn('restaurant_tables', 'table_number'))
                $table->string('table_number')->nullable();

            if (!Schema::hasColumn('restaurant_tables', 'capacity'))
                $table->unsignedSmallInteger('capacity')->default(2);

            if (!Schema::hasColumn('restaurant_tables', 'status'))
                $table->string('status')->default('available');

            if (!Schema::hasColumn('restaurant_tables', 'description'))
                $table->text('description')->nullable();
        });

        // ORDERS
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'order_number'))
                $table->string('order_number')->nullable();

            if (!Schema::hasColumn('orders', 'customer_id'))
                $table->foreignId('customer_id')->nullable()
                    ->constrained('customers')->nullOnDelete();

            if (!Schema::hasColumn('orders', 'restaurant_table_id'))
                $table->foreignId('restaurant_table_id')->nullable()
                    ->constrained('restaurant_tables')->nullOnDelete();

            if (!Schema::hasColumn('orders', 'order_type'))
                $table->string('order_type')->default('dine_in');

            if (!Schema::hasColumn('orders', 'status'))
                $table->string('status')->default('pending');

            if (!Schema::hasColumn('orders', 'items'))
                $table->json('items')->nullable();

            if (!Schema::hasColumn('orders', 'subtotal'))
                $table->decimal('subtotal', 10, 2)->default(0);

            if (!Schema::hasColumn('orders', 'tax'))
                $table->decimal('tax', 10, 2)->default(0);

            if (!Schema::hasColumn('orders', 'discount'))
                $table->decimal('discount', 10, 2)->default(0);

            if (!Schema::hasColumn('orders', 'total'))
                $table->decimal('total', 10, 2)->default(0);

            if (!Schema::hasColumn('orders', 'notes'))
                $table->text('notes')->nullable();
        });

        // PAYMENTS
        Schema::table('payments', function (Blueprint $table) {
            if (!Schema::hasColumn('payments', 'order_id'))
                $table->foreignId('order_id')->nullable()
                    ->constrained('orders')->nullOnDelete();

            if (!Schema::hasColumn('payments', 'payment_number'))
                $table->string('payment_number')->nullable();

            if (!Schema::hasColumn('payments', 'amount'))
                $table->decimal('amount', 10, 2)->default(0);

            if (!Schema::hasColumn('payments', 'payment_method'))
                $table->string('payment_method')->default('cash');

            if (!Schema::hasColumn('payments', 'transaction_id'))
                $table->string('transaction_id')->nullable();

            if (!Schema::hasColumn('payments', 'status'))
                $table->string('status')->default('pending');

            if (!Schema::hasColumn('payments', 'paid_at'))
                $table->timestamp('paid_at')->nullable();
        });

        // OFFERS
        Schema::table('offers', function (Blueprint $table) {
            if (!Schema::hasColumn('offers', 'title'))
                $table->string('title')->nullable();

            if (!Schema::hasColumn('offers', 'code'))
                $table->string('code')->nullable();

            if (!Schema::hasColumn('offers', 'description'))
                $table->text('description')->nullable();

            if (!Schema::hasColumn('offers', 'discount_type'))
                $table->string('discount_type')->default('percentage');

            if (!Schema::hasColumn('offers', 'discount_value'))
                $table->decimal('discount_value', 10, 2)->default(0);

            if (!Schema::hasColumn('offers', 'minimum_order'))
                $table->decimal('minimum_order', 10, 2)->nullable();

            if (!Schema::hasColumn('offers', 'starts_at'))
                $table->timestamp('starts_at')->nullable();

            if (!Schema::hasColumn('offers', 'ends_at'))
                $table->timestamp('ends_at')->nullable();

            if (!Schema::hasColumn('offers', 'is_active'))
                $table->boolean('is_active')->default(true);
        });

        // REVIEWS
        Schema::table('reviews', function (Blueprint $table) {
            if (!Schema::hasColumn('reviews', 'customer_id'))
                $table->foreignId('customer_id')->nullable()
                    ->constrained('customers')->nullOnDelete();

            if (!Schema::hasColumn('reviews', 'customer_name'))
                $table->string('customer_name')->nullable();

            if (!Schema::hasColumn('reviews', 'rating'))
                $table->unsignedTinyInteger('rating')->default(5);

            if (!Schema::hasColumn('reviews', 'comment'))
                $table->text('comment')->nullable();

            if (!Schema::hasColumn('reviews', 'is_approved'))
                $table->boolean('is_approved')->default(false);
        });

        // GALLERY
        Schema::table('gallery', function (Blueprint $table) {
            if (!Schema::hasColumn('gallery', 'title'))
                $table->string('title')->nullable();

            if (!Schema::hasColumn('gallery', 'image'))
                $table->string('image')->nullable();

            if (!Schema::hasColumn('gallery', 'description'))
                $table->text('description')->nullable();

            if (!Schema::hasColumn('gallery', 'category'))
                $table->string('category')->nullable();

            if (!Schema::hasColumn('gallery', 'is_active'))
                $table->boolean('is_active')->default(true);

            if (!Schema::hasColumn('gallery', 'sort_order'))
                $table->unsignedInteger('sort_order')->default(0);
        });

        // STAFF PROFILES
        Schema::table('staff_profiles', function (Blueprint $table) {
            if (!Schema::hasColumn('staff_profiles', 'user_id'))
                $table->foreignId('user_id')->nullable()
                    ->constrained('users')->nullOnDelete();

            if (!Schema::hasColumn('staff_profiles', 'employee_id'))
                $table->string('employee_id')->nullable();

            if (!Schema::hasColumn('staff_profiles', 'name'))
                $table->string('name')->nullable();

            if (!Schema::hasColumn('staff_profiles', 'email'))
                $table->string('email')->nullable();

            if (!Schema::hasColumn('staff_profiles', 'phone'))
                $table->string('phone', 30)->nullable();

            if (!Schema::hasColumn('staff_profiles', 'position'))
                $table->string('position')->nullable();

            if (!Schema::hasColumn('staff_profiles', 'role'))
                $table->string('role')->default('staff');

            if (!Schema::hasColumn('staff_profiles', 'status'))
                $table->string('status')->default('active');

            if (!Schema::hasColumn('staff_profiles', 'salary'))
                $table->decimal('salary', 10, 2)->nullable();

            if (!Schema::hasColumn('staff_profiles', 'joining_date'))
                $table->date('joining_date')->nullable();
        });

        // NOTIFICATIONS
        Schema::table('notifications', function (Blueprint $table) {
            if (!Schema::hasColumn('notifications', 'title'))
                $table->string('title')->nullable();

            if (!Schema::hasColumn('notifications', 'message'))
                $table->text('message')->nullable();

            if (!Schema::hasColumn('notifications', 'audience'))
                $table->string('audience')->default('admin');

            if (!Schema::hasColumn('notifications', 'recipient_email'))
                $table->string('recipient_email')->nullable();

            if (!Schema::hasColumn('notifications', 'type'))
                $table->string('type')->default('info');

            if (!Schema::hasColumn('notifications', 'is_read'))
                $table->boolean('is_read')->default(false);

            if (!Schema::hasColumn('notifications', 'link'))
                $table->string('link')->nullable();

            if (!Schema::hasColumn('notifications', 'sent_at'))
                $table->timestamp('sent_at')->nullable();
        });
    }

    public function down(): void
    {
        // This migration may encounter columns that existed before it ran.
        // Avoid dropping existing data or columns during rollback.
    }
};
