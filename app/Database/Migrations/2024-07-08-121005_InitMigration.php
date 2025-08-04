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

        // kategori
        Eloquent::schema()->create("kategori", function (Blueprint $table) {
            $table->id();
            $table->string("kode")->unique();
            $table->string("nama");
            $table->text("deskripsi")->nullable();
            $table->timestamps();
        });

        Eloquent::schema()->create("produk", function (Blueprint $table) {
            $table->id();
            $table->string("kode")->unique();
            $table->string("nama");
            $table->text("deskripsi")->nullable();
            $table->string("gambar")->nullable();
            $table->decimal("harga", 10, 2);
            $table->integer("stok")->default(0);
            $table->string("kategori_kode")->nullable();
            $table->foreign("kategori_kode")
                ->references("kode")
                ->on("kategori")
                ->onUpdate("cascade")
                ->onDelete("set null");
            $table->timestamps();
        });



        // transaksi
        Eloquent::schema()->create("transaksi", function (Blueprint $table) {
            $table->id();
            $table->foreignUuid("user_id")
                ->constrained("users")
                ->onUpdate("cascade")
                ->onDelete("cascade");
            // order_id
            $table->string("order_id")->unique();
            $table->string("total_harga")->nullable();
            $table->string("status")->default("pending");
            // payment_method
            $table->string("payment_method")->nullable();
            $table->string("nama_penerima")->nullable();
            $table->string("alamat_penerima")->nullable();
            $table->string("email_penerima")->nullable();
            $table->string("nomor_telepon_penerima")->nullable();

            $table->timestamps();
        });

        // detail_transaksi
        Eloquent::schema()->create("detail_transaksi", function (Blueprint $table) {
            $table->id();
            $table->foreignId("transaksi_id")
                ->constrained("transaksi")
                ->onUpdate("cascade")
                ->onDelete("cascade");
            $table->foreignId("produk_id")
                ->constrained("produk")
                ->onUpdate("cascade")
                ->onDelete("cascade");
            $table->integer("jumlah")->default(1);
            $table->decimal("harga", 10, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Eloquent::schema()->dropIfExists('auth_jwt');
        Eloquent::schema()->dropIfExists('kategori');
        Eloquent::schema()->dropIfExists('produk');
        Eloquent::schema()->dropIfExists('transaksi');
        Eloquent::schema()->dropIfExists('detail_transaksi');
    }
}
