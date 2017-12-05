<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

$component_table_name = 'component_example';

if (! Schema::hasColumn($component_table_name, 'data')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->string('data')->nullable();
    });
}
