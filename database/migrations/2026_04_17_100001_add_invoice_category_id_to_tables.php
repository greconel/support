<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        $defaultCategoryId = DB::table('invoice_categories')->where('name', 'IT')->value('id');

        Schema::table('clients', function (Blueprint $table) {
            $table->foreignId('invoice_category_id')->nullable()->after('peppol_only')->constrained('invoice_categories')->nullOnDelete();
        });

        Schema::table('suppliers', function (Blueprint $table) {
            $table->foreignId('invoice_category_id')->nullable()->after('is_disabled_for_clearfacts')->constrained('invoice_categories')->nullOnDelete();
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->foreignId('invoice_category_id')->nullable()->after('po_number')->constrained('invoice_categories')->nullOnDelete();
        });

        Schema::table('expenses', function (Blueprint $table) {
            $table->foreignId('invoice_category_id')->nullable()->after('various_transaction_category')->constrained('invoice_categories')->nullOnDelete();
        });

        // Retroactively set all existing records to "IT"
        if ($defaultCategoryId) {
            DB::table('clients')->whereNull('invoice_category_id')->update(['invoice_category_id' => $defaultCategoryId]);
            DB::table('suppliers')->whereNull('invoice_category_id')->update(['invoice_category_id' => $defaultCategoryId]);
            DB::table('invoices')->whereNull('invoice_category_id')->update(['invoice_category_id' => $defaultCategoryId]);
            DB::table('expenses')->whereNull('invoice_category_id')->update(['invoice_category_id' => $defaultCategoryId]);
        }
    }

    public function down()
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropConstrainedForeignId('invoice_category_id');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropConstrainedForeignId('invoice_category_id');
        });

        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropConstrainedForeignId('invoice_category_id');
        });

        Schema::table('clients', function (Blueprint $table) {
            $table->dropConstrainedForeignId('invoice_category_id');
        });
    }
};
