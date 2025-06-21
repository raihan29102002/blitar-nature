<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Wisata;
use App\Models\Fasilitas;
use Illuminate\Support\Facades\DB;

class FasilitasWisataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $wisatas = Wisata::whereBetween('id', [57, 132])->get();
        $fasilitas = Fasilitas::all()->pluck('id')->toArray();

        foreach ($wisatas as $wisata) {
            $jumlahFasilitas = rand(2, 5);
            $fasilitasDipilih = collect($fasilitas)->random($jumlahFasilitas);

            foreach ($fasilitasDipilih as $fasilitasId) {
                DB::table('fasilitas_wisata')->insert([
                    'wisata_id' => $wisata->id,
                    'fasilitas_id' => $fasilitasId,
                ]);
            }
        }
    }
}
