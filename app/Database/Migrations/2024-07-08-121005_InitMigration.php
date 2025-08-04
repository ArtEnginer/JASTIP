<?php

namespace App\Database\Migrations;

use App\Libraries\Eloquent;
use CodeIgniter\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class InitMigration extends Migration
{
    public function up()
    {
        Eloquent::schema()->create("auth_jwt", function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->foreignUuid('user_id')
                ->constrained("users")
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->text("access_token");
            $table->string("refresh_token");
            $table->timestamps();
        });

        // table jasa titip barang
        Eloquent::schema()->create("jastip", function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->foreignUuid('user_id')
                ->constrained("users")
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string("keterangan")->nullable();
            $table->string("catatan")->nullable();
            $table->string("status")->default('pending');
            $table->timestamps();
        });

        Eloquent::schema()->create("jastip_detail", function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->foreignUuid('jastip_id')
                ->constrained("jastip")
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string("nama_barang");
            $table->integer("jumlah");
            $table->string("keterangan")->nullable();
            $table->string("catatan")->nullable();
            $table->string("status")->default('pending');
            $table->timestamps();
        });
    }

    public function down()
    {
        Eloquent::schema()->dropIfExists('auth_jwt');
        Eloquent::schema()->dropIfExists('jastip');
        Eloquent::schema()->dropIfExists('jastip_detail');
    }
}
