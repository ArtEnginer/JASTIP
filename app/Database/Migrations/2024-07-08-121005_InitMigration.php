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

        Eloquent::schema()->create("shipments", function (Blueprint $table) {
            $table->id();
            $table->string("nomor_kontainer", 100)->unique();
            $table->string("nama_kontainer", 255)->nullable();
            $table->dateTime("tanggal_pengiriman");
            $table->dateTime("estimasi_sampai")->nullable();
            $table->enum("status_pengiriman", ['Persiapan', 'Dalam Perjalanan', 'Sampai Tujuan', 'Selesai'])->default('Persiapan');
            $table->integer("total_paket")->default(0);
            $table->decimal("total_bobot", 10, 2)->default(0);
            $table->text("keterangan")->nullable();
            $table->timestamps();
        });


        // table jasa titip barang
        Eloquent::schema()->create("jastip", function (Blueprint $table) {
            $table->id();
            $table->string("nomor_resi")->unique()->nullable();
            $table->string("nama_penerima")->nullable();
            $table->string("alamat_penerima")->nullable();
            $table->string("no_telp_penerima")->nullable();
            $table->decimal("biaya", 10, 2)->nullable();
            $table->decimal("bobot", 10, 2)->nullable();
            $table->string("keterangan")->nullable();
            $table->string("catatan")->nullable();
            $table->string("status")->default('pending');
            $table->dateTime("estimasi_sampai")->nullable();
            $table->foreignId('shipment_id')
                ->nullable()
                ->constrained("shipments")
                ->onUpdate('cascade')
                ->onDelete('set null');
            $table->timestamps();
        });

        // table status history
        Eloquent::schema()->create("status", function (Blueprint $table) {
            $table->id();
            $table->foreignId('jastip_id')
                ->constrained("jastip")
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string("status");
            $table->timestamps();
        });


        Eloquent::schema()->create("shipment_details", function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipment_id')
                ->constrained("shipments")
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('jastip_id')
                ->constrained("jastip")
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->timestamps();
        });

        Eloquent::schema()->create("pengaturan", function (Blueprint $table) {
            $table->id();
            $table->string("nominal_per_kg")->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Eloquent::schema()->dropIfExists('auth_jwt');
        Eloquent::schema()->dropIfExists('jastip');
        Eloquent::schema()->dropIfExists('status');
    }
}
