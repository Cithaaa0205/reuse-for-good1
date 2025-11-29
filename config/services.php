<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Layanan Eksternal
    |--------------------------------------------------------------------------
    |
    | Di sini kamu bisa menyimpan konfigurasi untuk berbagai layanan pihak
    | ketiga. Untuk sekarang kita cuma pakai OpenAI; kalau nanti mau tambah
    | mail, storage, dsb tinggal ditaruh di file ini juga.
    |
    */

    'openai' => [
    'api_key' => env('OPENAI_API_KEY'),
],

];
