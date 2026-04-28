<?php

use App\Models\Client;
use App\Models\DealColumn;
use App\Models\Quotation;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDealsTable extends Migration
{
    public function up()
    {
        Schema::create('deals', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(DealColumn::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Client::class)->nullable()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Quotation::class)->nullable()->constrained()->nullOnDelete();
            $table->integer('order')->default(1);
            $table->string('name');
            $table->longText('description')->nullable();
            $table->integer('chance_of_success')->nullable();
            $table->decimal('expected_revenue')->nullable();
            $table->date('expected_start_date')->nullable();
            $table->dateTime('due_date')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('deals');
    }
}
