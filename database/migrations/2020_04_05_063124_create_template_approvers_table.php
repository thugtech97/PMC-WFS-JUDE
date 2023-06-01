<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTemplateApproversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('template_approvers', function (Blueprint $table) {
            $table->id();
            $table->integer('template_id');
            $table->integer('approver_id');
            $table->integer('alternate_approver_id')->nullable();
            $table->integer('sequence_number');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('template_approvers');
    }
}
