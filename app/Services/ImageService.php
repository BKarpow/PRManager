<?php
namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Jobs\AutoImageDownloadJob;

class ImageService
{

    private string $barcode = "";

    private function getApiUrl(string $s): string
    {
        $this->barcode = $s;
        return "https://www.googleapis.com/customsearch/v1?key=" . env('GOOGLE_API_KEY') . "&cx=" . env('GOOGLE_SEARCH_ENGINE_ID') . "&q={$s}&searchType=image";
    }

    private function getData(string $s)
    {
        // $response = Http::withoutVerifying()->get($this->getApiUrl($s));
//         $response = Http::withoutVerifying()->withHeaders([
//     'Ocp-Apim-Subscription-Key' => env('BING_SEARCH_KEY'),
// ])->get('https://api.bing.microsoft.com/v7.0/images/search', [
//     'q' => $s,
//     'count' => 2
// ]);
        $response = Http::withoutVerifying()->get('https://serpapi.com/search.json', [
            'engine'  => 'google_images', // Вказуємо, що шукаємо саме картинки
            'q'       => $s,         // Ваш штрихкод або назва
            'google_domain' => 'google.com.ua',
            'hl'      => 'uk',           // Мова інтерфейсу
            'gl'      => 'ua',           // Геолокація (Україна)
            'api_key' => env('SERP_API_KEY'),
        ]);
        // dd($response->json());
        if (isset($response->json()['images_results'])) {
            return $response->json()['images_results'][0];
        }
        return null;
    }

    public function getThumbUrl(string $s)
    {

        $r = $this->getData($s);
        $this->barcode = $s;
        return $r['thumbnail'] ?? null;
    }

    private function getImageName(string $ex): string
    {
        return "products/product_" . $this->barcode . "." . $ex;
    }

    public function downloadImageFromUrl(string $url)
    {
        try {
            // 1. Виконуємо запит до URL
            $response = Http::withoutVerifying()->get($url);

            if (!$response->successful()) {
                Log::error("Не вдалося завантажити файл. Статус: " . $response->status());
            }

            // 2. Отримуємо MIME-тип із заголовків (Content-Type)
            $contentType = $response->header('Content-Type');

            // Мапа популярних типів (можна розширити)
            $extensions = [
                'image/jpeg' => 'jpg',
                'image/png'  => 'png',
                'image/gif'  => 'gif',
                'image/webp' => 'webp',
                'image/svg+xml' => 'svg',
            ];

            // Визначаємо розширення або ставимо 'jpg' за замовчуванням
            $extension = $extensions[$contentType] ?? 'jpg';

            // 3. Генеруємо унікальне ім'я файлу
            $fileName = $this->getImageName($extension);

            // 4. Зберігаємо файл у сховище (диск 'public' за замовчуванням)
            Storage::disk('public')->put($fileName, $response->body());
            // dd($fileName);

            return "Файл успішно збережено: " . Storage::url($fileName);
        } catch (\Exception $e) {
            Log::error("Помилка завантаження зображення: " . $e->getMessage());
        }
    }

    public function getMissingBarcodes(int $limit = 100)
    {
        $files = Storage::disk('public')->files('products');
        $fileBarcodes = collect($files)->map(fn($p) => preg_replace('/[^0-9]/', '', basename($p)))
            ->unique()->toArray();

        return Product::pluck('barcode')
            ->diff($fileBarcodes)
            ->take($limit)
            ->values();
    }

    public function runOneJob(string $s)
    {
        // dd($s);
        $this->downloadImageFromUrl($this->getThumbUrl($s));
    }

    public function runAutoDownload()
    {
        $bc = $this->getMissingBarcodes(8);
        // dd($bc);

        foreach($bc as $b) {
            // $this->runOneJob($b);
            AutoImageDownloadJob::dispatch($b);
        }
    }
}
