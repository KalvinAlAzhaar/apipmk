        <?php

        use Illuminate\Database\Migrations\Migration;
        use Illuminate\Database\Schema\Blueprint;
        use Illuminate\Support\Facades\Schema;

        class CreateLaporansTable extends Migration
        {
            /**
             * Run the migrations.
             *
             * @return void
             */
            public function up()
            {
                Schema::create('laporans', function (Blueprint $table) {
                    $table->id();
                    $table->bigInteger('idkategori')->unsigned();
                    $table->bigInteger('idPelapor')->unsigned();
                    $table->string('lokasiKejadian');
                    $table->string('tglKejadian');
                    $table->string('isiLaporan');
                    $table->string('status')->default('Belum Selesai')->nullable();
                    $table->string('gambar');
                    $table->string('tgl_selesai')->nullable();
                    $table->timestamps();

                    $table->foreign('idPelapor')->references('id')->on('penggunas')->onDelete('cascade');
                    $table->foreign('idkategori')->references('id')->on('kategori')->onDelete('cascade');
                });
            }

            /**
             * Reverse the migrations.
             *
             * @return void
             */
            public function down()
            {
                Schema::dropIfExists('laporans');
            }
        }
